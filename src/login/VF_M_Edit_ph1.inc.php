<?php

/**
 * Mitgliederverwaltung, Date abspeichern
 *
 * @author Josef Rohowsky - neu 2020
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_M_Edit_ph1.inc.php ist gestarted</pre>";
}

if ($debug) {
    echo '<pre class=debug>';
    echo '<hr>$neu: ';
    var_dump($neu);
    echo '<hr>$_SESSION[$module] : ';
    print_r($_SESSION[$module]);
    echo '</pre>';
}
echo "L 021 sterbdat ".$neu['mi_sterbdat']."<br>";
if ($neu['mi_sterbdat'] > "0000-00-00") {
   echo "Replace E-Mail Addr und Tel mit blank <br>";   
}

$p_uid = $_SESSION['VF_Prim']['p_uid'];

if ($neu['mi_id'] == 0) { // Neu anlegen eines Mitglieds- Datensatzes
    $sql = "INSERT INTO fh_mitglieder (
              mi_mtyp,mi_org_typ,mi_org_name,mi_name,mi_vname,mi_titel,
              mi_n_titel,mi_dgr,mi_anrede,mi_gebtag,mi_staat,mi_plz,mi_ort,mi_anschr,
              mi_tel_handy,mi_fax,mi_email,mi_vorst_funct,mi_ref_leit,mi_ref_int_2,mi_ref_int_3,mi_ref_int_4,
              mi_vorst_funct,mi_ref_leit,mi_sterbdat,mi_beitritt,mi_austrdat,
              mi_einv_art,mi_einversterkl,mi_einv_dat,mi_uidaend,mi_aenddat
              ) VALUE (
               '$neu[mi_mtyp]','$neu[mi_org_typ]','$neu[mi_org_name]','$neu[mi_name]','$neu[mi_vname]','$neu[mi_titel]',
               '$neu[mi_n_titel]','$neu[mi_dgr]','$neu[mi_anrede]','$neu[mi_gebtag]','$neu[mi_staat]','$neu[mi_plz]','$neu[mi_ort]','$neu[mi_anschr]',
               '$neu[mi_tel_handy]','$neu[mi_fax]','$neu[mi_email]','$neu[mi_vorst_funct]','$neu[mi_ref_leit]','$neu[mi_ref_int_2]','$neu[mi_ref_int_3]','$neu[mi_ref_int_4]',
               '$neu[mi_ref_leivorst_funct]','$neu[mi_ref_leit]','$neu[mi_sterbdat]','$neu[mi_beitritt]','$neu[mi_austrdat]',
               '$neu[mi_einv_art]','$neu[mi_einversterkl]','$neu[mi_einv_dat]','$p_uid',now()
               )";

    $result = SQL_QUERY($db, $sql);
    $_SESSION['neu_mitgl']['neu_mi_id'] = mysqli_insert_id($db);

    $datum = date("d.m.Y:");
    $zeit = date("H:i:s");

    $dsn = "../login/logs/anmeldlog";

    $log_rec = "**** PFLICHTFELDER **** \nAnrede: " . $neu['mi_anrede'] . "\n";
    $log_rec .= "Familienname: " . $neu['mi_name'] . "\n";
    $log_rec .= "Vorname: " . $neu['mi_vname'] . "\n";
    $log_rec .= "E-Mail: " . $neu['mi_email'] . "\n";
    $log_rec .= "Adresse: " . $neu['mi_anschr'] . "\n";
    $log_rec .= "PLZ: " . $neu['mi_plz'] . "\n";
    $log_rec .= "Ort: " . $neu['mi_ort'] . "\n";

    // ******** Optionale Felder ********
    $log_rec .= "**** Optionale Felder ****\nTitel: " . $neu['mi_titel'] . "\n";
    $log_rec .= "**** Optionale Felder ****\n nachfolg. Titel: " . $neu['mi_n_titel'] . "\n";

    $log_rec .= "Tel Nummmer: " . $neu['mi_tel_handy'] . "\n";
    $log_rec .= "Mobil Nummer: " . $neu['mi_handy'] . "\n";
    $log_rec .= "Fax: " . $neu['mi_fax'] . "\n";
    $log_rec .= "Geburtsdatum: " . $neu['mi_gebtag'] . "\n";
    $log_rec .= "Oganisationstyp: " . $neu['mi_org_typ'] . "\n";
    $log_rec .= "Organisation: " . $neu['mi_org_name'] . "\n";
    # $log_rec .= "Referatsfunktion: ".$neu['mi_ref_int']."\n";
    # $log_rec .= "Referatsmitarbeit: ".$neu['mi_ref_ma']."\n";
    # $log_rec .= "Referatsinormation: ".$neu['mi_ref_int']."\n";
    $log_rec .= "Einverstaendniserklaerung: " . $neu['mi_einversterkl'] . " $datum $zeit " . $neu['mi_einv_art'] . "\n";
    $log_rec .= "Mitgliedsnummer:  " . $_SESSION['neu_mitgl']['neu_mi_id'] . "\n";
    $text = " $log_rec ***** \"LOG ENDE\" *****\n";
    $text .= "Orig.TCP = " . $_SERVER['REMOTE_ADDR'] . "\n";

    $fname = writelog($dsn, $text);
    $tr = array(
        "\n" => "<br>"
    );
    $text = strtr($text, $tr);

    $adr_list = Mail_Set('Mitgl');

    if ($module == "0_EM") {
        sendEMail($neu['mi_email'] . ", $adr_list , josef@kexi.at", "VFHNÖ Mitglieds- Neuanmeldung ", $text); # service@feuerwehrhistoriker.at, helmut-riegler@aon.at, f.blueml@gmx.at"
    }

    $text = "Zur Info: Soeben " . $datum . " / " . $zeit . " wurde eine Anmeldung online  dem System übergeben.\n";
    $text .= "Im Formular wurden u.A. Name / Vorname / Emailadresse erfasst: " . $neu['mi_name'] . " / " . $neu['mi_vname'] . " / " . $neu['mi_email'] . ".\n";
    IF (! empty($ftext)) {
        $text .= "\nAchtung fehlende Pflichtangaben: $ftext\n\n";
    }
    $text .= "Weitere Infos sind im Anmeldelog ersichtlich.\n";
    $text .= "http://www.feuerwehrhistoriker.at/login/log/DSVGO_log/";
    $text .= "\nBitte beobachten ob die Anmeldung korrekt beendet wurde\n";
    $text .= "und zu einem Teilnehmer Aufnameantrag geführt hat!\n";
    $text .= "Zum Ansehen des Logs folgenden Link anklicken:\n";
    $text .= "http://www.feuerwehrhistoriker.at/login/log/dir.php\n";
    $text .= "Mitgliedsnummer:  " . $_SESSION['neu_mitgl']['neu_mi_id'] . "\n";
    $text .= "Anmeldelog  Mail Ende\n";

    VF_sendEmail("$adr_list, josef@kexi.at", // Empänger(Liste)
    "Neuanmeldung " . $neu['mi_name'] . " ", // Subject Text der EMail
    $text, // Inhalt der Email in HTML format
    "service@feuerwehrhistoriker.at"); // optionale 'Reply-To' E-Mail-Adresse
    
    header ("Loction: /indx.php");
} else { // ändern eines Mitglieds- Daensatzes
         # Sichern der Originaldaten in die Historie
    $tabelle = 'fh_mitglieder';

    $sql = "SELECT * FROM $tabelle WHERE mi_id = '$mi_id'";
    if ($debug) {
        echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
    }
    $result = SQL_QUERY($db, $sql);
    $num_rows = mysqli_num_rows($result);
    if ($num_rows !== 1) {
        echo "<p style='color:red;font-size:150%;font-weight:bold;' >$num_rows Eintragungen mit der mi_id Nummer $mi_id gefunden</p>";
        HTML_trailer();
        exit();
    }

    $row = mysqli_fetch_array($result);
    if ($debug) {
        echo '<pre class=debug>';
        echo '<hr>$row: ';
        print_r($row);
        echo '</pre>';
    }

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
        }
        if ($name == "p_uid") {
            continue;
        }
        if ($name == "tabelle") {
            continue;
        }
        
        $updas .= ",`$name`='" . $neu[$name] . "'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife

    $updas = "`mi_uidaend`='$p_uid'" . $updas;
    # echo "\$updas $updas <br>";
    if ($_SESSION[$module]['all_upd'] || $_SESSION['VF_Prim']['p_uid'] == $neu['mi_id']) {
        $sql = "UPDATE `fh_mitglieder` SET  $updas WHERE `mi_id`='$mi_id'";
        if ($debug) {
            echo '<pre class=debug> L 0197: \$sql $sql </pre>';
        }

        $result = SQL_QUERY($db, $sql);
        $_SESSION['neu_mitgl']['neu_mi_id'] = $mi_id;
    }

    $logtext = "Änderungen in $tabelle für " . $neu['mi_name'] . "  " . $neu['mi_vname'] . " " . $_SESSION['neu_mitgl']['neu_mi_id'] . " \nMitgliedsdaten geändert oder neu angelegt von Benutzer $p_uid ";
    writelog($path2ROOT . "login/logs/MitglLog/Mitgl_aenderg_log", $logtext);
}

header ("Location: VF_M_List.php");
if ($debug) {
    echo "<pre class=debug>VF_M_Edit_ph1.inc.php beendet</pre>";
}
?>