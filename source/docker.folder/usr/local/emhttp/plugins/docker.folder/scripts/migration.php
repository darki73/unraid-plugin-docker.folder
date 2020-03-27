<?php
    $GLOBALS['newFoldersVersion'] = 2;
    init();
    function init() {
        $path = '/boot/config/plugins/docker.folder/';
        $foldersFile = $path.'folders.json';
        if ( file_exists($foldersFile ) ) {
            $folders_file = file_get_contents($path.'folders.json');
            $folders = json_decode($folders_file, true);

            // exit if there are no folders
            if (count($folders) == null || count($folders) < 2) {
                exit();
            }

            file_put_contents($path.'folders.backup.json', $folders_file);

            if ($folders['foldersVersion'] == null) {
                $folders = migration_1($folders);
            }

            $folders['foldersVersion'] = $GLOBALS['newFoldersVersion'];

            $jsonData = json_encode($folders, JSON_PRETTY_PRINT);
            file_put_contents($path.'folders.json', $jsonData);
        }
    }

    function migration_1($folders) {
        echo("migration_1");
        foreach ($folders as $folderKey => &$folder) {
            if($folderKey == 'foldersVersion');
            foreach ($folder['buttons'] as $buttonKey => &$button) {

                // if its got type just skip
                if($button['type'] !== null) {
                    continue;
                }

                $isBash = true;

                // WebUI
                if ($button['name'] == 'WebUI') {
                    $isBash = false;

                    $button['type'] = 'WebUI';
                }

                // Docker_Default
                if ($button['cmd'] == 'Docker_Default') {
                    $isBash = false;

                    $button['type'] = 'Docker_Default';
                    $button['cmd'] = strtolower($button['name']);
                } 
                
                // bash
                if ($isBash == true) {
                    $button['type'] = 'Bash';
                }
            }
        }

        return $folders;
    }


    