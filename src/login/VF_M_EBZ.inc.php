<?php

/**
 *  Anlegen Eigentümer und Benutzer nach Anmeldung eines neuen Mitgliedes.
 *
 * @author Josef Rohowsky - neu 2018
 *
 * Einlesen Mitgliedsdaten, Aufbereiten Daten für Eigentümer- und Benutzer- Tabelle
 *
 *
 */
# $debug = True;
if ($debug) {
    echo "<pre class=debug>VF_M_EBZ.inc.php ist gestarted</pre>";
}

if ($debug) {
    echo '<pre class=debug>';
    echo 'VF_M_EZB.inc Neuanlegen Eigentümer, User und Berechtgung ';
}

$neu_mi_id = $_SESSION['neu_mitgl']['neu_mi_id'];

$select = "WHERE  `mi_id`='" . $_SESSION['neu_mitgl']['neu_mi_id'] . "' ";

$sql_mi = "SELECT * FROM fh_mitglieder $select ORDER BY `mi_name` ASC";
$return_mi = SQL_QUERY($db, $sql_mi);

while ($row = mysqli_fetch_object($return_mi)) {

    $mitgl_nr = $row->mi_id; #
    $mitgl_org_typ = $row->mi_org_typ; #
    $mitgl_org_name = $row->mi_org_name; #
    $mitgl_typ = $row->mi_mtyp;
    $mitgl_dgr = $row->mi_dgr;
    $mitgl_anred = $row->mi_anrede;
    $mitgl_titel = $row->mi_titel; #
    $mitgl_n_titel = $row->mi_n_titel;
    $mitgl_vname = $row->mi_vname; #
    $mitgl_name = $row->mi_name; #
    $mitgl_gebtag = $row->mi_gebtag;
    $mitgl_addr = $row->mi_anschr;
    $mitgl_staat = $row->mi_staat;
    $mitgl_plz = $row->mi_plz; #
    $mitgl_ort = $row->mi_ort; #
    $mitgl_tel = $row->mi_tel_handy; #
    $mitgl_fax = $row->mi_fax; #
    $mitgl_email = $row->mi_email; #
    $mitgl_ref_l = $row->mi_ref_leit;
    $mitgl_beitrd = $row->mi_beitritt;
    $mitgl_austrd = $row->mi_austrdat;
    $mitgl_sterbd = $row->mi_sterbdat;
    $mitgl_uidaend = $row->mi_uidaend;
    $mitgl_aend = $row->mi_aenddat;
    
    $sql_ei = "SELECT * FROM fh_eigentuemer WHERE ei_name='$mitgl_name' AND ei_vname='$mitgl_vname' ORDER BY `ei_id` ASC";
    $return_ei = SQL_QUERY($db, $sql_ei);

    $neu = array();
    $neu['ei_staat'] = $mitgl_staat;

    $neu['ei_mitglnr'] = $mitgl_nr;
    $neu['ei_bdld'] = $neu['ei_bezirk'] = "";
    $neu['ei_org_typ'] = $mitgl_org_typ;
    $neu['ei_org_name'] = $mitgl_org_name;

    $neu['kont_name'] = "";

    $neu['ei_fwkz'] = "";
    $neu['ei_grdgj'] = "";
    $neu['ei_titel'] = $neu['ei_vname'] = $neu['ei_name'] = $neu['ei_dgr'] = "";
    $neu['ei_adresse'] = "";
    $neu['ei_plz'] = $neu['ei_ort'] = $neu['ei_tel'] = $neu['ei_fax'] = $neu['ei_handy'] = $neu['ei_email'] = "";
    $neu['ei_internet'] = $neu['ei_sterbdat'] = $neu['ei_abgdat'] = $neu['ei_neueigner'] = $neu['ei_wlpriv'] = $neu['ei_vopriv'] = "";
    $neu['ei_wlmus'] = $neu['ei_vomus'] = $neu['ei_wlinv'] = $neu['ei_voinv'] = $neu['ei_voinf'] = $neu['ei_vofo'] = "";
    $neu['ei_voar'] = $neu['ei_drwvs'] = $neu['ei_drneu'] = $neu['ei_uidaend'] = $neu['ei_aenddat'] = "";

    if ($neu['ei_org_typ'] == "Privat") {
        $neu['ei_titel'] = $mitgl_titel;
        $neu['ei_vname'] = $mitgl_vname;
        $neu['ei_name'] = $mitgl_name;

        $neu['ei_plz'] = $mitgl_plz;
        $neu['ei_ort'] = $mitgl_ort;
        $neu['ei_email'] = $mitgl_email;
    }

    $eig_id = 0;
    if (mysqli_num_rows($return_ei) == 0) {
        $neu['ei_id'] = 0;
        require $path2ROOT . 'login/VF_Z_E_Edit_ph1.inc.php'; # #
        #$eig_id = mysqli_insert_id($db);
    }
    $sql = "SELECT * FROM fh_eigentuemer WHERE ei_mitglnr= '$mitgl_nr' ";
    $return_ei = SQL_QUERY($db,$sql);

    $row = mysqli_fetch_object($return_ei);
    
    $eig_id = $row->ei_id;
    $neu = array();
    $neu['be_id'] = "0";
    $neu['be_org_typ'] = $mitgl_org_typ;
    $neu['be_org_name'] = $mitgl_org_name;
    $neu['be_mitglnr'] = $mitgl_nr;
    $neu['be_titel'] = $mitgl_titel;
    $neu['be_titel'] = $mitgl_titel;
    $neu['be_n_titel'] = $mitgl_n_titel;
    $neu['be_anrede'] = "";
    $neu['be_vname'] = $mitgl_vname;
    $neu['be_name'] = $mitgl_name;
    $neu['be_adresse'] = $mitgl_addr;
    $neu['be_staat'] = $mitgl_staat;
    $neu['be_plz'] = $mitgl_plz;
    $neu['be_ort'] = $mitgl_ort;
    $neu['be_telefon'] = $mitgl_tel;
    $neu['be_fax'] = $mitgl_fax;
    $neu['be_email'] = $mitgl_email;
    $neu['eig_id'] = $eig_id;
    $neu['be_aenddat'] = "";
    $neu['be_uidaend'] = $_SESSION['VF_Prim']['p_uid'];

    require $path2ROOT . 'login/VF_Z_B_Edit_ph1.inc.php';
}

$ini_arr = parse_ini_file($path2ROOT.'login/common/config_s.ini',True,INI_SCANNER_NORMAL);
$c_email =$ini_arr['Config']['vema'];
echo "<h2> Werter Kamerad ".$neu['be_titel']." ". $neu['be_anrede']." ".  $neu['be_vname']." ".  $neu['be_name']." ".  $neu['be_n_titel']." </h2>";
echo "<p>Die Anmeldung wurde durchgeführt, ein Zugriff (Benutzer-ID und Passwort) zur Homepage wurde eingerichtet.</p>";
echo "<p>Die Zugriffsdaten werden vom Webmaster verwaltet. E-Mail an: $c_email</p>";

if ($debug) {
    echo "<pre class=debug>VF_M_EZB.inc.php beendet</pre>";
}
?>