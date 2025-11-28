<?php

/**
 * Laden der Daten in die Foto-Tabellen des gewählten Eigentümers, Daten in die Tabellen enfügn
 *
 * @author  Josef Rohowsky - neu 2025
 *
 *
 */

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
# $_SESSION[$module]['Inc_Arr']  = array();
$_SESSION[$module]['Inc_Arr'][] = "VF_C_MassUp_FO_ph2.inc.php.php";

if ($debug) {
    echo "<pre class=debug>VF_MassUp_FO_ph2.inc.php ist gestarted</pre>";
}

if (isset($_POST['aufn_dat'])) {
    $aufn_dat = $_SESSION[$module]['Up_Parm']['aufn_dat'] = $_POST['aufn_dat'];
    $trans = array('-'=>'_','/'=>'_');
    $aufn_dat = strtr($aufn_dat,$trans);
}
if (isset($_POST['beschreibg'])) {
    $beschreibg = $_SESSION[$module]['Up_Parm']['beschreibg'] = $_POST['beschreibg'];
}
$urheinfueg = "N";
if (isset($_POST['urheinfueg'])) {
    $urheinfueg = $_SESSION[$module]['Up_Parm']['urheinfueg'] = $_POST['urheinfueg'];
}

$from_pf = $_SESSION[$module]['Up_Parm']['pfad'] = "VF_Upload/".$_SESSION['VF_Prim']['p_uid']."/";

// anlegen des Verzeichnis- Records

/**
 * ist Datenrecord vorhanden -> ersetzten des Dateinamens - sonst neuer Datensatz
 */
$eignr = $_SESSION['Eigner']['eig_eigner'];
$tabelle_in = "dm_edien_$eignr";

Cr_n_Medien_Daten($tabelle_in);

$sql = "SELECT * FROM $tabelle_in where md_aufn_datum = '$aufn_dat' AND md_dsn_1 = '0_Verz' ";
$urhname = $_SESSION['Eigner']['eig_urhname'];

$media = "Foto";

$return = SQL_QUERY($db, $sql);

if (mysqli_num_rows($return) == "0") {

    $sql = "INSERT INTO $tabelle_in (
                         md_eigner,md_urheber,md_dsn_1,md_aufn_datum,md_beschreibg,md_namen,
                         md_sammlg,md_media,
                         md_aenduid
                      ) VALUE (
                        '$eignr','$urhname','0_Verz','$aufn_dat','$beschreibg','',
                        '','$media',
                        '" . $_SESSION['VF_Prim']['p_uid'] . "'
                      )";
    $result = SQL_QUERY($db, $sql);
} else {
    #echo "L 0144 Verzeichnis- Datensatz vorhanden, könnte geändert werden <br>";
    /*
     * $sql = "UPDATE $tabelle SET $updas WHERE `fo_id`='".$_SESSION[$module]['fo_id']."'";
     * if ( $debug ) { echo '<pre class=debug> L 047: \$sql $sql </pre>'; }
     *
     * echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
     * $result = VF_SQL_QUEry($db,$sql);
     */

}

// Ausgabe der notwendigen Parameter als hidden input

Edit_Tabellen_Header("Hochladen von Mediendaten für Urheber: $urhname");
Edit_Separator_Zeile("Aufnahmedatum: $aufn_dat ");

echo "<p>Titel: $beschreibg</p>";
;

echo "<input type='hidden' id='urhName' value='$urhname' >";
echo "<input type='hidden' id='urhNr' name='urhNr' value='$eignr' >";
echo "<input type='hidden' id='aufnDat' value='$aufn_dat' >";

echo "<input type='hidden' id='urhEinfg' value='$urheinfueg' >";
echo "<input type='hidden' id='subMod' value='FOTO|Upl' >";

if ($debug) {
    echo "<pre class=debug>VF_C_MassUp_FO_ph2.inc.php ist beendet</pre>";
}

