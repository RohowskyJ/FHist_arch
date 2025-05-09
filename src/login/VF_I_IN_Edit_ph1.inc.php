<?php 
/**
 * Inventarverwaltung für Feuerwehren, Datenspeicherung
 * 
 * @author  Josef Rohowsky <josef@kexi.at>
 */

if ($debug) {echo "<pre class=debug>VF_I_IN_Edit_ph1.inc ist gestarted</pre>";}

foreach ($_POST as $name => $value)
{ $neu[$name] = mysqli_real_escape_string($db,$value); }

$neu['in_uidaend'] = $_SESSION['VF_Prim']['p_uid'];

if ( $debug ) { echo '<pre class=debug>';echo '<hr>$neu: ';     print_r($neu); echo '</pre>'; }
var_dump($_FILES);
$neu['in_eignr'] = $_SESSION['Eigner']['eig_eigner'];

/* Sammlung aufbereiten */
if (isset($_POST['level1']) != "") {
    $response = VF_Multi_Sel_Input();
    if ($response == "" || $response == "Nix") {
        
    } else {
        $neu['in_sammlg'] = $response;
    }
}

if (isset($_FILES['uploaddatei_1']['name'])) {
   
    $uploaddir = "AOrd_Verz/".$_SESSION['Eigner']['eig_eigner']."/INV/";
    
    # echo "L 031 \$uploaddir $uploaddir <br/>";
    if (!file_exists($uploaddir)) {
        mkdir($uploaddir,'0770',true);
    }
    
    if ($_FILES['uploaddatei_1']['name'] != "" ) {
        $neu['in_foto_1'] = VF_Upload($uploaddir, 1);
    }
    if ($_FILES['uploaddatei_2']['name'] != "" ) {
        $neu['in_foto_2'] = VF_Upload($uploaddir, 2);
    }
    
}

$neu['in_uidaend'] = $_SESSION['VF_Prim']['p_uid'];

if ($_SESSION[$module]['in_id'] == 0) {
   var_dump($neu);
    $in_sm = $neu['in_sammlg'];
    $sql_in_flnr = "select * FROM `$tabelle_a` WHERE `in_sammlg`='$in_sm' ";
    $return_in_flnr = SQL_QUERY($db,$sql_in_flnr);
    if ($return_in_flnr ) {
        $row = mysqli_fetch_object($return_in_flnr);
        $numrow = mysqli_num_rows($return_in_flnr);
        $neu['in_invnr'] = $numrow +1;
    } else {
        $neu['in_invnr'] = 1;
    }

    $sql = "INSERT INTO $tabelle_a (
                ei_id,in_invjahr,in_eingbuchnr,in_eingbuchdat,in_altbestand,in_invnr,
                in_sammlg,in_epoche,
                in_zustand,in_entstehungszeit,in_hersteller,in_herstld,
                in_aufbld_1,in_aufbld_2,in_aufbld_3,in_nutzld,in_bezeichnung,in_beschreibg,in_wert,
                in_wert_neu,in_neu_waehrg,in_wert_kauf,in_kauf_waehrung,in_wert_besch,in_besch_waehrung,
                in_abmess,in_gewicht,in_linkerkl,in_kommentar,
                in_namen,
                in_foto_1,in_fbeschr_1,in_foto_2,in_fbeschr_2,
                in_refindex,in_raum,in_platz,in_erstdat,
                in_ausgdat,in_neueigner,in_uidaend,in_aenddat
              ) VALUE (
                '$neu[ei_id]','$neu[in_invjahr]','$neu[in_eingbuchnr]','$neu[in_eingbuchdat]','$neu[in_altbestand]','$neu[in_invnr]',
                '$neu[in_sammlg]','$neu[in_epoche]',
                '$neu[in_zustand]','$neu[in_entstehungszeit]','$neu[in_hersteller]','$neu[in_herstld]',
                '$neu[in_aufbld_1]','$neu[in_aufbld_2]','$neu[in_aufbld_3]','$neu[in_nutzld]','$neu[in_bezeichnung]','$neu[in_beschreibg]','$neu[in_wert]',
                '$neu[in_wert_neu]','$neu[in_neu_waehrg]','$neu[in_wert_kauf]','$neu[in_kauf_waehrung]','$neu[in_wert_besch]','$neu[in_besch_waehrung]',
                '$neu[in_abmess]','$neu[in_gewicht]','$neu[in_linkerkl]','$neu[in_kommentar]',
                '$neu[in_namen]',
                '$neu[in_foto_1]','$neu[in_fbeschr_1]','$neu[in_foto_2]','$neu[in_fbeschr_2]',
                '$neu[in_refindex]','$neu[in_raum]','$neu[in_platz]','$neu[in_erstdat]',
                '$neu[in_ausgdat]','$neu[in_neueigner]','$neu[in_uidaend]',now()
               )";
    
    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
    $result = SQL_QUERY($db,$sql); // or die('INSERT nicht möglich: ' . mysqli_error($db));
 
    $neu['in_id'] = mysqli_insert_id($db);
    
} else {
    $updas   = ""; # assignemens for UPDATE xxxxx SET `variable` = 'Wert'
    
    foreach ($neu as $name => $value) # für alle  Felder aus der tabelle
    {
        if ( !preg_match ("/[^0-9]/", $name) ) {continue;}    # überspringe Numerische Feldnamen
        if ($name == "MAX_FILE_SIZE") {continue;}    #
        if ($name == "in_foto_11") {continue;}    #
        if ($name == "in_foto_22") {continue;}    #
        if ($name == "tabelle") {continue;}    #
        if (substr($name,0,5) ==  "level") {continue;}
        
        if ($name == "in_eignr") {continue;}   #
        if ($name == "InvNr") {continue;}   #
        if ($name == "eigentmr") {continue;}   #
        
        if ($name == "sammlg") { continue;}    #
        if ($name == "phase") {continue;}    #
        if ($name  = 'sa_name') {continue;} 
            
        $updas .= ",`$name`='".$neu[$name]."'"; # weiteres SET `variable` = 'Wert' fürs query
    } # Ende der Schleife
    
    #$updas = mb_substr($updas,1); # 1es comma entfernen nur notwendig, wenn vorer keine Update-Strings sind
    $updas = "in_aenddat=now(),in_uidaend='".$neu['in_uidaend']."'";
    if ( $_SESSION[$module]['all_upd']  ) {
        $sql = "UPDATE $tabelle_a SET $updas WHERE `in_id`='".$neu['in_id']."'";
        if ( $debug ) { echo '<pre class=debug> L 0197: \$sql $sql </pre>'; }
        
        echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>$sql</pre>";
        $result = mysqli_query($db,$sql) or die('UPDATE nicht möglich: ' . mysqli_error($db));
    }

    
}

if ($neu['in_namen'] != "")    {
 #   require('../add_namefind.php');
    VF_Add_Namen($tabelle_a,$neu['in_id'],'in_id',$neu['in_namen']) ;
}
    
header("Location: VF_I_IN_List.php");

# =========================================================================================================
 
if ($debug) {echo "<pre class=debug>VF_I_IN_Edit_ph1.inc beendet</pre>";}
?>