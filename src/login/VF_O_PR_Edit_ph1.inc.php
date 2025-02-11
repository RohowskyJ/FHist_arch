<?php

/**
 * Lste der Presse, Wartung, Daten schreiben
 *
 * @author Josef Rohowsky - neu 2018
 *
 *
 */
if ($debug) {
    echo "<pre class=debug>VFH_O_PR_Edit_ph1.php ist gestarted</pre>";
}

foreach ($_POST as $name => $value) {
    $neu[$name] = mysqli_real_escape_string($db, $value);
}

if (isset($_FILES['uploaddatei_1']['name'])) {
    $uploaddir = $path2ROOT."login/AOrd_Verz/Presse/";
    
    if (! file_exists($uploaddir)) {
        mkdir($uploaddir, 0777, true);
    }
    
    if ($_FILES['uploaddatei_1']['name'] != "" ) {
        $neu['pr_bild1'] = VF_Upload($uploaddir, 1);
    }
    if ($_FILES['uploaddatei_2']['name'] != "" ) {
        $neu['pr_bild2'] = VF_Upload($uploaddir, 2);
    }
    if ($_FILES['uploaddatei_3']['name'] != "" ) {
        $neu['pr_bild3'] = VF_Upload($uploaddir, 3);
    }
    if ($_FILES['uploaddatei_4']['name'] != "" ) {
        $neu['pr_bild4'] = VF_Upload($uploaddir, 4);
    }
    
    if ($_FILES['uploaddatei_5']['name'] != "" ) {
        $neu['pr_bild5'] = VF_Upload($uploaddir, 5);
    }
}

$p_uid = $_SESSION['VF_Prim']['p_uid']; 
if ($debug) {
    echo '<pre class=debug>';
    echo '<hr>$neu: ';
    print_r($neu);
    echo '</pre>';
}

if ($neu['pr_id'] == 0) { # neueingabe
    $sql = "INSERT INTO pr_esse (
              pr_datum,pr_name,pr_ausg,pr_medium,pr_seite,
              pr_teaser,pr_text,pr_bild1,pr_bild2,pr_bild3,pr_bild4,
              pr_bild5,pr_web_site,pr_web_text,pr_inet,pr_uidaend
              ) VALUE (
               '$neu[pr_datum]','$neu[pr_name]','$neu[pr_ausg]','$neu[pr_medium]','$neu[pr_seite]',
               '$neu[pr_teaser]','$neu[pr_text]','$neu[pr_bild1]','$neu[pr_bild2]','$neu[pr_bild3]','$neu[pr_bild4]',
               '$neu[pr_bild5]','$neu[pr_web_site]','$neu[pr_web_text]','$neu[pr_inet]','$p_uid'
               )";

    $result = SQL_QUERY($db, $sql) ;
    
} else { # update
   
    $updas = ""; # assignemens for UPDATE xxxxx SET `variable` = 'Wert'

    foreach ($neu as $name => $value) # für alle Felder aus der tabelle
    {
        if (! preg_match("/[^0-9]/", $name)) {
            continue;
        } # überspringe Numerische Feldnamen
        if ($name == "MAX_FILE_SIZE") {
            continue;
        } #
        if ($name == "phase") {
            continue;
        } #
        if ($name == "pr_bild11") {
            continue;
        }
        if ($name == "pr_bild22") {
            continue;
        }
        if ($name == "pr_bild33") {
            continue;
        }
        if ($name == "pr_bild44") {
            continue;
        }
        if ($name == "pr_bild55") {
            continue;
        }
        if ($name == "pr_id") {
            continue;
        }

        $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife

    $updas = mb_substr($updas, 1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind

    $sql = "UPDATE `pr_esse` SET  $updas WHERE `pr_id`='$neu[pr_id]' ";
    if ($debug) {
        echo '<pre class=debug> L 0197: \$sql $sql </pre>';
    }

    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
    $result = SQL_QUERY($db, $sql);

}

header("Location: VF_O_PR_List.php?Act=" . $_SESSION[$module]['Act']);

if ($debug) {
    echo "<pre class=debug>VFH_O_PR_Edit_ph1.php beendet </pre>";
}
?>