<?php
$module = 'phpinfo';
$debug = True;
$debug = False; // Debug output Ein/Aus Schalter
                # ----------------------------------------------------------------------------------------------
                # php Env Vars anzeigen
                #
                # wird nur in /login/comon/index verwendet !
                #
                # 2022-02-05 B.R.Gaicki - V5 (PixRipTab & login )
                # -----------------------------------------------------------------------------------------------
require 'Funcs.inc.php'; // Diverse Unterprogramme

initial_debug(); # Wenn $debug=true - Ausgabe von Debug Informationen: $_POST, $_GET, $_FILES
                 # var_dump(php_ini_loaded_file(), php_ini_scanned_files());

HTML_header('PHPINFO anzeigen', '', '', '', '');
phpinfo();

HTML_trailer();
?>
