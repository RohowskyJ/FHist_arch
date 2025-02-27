<?php

/**
 * Cleanup der Installations- Routinen
 *  neu J.Rohowsky 2025
 * 
 * Die Datei /install/install.php wird  umbenannt, da dir löschen so nicht geht - erweiterung?
 * 
 */
if (is_file('install.php')) {
    $result = rename("install.php", "!install.php");
    if ($result) {echo "Installations- Script wurde umbenannt.<br>";}
}

?>