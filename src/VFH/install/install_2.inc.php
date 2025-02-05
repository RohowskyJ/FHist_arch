<?php
if ($debug) {
    echo "<pre class=debug>install_2.inc.php ist gestarted</pre>";
}

$updas = ""; # assignements for UPDATE xxxxx SET `variable` = 'Wert'

if (isset($_POST)) {
    $first_l = $first_h = True;
    
    foreach($_POST as $name => $value) {
        if ($name == "phase") {
            continue;
        } #
        
        if ($first_l && substr($name,0,2) == "l_" ) {
            $updas = "\n[localhost] \n";
            $first_l = False;
        }
        if ($first_h && substr($name,0,2) == "h_" ) {
            $updas .= "\n[HOST] \n";
            $first_h = False;
        }
        $updas .= "$name='$value'\n"; # weiteres SET `variable` = 'Wert' fürs query

    }
    $dsn = $path2ROOT."login/common/config_d.ini";
    
    $datei = fopen($dsn, 'w');
    fputs($datei,$updas);
    fclose($datei);
} # Ende der Schleife



header ('Location: install.php?phase=3');

if ($debug) {
    echo "<pre class=debug>install_2.inc.php beendet</pre>";
}
?>