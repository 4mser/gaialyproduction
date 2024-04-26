<?php
function removeDirectory($dirPath) {
    if (!is_dir($dirPath)) {
        // Check if the given path is a directory
        return false;
    }

    // Get the list of files and directories in the given directory
    $contents = scandir($dirPath);

    foreach ($contents as $item) {
        if ($item !== '.' && $item !== '..') {
            // Ignore '.' and '..' entries
            $itemPath = $dirPath . DIRECTORY_SEPARATOR . $item;

            if (is_dir($itemPath)) {
                // Recursively delete subdirectories
                removeDirectory($itemPath);
            } else {
                // Delete files
                unlink($itemPath);
            }
        }
    }

    // Remove the empty directory itself
    rmdir($dirPath);
    return true;
}
