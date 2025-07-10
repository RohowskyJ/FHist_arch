<?php
/**
 * Funktionsbibliothek für diverse Zusatzfunktionen für Feuerwehrhistoriker HP.
 *
 * @author  Josef Rohowsky josef@kexi.at
 *
 * Enthält und Unterprogramme für die Auwahl von Namen und Begriffen
 *
 *  - VF_Add_Findbuch   - Suchbegriff Schlagwort hinzufügen
 *  - VF_Add_Namen      - Suchbegriff Namen hinzufügen
 *  - VF_chk_Valid      - Prüfung ob gültige Aufruf, setzten der Zugriffs- Parameter für $module neu 20240120
 *  - VF_Count_add      - Add Record for startng an inernal Sub-Process (as Archive, Suche, Fzg
 *  - VF_Bdld_Displ     - Zeigt den Namen des Bundeslandes für die angegebene Abkürzung
 *  - VF_Displ_Arl      - Anzeige Archivordnung 3+4. Ebene Locale Sachgeb + Subsachgeb
 *  - VF_Displ_Aro      - Anzeige Archivordnug, 1+2. Ebene Generelles Sachgebiet und Sub-Sachgebiet
 *  - VF_Displ_Eig      - Daten zur Anzeige der Eigentümer-Daten, Speichern in SESSION[Eigner [
 *  - VF_Displ_Suchb    - Suchbegriffe für Anzeige einlesen - VF_Displ_Suchb    - Suchbegriffe für Anzeige einlesen
 *  - VF_Displ_Urheb_n  - Urheber Daten in $_Sess[$module]['Fo']['URHEBER'] einlesen  fh_urheber_n/fh_urh_erw_n
 *  - VF_Login          - Login durchführen
 *  - VF_Log_Pw_chg     - Passwortänderung beim Login Daten erfassen
 *  - VF_Log_PW_Upd     - Passworänderung schreiben
 *  - VF_Mail_Set       - gibt die E-Mail Adresse für die Recs aus fh_m_mail zurück neu 20240120
 *  - VF_Multi_Foto     - Anzeige mehrfach - s mit den texten paarweise n Zeile
 *  - VF_M_Foto         - Anzeige mehrfach - s mit den texten paarweise n Zeile  , Upload oder Auswahl aus Foto- Lib
 *  - VF_Sel_Bdld       - Auswahl Bundesland
 *  - VF_Sel_Det        - Detailbeschreibungs Selektion
 *  - VF_Sel_Sammlg     - Sammlungs- Selektion mit select list
 *  - VF_Sel_Staat      - Auswahl Staat
 *  - VF_Sel_Urheber_n    - Auswahl des Urhebers, speicherung Urhebernummer   fh_urh* $_Sess[$module]['Fo']['Urheber_list']
 *  - VF_set_module_p   - setzen der Module- Parameter    neu 20240120
 *  - VF_Show_Eig       - Auslesen ud zurückgeben der Eigner-Daten im Format wie Autocomplete
 *  - VF_tableExist     - test ob eine Tabelle existiert
 *  - VF_upd            - Berechtigungs- Feststellung je *_List Script entsprechend Eigentümer
 *  - VF_Upload_Pic     - Hochladen der Datei mit Umbenennung auf Foto- Video- Format Urh-Datum-Datei.Name
 *  - VF_trans_2_separate - Umlaute eines Strings von UTF-8 oder CP1252 auf gtrennte Schreibweise -> Ü ->UE
 *  - VF_Eig_Ausw       - Autokomplete Auswahl Eigner
 *  - VF_Multi_Dropdown - Multiple Dropdown Auswahl mit bis zu 6 Ebenen, Verwendet für Sammlungsauswahl, AOrd- Auswahl
 *  - VF_Sel_Eigner     - Eigentümer- Auswahl für Berechtigungen (wieder aktiviert)
 *  - VF_Sel_Eign_Urheb - Urheber- Auswahl aus Eigentümer- Datei
 *  fehlende scripts in der liste
 *  - VF_Auto_
 *  - VF_Upload_
 */

if ($debug) {
    echo "L 042: VF_Common_Funcs.inc.php ist geladen. <br/>";
}

/**
 * Konstante für die Module, Stimmt nicht mehr, ? noch Notwendig
 *
 * @var array
 */
const VF_module = array(
    'VF_Prim' => 'Primäres Menu ',
    '1-2_Fzg_Ger' => 'Sub- Menue Fahrzeuge',
    '4_Pers_Ausr' => 'Sub- Menu Persönl. Ausrüstung',
    '5_Archiv' => 'Sub- Menu Archiv',
    '6_inv' => 'Sub- Menu Invntar',
    '7_Öffent' => 'Sub- Menu Öffentlichkeitsarbeit',
    '12_Mitgl' => 'Sub- Menu Mitglieder',
    'V_Zentral' => 'Sub- Menu Zentrale Verwaltung',
    'E_Termin' => 'Extern, Terminkalender',
    'E_Museum' => 'Extern, Museum',
    'E_Markt' => 'Extern, Marktplatz',
    'E_Links' => 'Extern, Bibliotheks-Links',
    'E_Presse' => 'Extern, Pressespiegel',
    'E_Buch' => 'Extern, Buchbesprechung',
    'E_Impress' => 'Extern, Impressum',
    'E_Kontakt' => 'Extern, Kontakt',
    'E_NeuMitgl' => 'Extern, neues Mitglied',
    'E_VBeschr' => 'Extern, Verarbeitungsbeschreibung',
    'E_Referate' => 'Extern, Referate',
    'E_Archive' => 'Extern, Berichte im Archiv',
    'E_Ziele' => 'Extern, Ziele-Details'
);

/**
 * Function um Suchbegriffe in die Findbuch- (Such-) Tabelle einzutragen.
 *
 * Eingügen der Suchbegriffe in die Such- Tabelle
 *
 * @param string $table
 *            Tabellen- Name
 * @param string $keywords
 *            Liste der Suchbegriffe
 * @param string $fld
 *            Feldname
 * @param string $fdid
 *            Datensatz- Nummer
 * @param string $eigner
 *            Eigentümer- Nummer
 *
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 * @global array $db Datenbank Handle
 * @global array $neu Eingelesene Daten Felder
 * @global string $module Modul-Name für $_SESSION[$module] - Parameter
 */
function VF_Add_Findbuch($table, $keywords, $fld, $fdid, $eigner)
{
    global $debug, $db, $neu, $module, $flow_list;
    if ($debug) {
        echo "<pre class=debug>VF_Add_Findbuch L Beg: \$table $table \$fld $fld \$fdid $fdid \$keywords $keywords <pre>";
    }

    flow_add($module, "VF_Comm_Funcs.inc Funct: VF_Add_Findbuch");

    if ($keywords == "") {
        return;
    }
    $find_arr = explode(",", $keywords);
    print_r($find_arr);
    echo "<br> find_arr <br>";
    $arr_cnt = count($find_arr);
    $i = 0;
    while ($find_arr[$i] != "") {
        $find_arr[$i] = trim($find_arr[$i]);
        $sql_fi = "SELECT   * FROM `fh_findbuch` WHERE `fi_table`='$table' AND `fi_fldname`='$fld' AND `fi_fdid`='$fdid' AND `fi_suchbegr`='$find_arr[$i]'";
        $return_fi = SQL_QUERY($db, $sql_fi);

        $recnum = mysqli_num_rows($return_fi);
        if ($recnum == 0) {
            $sql_fb = "INSERT INTO `fh_findbuch` (`fi_table`, `fi_fldname`,
                 `fi_fdid`, `fi_suchbegr`, `fi_suchbegr_all`, `fi_eigner`
                 ) VALUES
         ('$table','$fld','$fdid','$find_arr[$i]', '$keywords', '$eigner'
                 )";

            $return_fb = SQL_QUERY($db, $sql_fb);
        }
        $i++;
        if ($i >= $arr_cnt) {
            break;
        }
    }
}

/**
 * Hinzufügen von Namen in eine Namens-Such - Tablle
 *
 * @param string $table
 *            Tabellen- Name
 * @param string $names
 *            Liste der Namen als Suchbegriffe
 * @param string $feldnam
 *            Feldname
 * @param string $recordnr
 *            Datensatz- Nummer
 * @param string $eigner
 *            Eigentümer- Nummer
 *
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 * @global array $db Datenbank Handle
 * @global array $neu Eingelesene Daten Felder
 * @global string $module Modul-Name für $_SESSION[$module] - Parameter
 *
 */
function VF_Add_Namen($table, $recordnr, $feldnam, $names) # für Referat
{
    global $debug, $db, $neu, $module, $flow_list;
    if ($debug) {
        echo "<pre class=debug>VF_Add_Namen L Beg: \$table $table \$recordnr $recordnr \$feldnam $feldnam \$names $names <pre>";
    }

    flow_add($module, "VF_Comm_Funcs.inc Funct: VF_Add_Namen");

    if ($names == "") {
        return;
    }

    $find_arr = explode(',', $names);

    foreach ($find_arr as $value) {
        $value = trim($value);
        $sql_fi = "SELECT   * FROM `fh_find_namen` WHERE `na_table`='$table' AND `na_fldname`='$feldnam' AND `na_fdid`='$recordnr' AND `na_name`='$value'";

        $return_fi = SQL_QUERY($db, $sql_fi);

        $recnum = mysqli_num_rows($return_fi);
        if ($recnum == 0) {
            $sql_fb = "INSERT INTO `fh_find_namen` (`na_table`, `na_fldname`,
                 `na_fdid`, `na_name`, `na_eigner`
                 ) VALUES
         ('$table','$feldnam','$recordnr','$value','" . $_SESSION['Eigner']['eig_eigner'] . "'
                 )";
            $return_fb = SQL_QUERY($db, $sql_fb);
        }
    }
}

// Ende von function VF_Add_Namen

/**
 * Überprüfung des Aufrufs- Parameters - wenn nicht identich -> Abbruch
 *
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 * @global array $db Datenbank Handle
 */
function VF_chk_valid()
// --------------------------------------------------------------------------------
{
    global $debug, $db,$module ;

    flow_add($module, "VF_Comm_Funcs.inc Funct: VF_Chk_valid");

    if (isset($_GET['SK'])) {
        if ($_GET['SK'] != $_SESSION['VF_Prim']['SK']) {
            die("Falscher Aufruf. Programm wird abgebrochen.");
        }
    }
}

# Ende FunktionVF_chk_valid

/**
 * Schreiben von Zugriffs Daten je $module und Benutzer- ID und Aufruf (Referrer), Adresse
 *
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 * @global array $db Datenbank Handle
 * @global string $module Modul-Name für $_SESSION[$module] - Parameter
 */
function VF_Count_add()
{
    global $debug, $db, $module, $flow_list;
    # Aufruf- Counter

    if ($_SERVER['SERVER_NAME'] == 'localhost') {
        return;
    }

    flow_add($module, "VF_Comm_Funcs.inc Funct: VF_Count_Add");

    if ($debug) {
        echo "<pre class=debug> Count add L Beg: t<pre>";
    }

    $p_uid = $_SESSION['VF_Prim']['p_uid'];

    $date = date('Y-m-d');

    $sql = "SELECT * FROM fh_count WHERE fc_uid='$p_uid' AND fc_datum= '$date' ";

    $return = SQL_QUERY($db, $sql);

    if ($return) {

        $num_rows = mysqli_num_rows($return);
        $fc_referrer = explode("?", $_SERVER["HTTP_REFERER"]);
        $fc_refer = $fc_referrer[0];
        $fc_remaddr = $_SERVER["REMOTE_ADDR"];
        $fc_uagent = $_SERVER["HTTP_USER_AGENT"];

        if ($num_rows > 0) {
            while ($row = mysqli_fetch_object($return)) {

                if ($row->fc_module == $module) {
                    continue;
                } else {
                    $sql = "INSERT INTO fh_count (
                          fc_datum,fc_uid,fc_module,fc_referrer,fc_remaddr,fc_uagent
                         ) VALUE (
                         '$date','$p_uid','$module','$fc_refer','$fc_remaddr','$fc_uagent'
                         )";

                    $return = SQL_QUERY($db, $sql);

                    break;
                }
            }
        } else {
            $sql = "INSERT INTO fh_count (
               fc_datum,fc_uid,fc_module,fc_referrer,fc_remaddr,fc_uagent
              ) VALUE (
               '$date','$p_uid','$module','$fc_refer','$fc_remaddr','$fc_uagent'
              )";

            $return = SQL_QUERY($db, $sql);
        }
    } else {
        $sql = "INSERT INTO fh_count (
               fc_datum,fc_uid,fc_module,fc_referrer,fc_remaddr,fc_uagent
              ) VALUE (
               '$date','$p_uid','$module','$fc_refer','$fc_remaddr','$fc_uagent'
              )";

        $return = SQL_QUERY($db, $sql);
    }
}

// Ende von function VF_Count_add

/**
 * Function um Bundesland auszuwählen
 *
 *
 * @param string $staat
 *            Staats- Abkürzung (AT, DE, ...)
 * @param string $bundesland
 *            Abkürzung des Bundeslandes m Staat
 *
 * @return string $menuepunkte_bl Name des gesuchten Bundeslandes
 *
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 *
 */
function VF_Bdld_Displ($staat, # Fortl, Nummer des Benutzere
    $bundesland) # für Referat
// --------------------------------------------------------------------------------
{
    global $debug, $db, $flow_list,$module;

    /**
     * ***********************************************
     *
     * ***********************************************
     */
    if ($debug) {
        echo "<pre class=debug>F Bdld_Displ L Beg: \$staat $staat \$bundesland $bundesland<pre>";
    }

    flow_add($module, "VF_Comm_Funcs.inc Funct: VF_Bdld_Displ");

    $sql = "SELECT * FROM fh_bundesld WHERE bl_stabkz='$staat' and bl_blabkz = '$bundesland' ORDER BY bl_id ASC";
    $return_bl = mysqli_query($db, $sql) or die("Datenbankabfrage gescheitert. " . mysqli_error($db));
    while ($row = mysqli_fetch_object($return_bl)) {
        if ($row->bl_stabkz == $staat && $row->bl_blabkz == $bundesland) {
            $menuepunkte_bl = $row->bl_name;
            break;
        }
    }
    mysqli_free_result($return_bl);
    return ($menuepunkte_bl);
    if ($debug) {
        echo "<pre class=debug>F Bdld_Displ L End: \$staat $staat \$bundesland $bundesland<pre>";
    }
}

// Ende von function

/**
 * Function um einen Beschaffungszeitraum für Fahrzeuge auszuwählen
 *
 *
 *
 * @param string $fz_aera
 *            Abkürzung der Aera
 * @param string $sub_funct
 *            -> hier nicht benutzt
 *
 * @return string $text Langext der Aera
 *
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 * @global array $db Datenbank Handle
 */
function VF_Displ_Aera($fz_area, $sub_funct)
// --------------------------------------------------------------------------------
{
    global $debug, $db, $flow_list,$module;

    flow_add($module, "VF_Comm_Funcs.inc Funct: VF_Displ_Aera");

    if ($debug) {
        echo "<pre class=debug>Displ_Aera L Beg: \$mit_name $mit_name \$sub_funct $sub_funct<pre>";
    }

    $sql_fa = "SELECT * FROM `fz_aera` WHERE `ar_id`='$fz_aera' ";

    $return_fa = SQL_QUERY($db, $sql_fa);

    while ($row = mysqli_fetch_object($return_fa)) {
        $text = $row->ar_text;
    }

    mysqli_free_result($return_fa);

    if ($debug) {
        echo "<pre class=debug>F Security_sel L End: \$p_uid $p_uid \$pgm_id $pgm_id<pre>";
    }
    return $text;
}

// Ende von function VF_Displ_Aera

/**
 * Anzeige der Archivordnung, Mandanten definierte Ebene 3 und 4, Eigentümer- Erweiterungen / Definitionen
 *
 * @param string $sg
 *            1. Ebene (ÖBFV)
 * @param string $ssg
 *            2. Ebene (ÖBFV)
 * @param string $lcsg
 *            3. Ebene (Mandanten Erweiterung)
 * @param string $lcssg
 *            4. Ebene (Mandanten Erweiterung)
 * @return string $string_arl M_Sachgebiet.M_Sub- Sachgebiet Sachgebietsname Suchbegriff
 *
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 * @global array $db Datenbank Handle
 * @global array $neu Eingelesene Daten Felder
 * @global string $module Modul-Name für $_SESSION[$module] - Parameter
 */
function VF_Displ_Arl($sg, $ssg, $lcsg, $lcssg)
{
    global $debug, $db, $neu, $module, $flow_list;

    flow_add($module, "VF_Comm_Funcs.inc Funct: VF_Displ_Arl");

    if ($debug) {
        echo "<pre class=debug>VF_Disp_Arl L Beg: \$lcsg $lcsg <pre>";
    }
    $string_arl = "";

    // arl_displ.php Anzeige des Archiv Sachgebietes
    // offenen Funktionen:

    $table = "ar_ord_local"; # . $_SESSION['Eigner']['eig_eigner'];
    // `al_eigner`='".$_SESSION[$module]['eignr']."' AND
    $lcssg_val = "";
    if ($lcssg != "" && $lcssg != "0") {
        $lcssg_val =  " AND `al_lcssg` = '$lcssg' ";
    }
    $sql_al = "SELECT * FROM $table WHERE   `al_sg`='$sg.$ssg' AND `al_lcsg` = '$lcsg' $lcssg_val ORDER BY al_sg ASC";
    $return_al = mysqli_query($db, $sql_al); // or die("Datenbankabfrage gescheitert. ".mysqli_error($db));
    # print_r($return_al);echo "<br> L 211: return \$sql_al $sql_al <br>";
    if ($return_al) {
        while ($row = mysqli_fetch_object($return_al)) {
            $string_arl = "$row->al_lcsg.$row->al_lcssg $row->al_bezeich";
        }
        mysqli_free_result($return_al);
    }

    return $string_arl;
}

// Ende von function VF_Displ_Arl

/**
 * Anzeige des Textes aus der Archivordnung, Ebbene 1 und 2 (ÖBFV, bzw.
 * LFKDO definiert)
 *
 * @param string $sg
 *            Sachgebiets- Nummer
 * @param string $ssg
 *            Sub-Sachgebiets- Nummer
 * @return string $string_aro Sachgebiet.Sub- Sachgebiet Sachgebietsname Suchbegriff
 *
 *
 */
function VF_Displ_Aro($sg, $ssg)
// --------------------------------------------------------------------------
{
    global $debug, $db, $neu, $module, $flow_list;

    flow_add($module, "VF_Comm_Funcs.inc Funct: VF_Displ_Aro");

    if ($debug) {
        echo "<pre class=debug>VF_Displ_Aro L Beg: \$sg $sg <pre>";
    }
    // aro_displ.php Anzeige des Archiv Sachgebietes
    // offenen Funktionen:
    $string_aro = "";
    $sql_ao = "SELECT * FROM ar_chivord WHERE ar_sg='$sg' AND ar_sub_sg = '$ssg' ORDER BY ar_sg ASC";
    $return_ao = mysqli_query($db, $sql_ao) or die("Datenbankabfrage gescheitert. " . mysqli_error($db));
    while ($row = mysqli_fetch_object($return_ao)) {
        $string_aro = "$row->ar_sg.$row->ar_sub_sg $row->ar_sgname;";
    }
    mysqli_free_result($return_ao);

    return $string_aro;
}

// Ende von function VF_Displ_Aro

/**
 * Anlegen eines Arrays mit den Daten des ausgesuchten Eigentümers
 * $_SESSION['Eigner'][felder]
 *
 * @param string $eigentmr
 *            Eigentümer-Nummer
 *
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 * @global array $db Datenbank Handle
 * @global string $module Modul-Name für $_SESSION[$module] - Parameter
 */
function VF_Displ_Eig($eigentmr)
{
    global $debug, $db, $module, $flow_list;

    flow_add($module, "VF_Comm_Funcs.inc Funct: VF_Displ_Eig");

    $sql = "SELECT * FROM `fh_eigentuemer` WHERE `ei_id` = '$eigentmr' ";

    if (isset($flow_list)) { # ->neue Libs
        $return_ei = SQL_QUERY($db, $sql);
    } else {
        $return_ei = SQL_QUERY($db, $sql);
    }
    while ($row = mysqli_fetch_object($return_ei)) {
        $leihname = "";

        switch ($row->ei_org_typ) {
            case "Privat":
                break;
            case "Fa.":
                $leihname = "Fa. $row->ei_org_name  ";
                break;
            case "FM":
                $leihname = "Feuerwehrmuseum $row->ei_org_name  ";
                break;
            case "FF":
                $leihname = "Feuerwehr $row->ei_org_name   ";
                break;
            case "V":
                $leihname = "Verein $row->ei_org_name   ";
                break;
            case "AFKDO":
                $leihname = "AFKDO $row->ei_org_name ";
                break;
            case "BFKDO":
                $leihname = "BFKDO $row->ei_org_name  ";
                break;
            case "LFKDO":
                $leihname = "LFKDO $row->ei_org_name ";
                break;
            case "GV":
                $leihname = "$row->ei_org_name  ";
                break;
        }

        $_SESSION['Eigner']['eig_eigner'] = $eigentmr;
        $_SESSION['Eigner']['eig_org'] = $leihname;
        $_SESSION['Eigner']['eig_name'] = $leihname;
        $_SESSION['Eigner']['eig_verant'] = "$row->ei_titel $row->ei_vname $row->ei_name $row->ei_dgr ";

        if ($row->ei_org_typ == "Privat") {
            $_SESSION['Eigner']['eig_vopriv'] = $row->ei_vopriv;
            $_SESSION['Eigner']['eig_verant'] = "";
            $_SESSION['Eigner']['eig_staat'] = "";
            $_SESSION['Eigner']['eig_adresse'] = "";
            $_SESSION['Eigner']['eig_ort'] = "";
            $_SESSION['Eigner']['eig_name'] = "$row->ei_titel $row->ei_vname $row->ei_name $row->ei_dgr ";
            $_SESSION['Eigner']['eig_urhname'] = "$row->ei_vname $row->ei_name ";
        } else {
            $_SESSION['Eigner']['eig_vopriv'] = $row->ei_vopriv;
            $_SESSION['Eigner']['eig_verant'] = "$row->ei_titel $row->ei_vname $row->ei_name $row->ei_dgr ";
            $_SESSION['Eigner']['eig_staat'] = $row->ei_staat;
            $_SESSION['Eigner']['eig_adresse'] = $row->ei_adresse;
            $_SESSION['Eigner']['eig_ort'] = $row->ei_plz . " " . $row->ei_ort;
            $_SESSION['Eigner']['eig_urhname'] = $leihname;
        }
    }
    mysqli_free_result($return_ei);
}

// Ende von function VF_Displ_Eig

/**
 * Anzeige der Suchbegriffe, Daten werden in die Globale Variable geschrieben
 *
 * Ausgabe der zugehörigen Texte in die globalen Variablen $o_suchb* (* = 1-6)
 *
 *
 * @param string $invsb*
 *            (* = 1-6) die 6 Suchbegriffs-Begriffe (1 - Hauptbegriff, 6 - Unterste Ebene, nicht alle notwendig/Definiert)
 *
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 * @global array $db Datenbank Handle
 * @global string $o_suchb1 - $o_suchb6
 */
function VF_Displ_Suchb($invsb1, $invsb2, $invsb3, $invsb4, $invsb5, $invsb6)
// --------------------------------------------------------------------------------
{
    global $debug, $db, $o_suchb1, $o_suchb2, $o_suchb3, $o_suchb4, $o_suchb5, $o_suchb6, $flow_list,$module;

    flow_add($module, "VF_Comm_Funcs.inc Funct: VF_Displ_Suchb");

    if (empty($invsb1)) {
        return;
        $select = "";
    } else {
        $select = "WHERE `sb_l1sb`='$invsb1' ";
    }
    $sql_sb = "SELECT * FROM `sb_egriffe` $select ";

    $return_sb = SQL_QUERY($db, $sql_sb);

    while ($row = mysqli_fetch_object($return_sb)) {
        if ($invsb1 == $row->sb_l1sb && empty($row->sb_l2sb)) {
            $o_suchb1 = "$row->sb_l1sb $row->sb_name";
        }
        if ($invsb1 == $row->sb_l1sb && $invsb2 == $row->sb_l2sb && ! empty($row->sb_l2sb) && empty($row->sb_l3sb)) {
            $o_suchb2 = "$row->sb_l2sb $row->sb_name";
        }
        if ($invsb1 == $row->sb_l1sb && $invsb2 == $row->sb_l2sb && $invsb3 == $row->sb_l3sb && ! empty($row->sb_l3sb) && empty($row->sb_l4sb)) {
            $o_suchb3 = "$row->sb_l3sb $row->sb_name";
        }
        if ($invsb1 == $row->sb_l1sb && $invsb2 == $row->sb_l2sb && $invsb3 == $row->sb_l3sb && $invsb4 == $row->sb_l4sb && ! empty($row->sb_l4sb) && empty($row->sb_l5sb)) {
            $o_suchb4 = "$row->sb_l4sb $row->sb_name";
        }
        if ($invsb1 == $row->sb_l1sb && $invsb2 == $row->sb_l2sb && $invsb3 == $row->sb_l3sb && $invsb4 == $row->sb_l4sb && $invsb5 == $row->sb_l5sb && ! empty($row->sb_l5sb) && empty($row->sb_l6sb)) {
            $o_suchb5 = "$row->sb_l5sb $row->sb_name";
        }
        if ($invsb1 == $row->sb_l1sb && $invsb2 == $row->sb_l2sb && $invsb3 == $row->sb_l3sb && $invsb4 == $row->sb_l4sb && $invsb5 == $row->sb_l5sb && $invsb6 == $row->sb_l6sb && ! empty($row->sb_l6sb)) {
            $o_suchb6 = "$row->sb_l6sb $row->sb_name";
        }
    }
}

# Ende function DisplSuch

/**
 * Auslesen der Urheber- Daten, Ausgabe in Array,
 *
 * Ausgabe array $_SESSION[$module]['URHEBER'][Felder] Besitzerdaten
 *
 * @param string $urhbernr
 *            Nummer des Urhebers (= Mandanten- Eigentümernummer)
 * @param string $typ
 *            F: Fotos,V: Videos, A: Audios, Default = F - Fotos
 *
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 * @global array $db Datenbank Handle
 * @global string $module Modul-Name für $_SESSION[$module] - Parameter
 */
function VF_Displ_Urheb_n($urhebernr, $typ = "F")
// --------------------------------------------------------------------------------
{
    global $debug, $db, $module, $fm_typ, $flow_list;

    flow_add($module, "VF_Comm_Funcs.inc.php Funct: VF_Displ_Urheb_n");

    if (!isset($fm_typ)) {
        $fm_typ = $typ;
    }
    if ($debug) {
        echo "<pre class=debug>VF Displ_Urheb_n L 687 Beg: \$urhebernr $urhebernr fm_typ $fm_typ <pre>";
    }

    $sql = "SELECT * FROM `fh_eigentuemer` WHERE `ei_id`='$urhebernr' ";

    $return_ur = SQL_QUERY($db, $sql);

    while ($row = mysqli_fetch_object($return_ur)) {

        $_SESSION[$module]['URHEBER']['BE']['ei_id'] = $row->ei_id;

        if ($row->ei_org_typ == "") {
            $urheber = $row->name ." .$row->ei_vname";
        } else {
            $urheber = $row->ei_org_typ ." ". $row->ei_org_name;
        }
        $_SESSION[$module]['URHEBER']['BE']['ei_urheber'] = $urheber;

        $_SESSION[$module]['URHEBER']['BE']['ei_urh_kurzz'] = $row->ei_urh_kurzz;
        $_SESSION[$module]['URHEBER']['BE']['ei_media'] = $row->ei_media;

        $sql_urh_det = "SELECT * from fh_eign_urh WHERE fs_eigner='$row->ei_id' AND fs_typ = '$fm_typ' ";
        $return_urh_det = SQL_QUERY($db, $sql_urh_det);
        $num_rec = mysqli_num_rows($return_urh_det);
        if ($num_rec > 0) {
            # $num_r_u_d = mysqli_num_rows($return_urh_det);
            $_SESSION[$module]['URHEBER']['BE']['urh_abk'] = array();
            while ($row_urh_d = mysqli_fetch_object($return_urh_det)) {
                $_SESSION[$module]['URHEBER']['BE']['urh_abk'][$row_urh_d->fs_urh_nr][$row_urh_d->fs_urh_kurzz] = $row_urh_d->fs_fotograf;

            }

            break;
        }


    }

    mysqli_free_result($return_ur);
}

// Ende von function VF_Displ_Urheb_n

/**
 * Feststellen ob die Login Parameter UID und PW korrekt eingegeben wurden
 * wenn Nein: Fehler
 * wenn OK, Einlesen der Benutzer- Werte in Array
 * $_SESSION['VF_Prim'][Felder]; Brechtigungen und Zuständigkeiten des Benutzers
 *
 * @param
 *            string
 *
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 * @global array $db Datenbank Handle
 * @global array $neu Eingelesene Daten Felder
 * @global string $module Modul-Name für $_SESSION[$module] - Parameter
 */
function VF_Login($email, $Pwd)
{
    global $debug, $db, $neu, $module, $flow_list;

    flow_add($module, "VF_Comm_Funcs.inc Funct: VF_Login");

    if ($debug) {
        echo "<pre class=debug>";
        echo "VF_Login Input : email $email pwd $Pwd  ";

        echo "</pre>";
    }
    $sql_b = "SELECT * FROM fh_benutzer WHERE be_email='$email' ORDER by be_id ASC ";

    $return_b = SQL_QUERY($db, $sql_b);

    # print_r($return_b);echo "<br>L 452 return_b sql_b $sql_b <br>";
    $num_rows = mysqli_num_rows($return_b);
    if ($num_rows > 0) {
        $ben_id = "";
        $p_id = "";
        $zu_id = "";
        while ($row_b = mysqli_fetch_object($return_b)) {
            if ($row_b->be_email == $email) {
                $_SESSION['VF_Prim']['p_uid'] = $ben_id = $row_b->be_id;
            }
            $_SESSION['VF_Prim']['benutzer'] = $row_b->be_vname . " " . $row_b->be_name;
        }
        $pwenc = crypt($Pwd, '$1$banane1a$');
        # $sql_z = "SELECT * FROM fh_zugriffe WHERE zu_id='$ben_id' AND zu_pw_enc='$pwenc' ORDER by zu_id ASC ";
        $sql_z = "SELECT * FROM fh_zugriffe_n WHERE zu_id='$ben_id'  ORDER by zu_id ASC ";

        $return_z = SQL_QUERY($db, $sql_z);

        if ($debug) {
            echo "L 713: vor einlesen zug <br>";
        }
        while ($row_z = mysqli_fetch_object($return_z)) {
            if ($debug) {
                echo "L 717: nach einlesen zug : $row_z->zu_pw_enc \$pwenc $pwenc<br>";
            }

            if ($row_z->zu_id == $ben_id && hash_equals($row_z->zu_pw_enc, $pwenc)) {
                $p_uid = $zu_id = $row_z->zu_id;
                $_SESSION['VF_Prim']['p_uid'] = $row_z->zu_id;

                $_SESSION['VF_Prim']['zu_eign'][1] = $row_z->zu_eignr_1;
                $_SESSION['VF_Prim']['zu_eign'][2] = $row_z->zu_eignr_2;
                $_SESSION['VF_Prim']['zu_eign'][3] = $row_z->zu_eignr_3;
                $_SESSION['VF_Prim']['zu_eign'][4] = $row_z->zu_eignr_4;
                $_SESSION['VF_Prim']['zu_eign'][5] = $row_z->zu_eignr_5;

                $_SESSION['VF_Prim']['SUC'] = $row_z->zu_SUC;
                $_SESSION['VF_Prim']['F_G'] = $row_z->zu_F_G;
                $_SESSION['VF_Prim']['F_M'] = $row_z->zu_F_M;
                $_SESSION['VF_Prim']['PSA'] = $row_z->zu_PSA;
                $_SESSION['VF_Prim']['ARC'] = $row_z->zu_ARC;
                $_SESSION['VF_Prim']['INV'] = $row_z->zu_INV;
                $_SESSION['VF_Prim']['OEF'] = $row_z->zu_OEF;
                $_SESSION['VF_Prim']['ADM'] = $row_z->zu_ADM;
                $_SESSION['VF_Prim']['MVW'] = $row_z->zu_MVW;
                $_SESSION['VF_Prim']['SK'] = md5($_SESSION['VF_Prim']['p_uid'] - date('Ymd'));
            } else {
                $_SESSION['VF_Prim']['p_uid'] = 'NoGood';
            }
        }
    } else {
        $_SESSION['VF_Prim']['p_uid'] = 'NoGood';
    }
}

// Ende von Funktion VF_Login

/**
 * Formular- Teil für Passwort- Änderung
 *
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 * @global array $db Datenbank Handle
 * @global string $module Modul-Name für $_SESSION[$module] - Parameter
 */
function VF_Log_Pw_Chg()
{
    global $debug, $db, $module, $flow_list;

    flow_add($module, "VF_Comm_Funcs.inc Funct: VF_Log_Pw_Chg");

    echo "<div class='w3-container'><fieldset>";
    echo "<strong><font size=\"+2\">Passwortänderung:</font></strong><br/>";
    echo "<strong>Das Password zweimal identisch eingeben</strong><br/>";
    echo '<div class="fElement">';
    echo '<div class="label">Password: </div>';
    echo "<input name=\"password\" value=\"\"  size=\"35\" type=\"password\"  autofocus=\"autofocus\" required=\"required\">";
    echo '</div>';
    echo '<div class="fElement">';
    echo '<div class="label">nochmals Password: </div>';
    echo "<input name=\"password1\" value=\"\"  size=\"35\" type=\"password\" required=\"required\"><br>";
}

// Ende der Funktione VF_Log_Pw_Chg

/**
 * Durchführung der PW Änderung
 *
 * @param string $p1
 *            neues Passwort, wird verschlüsselt (Hash)
 *
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 * @global array $db Datenbank Handle
 * @global string $module Modul-Name für $_SESSION[$module] - Parameter
 */
function VF_Log_Pw_Upd($p1)
{
    global $debug, $db, $module, $flow_list;

    flow_add($module, "VF_Comm_Funcs.inc Funct: VF_Log_Pw_Upd");

    $pw_chg_e = crypt($p1, '$1$banane1a$');

    $sql_zu = "UPDATE fh_zugriffe_n SET
               `zu_pw_enc`='$pw_chg_e',
               `zu_uidaend`='" . $_SESSION[$module]['p_uid'] . "',`zu_aenddat`=NOW()
               WHERE `zu_id`='" . $_SESSION[$module]['p_uid'] . "' LIMIT 1";

    $return_zu = SQL_QUERY($db, $sql_zu);

    echo "<p class='error'>Das Passwort wurde geändert.</p>";
}

// Ende der Funktion VF_Log_PW_Upd

/**
 * Admin- Verständigungs- Mails je Gruppe bereitstellen
 *
 * Die -E-Mail- Adressen der dafür bestimmten Administratoren werden als Komma getrennter String bereitgestellt.
 *
 *
 * @param string $mail_grp
 *            Administrator- Gruppe
 * @return string Mail-Adresse(n)
 *
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 * @global array $db Datenbank Handle
 */
function VF_Mail_Set($mail_grp) # Mail- Gruppe

# für Zusatzauswahlen
# Admin-Emails je Gruppeauswählen
// --------------------------------------------------------------------------------
{
    global $debug, $db, $flow_list,$module;

    flow_add($module, "VF_Comm_Funcs.inc Funct: VF_Mail_Set");

    if ($debug) {
        echo "<pre class=debug>F Staat Ausw L Beg: mail_grp \$mail_grp <pre>";
    }

    // einlesen fh_m_mail mit em_mail_grp == $mail_grp, dann E-Mail-Adresse aus fh_mitglieder mit em_mitgl_nr einlesen und in liste ausgeben "mail1, mail2, ..."
    $sql_mail = "SELECT * from fh_m_mail WHERE em_mail_grp = '$mail_grp' ";

    $adr_list = "";

    $return_mail = SQL_QUERY($db, $sql_mail);

    # print_r($return); echo "<br>\$sql $sql <br>";

    while ($row = mysqli_fetch_assoc($return_mail)) {
        $MitglNr = $row['em_mitgl_nr'];
        $sql_m = "SELECT * from fh_mitglieder WHERE mi_id = '$MitglNr' ";

        $return_m = SQL_QUERY($db, $sql_m);

        if ($return_m) {
            while ($row_m = mysqli_fetch_assoc($return_m)) {
                if ($row_m['mi_email'] != "") {
                    if ($adr_list == "") {
                        $adr_list = $row_m['mi_email'];
                    } else {
                        $adr_list .= ", " . $row_m['mi_email'];
                    }
                }
            }
        }
    }

    if ($adr_list == "") {
        $adr_list = " service@feuerwehrhistoriker.at ";
    }

    # print_r($adr_list);
    return ($adr_list);
}

// Ende von function VF_Mail_set

/**
 * Formular- Teil zum hochladen von mehrfach-Dateien (fotos, Dokumente, ..)
 *
 * @param array $Picts
 *            Daten zum Hochladen
 * @param string $sub_functs
 *            Steuerung für Sub-Funktionen
 * @return
 *
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 * @global array $db Datenbank Handle
 * @global array $neu Eingelesene Daten Felder
 * @global array $Tabellen_Spalten_COMMENT Global Array (Schlüssel: Spaltenname) mit Texten zu den Spalten
 */
function VF_Multi_Foto(array $Picts, $sub_funct = '')
// --------------------------------------------------------------------------------
{
    global $debug, $db, $neu, $module, $pict_path, $Tabellen_Spalten_COMMENT, $flow_list;

    flow_add($module, "VF_Comm_Funcs.inc.php Funct: VF_Multi_Foto");

    if ($debug) {
        echo "<pre class=debug>VF_Mult_ L Beg: \$Picts ";
        var_dump($Picts);
        echo "<pre>";
    }

    $pic_cnt = count($Picts);

    # echo "<tr><td colspan='2'>";
    echo "<div class='w3-container' max-width='100%' margin='5px '>";

    foreach ($Picts as $key => $value) {
        error_log($value);
        $p_a = explode("|", $value);

        echo "<div class='w3-half'><fieldset>";
        echo "<div style='float:left;'>";
        if ($p_a[0] != "") {
            if (isset($Tabellen_Spalten_COMMENT[$p_a[0]])) {
                echo $Tabellen_Spalten_COMMENT[$p_a[0]];
            } else {
                echo "<b>$p_a[0]</b> ";
            }
            echo "  <input class='w3-input' type='text' name='$p_a[0]' value='" . $neu[$p_a[0]] . "' size='50'> <br/>";
        }
        if ($p_a[1] != "") {
            if (isset($Tabellen_Spalten_COMMENT[$p_a[1]])) {
                echo $Tabellen_Spalten_COMMENT[$p_a[1]];
            } else {
                echo "$p_a[1]";
            }
            echo "  <input class='w3-input' type='text' name='$p_a[1]' value='" . $neu[$p_a[1]] . "'> <br/>";
        }
        if ($p_a[2] != "") {
            if (isset($Tabellen_Spalten_COMMENT[$p_a[2]])) {
                echo $Tabellen_Spalten_COMMENT[$p_a[2]];
            } else {
                echo $p_a[2];
            }
            echo "  <textarea class='w3-input' rows='5' cols='50' name='$p_a[2]' >" . $neu[$p_a[2]] . "</textarea> ";
        }

        echo '<input type="hidden" name="MAX_FILE_SIZE" value="4000000" >';
        $FeldName = $p_a[3];

        echo "<input type='hidden' id='f_Dat_$key' name='$FeldName' value='$neu[$FeldName]' >";

        if (isset($Tabellen_Spalten_COMMENT[$FeldName])) {
            if ($_SESSION['VF_Prim']['p_uid'] != 999999999) {
                echo "  <span class='info'>$Tabellen_Spalten_COMMENT[$FeldName] <b>$FeldName</b> Bild hochladen </span>";
                echo "<input type='file'   id='f_Doc_$key' name='f_Name_$key' accept=VF_zuldateitypen />";
            }
        } else {
            echo "  <span class='info'><b>$FeldName</b> Bild hochladen </span>";
            echo "<input type='file'   id='f_Dat_$key' name='f_Name_$key' accept=VF_zuldateitypen />";
        }
        error_log($pict_path);
        if ($neu[$p_a[3]] != "") {
            $p = $pict_path . $neu[$p_a[3]];
            error_log($p);
            echo "</div><div style='float:right;'>";
            if (stripos($neu[$p_a[3]], ".pdf")) {
                echo "<a href='$p' target='Bild $key' > Dokument</a></div>";
            } else {
                echo "<a href='$p' target='Bild $key' > <img src='$p' alter='$p' width='200px'>  Groß  </a></div>";
            }

        }

        echo "</fieldset></div>";
    }
    echo "</div>";
    echo "</div>";
    # echo "</td></tr>";

    if ($debug) {
        echo "<pre class=debug>VF_Mult_ L End: <pre>";
    }
    return;
}

/**
 * Formular- Teil zum hochladen von mehrfach-Dateien (fotos, Dokumente, ..) Modifizierte Vwersion
 *
 * @param array $Picts
 *            Daten zum Hochladen
 * @param string $sub_functs
 *            Steuerung für Sub-Funktionen
 * @return
 *
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 * @global array $db Datenbank Handle
 * @global array $neu Eingelesene Daten Felder
 * @global array $Tabellen_Spalten_COMMENT Global Array (Schlüssel: Spaltenname) mit Texten zu den Spalten
 */
function VF_M_Foto()
// --------------------------------------------------------------------------------
{
    global $debug, $db, $neu, $module, $pict_path, $Tabellen_Spalten_COMMENT, $flow_list,$hide_area, $path2ROOT, $button_clicked_flag,$urheber,$verzeichn,$suff;

    flow_add($module, "VF_Comm_Funcs.inc.php Funct: VF_M_Foto");

    if (!isset($urheber)) {
        $urheber = $_SESSION[$module]['Eigner']['eig_eigner'];
    }
    $verzeichnis = "";
    $suffix      = "";
    $foDsn       = "";
    $fo_org = 'H';

    # $_SESSION[$module]['Pct_Arr'][] = array("k1" => 'fz_b_1_komm', 'b1' => 'fz_bild_1', 'rb1' => '', 'up_err1' => '');

    if ($debug) {
        echo "<pre class=debug>VF_M_Foto L Beg: \$Picts ";
        var_dump($_SESSION[$module]['Pct_Arr']);
        echo "<pre>";
    }

    $pic_cnt = count($_SESSION[$module]['Pct_Arr']);

    # echo "<tr><td colspan='2'>";
    echo "<div class='w3-container' max-width='100%' margin='5px '>";

    var_dump($_SESSION[$module]['Pct_Arr']);

    foreach ($_SESSION[$module]['Pct_Arr'] as $key => $p_a) { # => $value) {
        var_dump($p_a);
        # $p_a = explode("|", $value);


        #var_dump($p_a);echo "L 01025 hide_area $hide_area <br>";

        #echo $neu[$p_a[2]]. " ".$p_a[2] . " " . $neu[$p_a[3]]." ". $p_a[3] ."<br>";

        if ($hide_area == 0 || ($hide_area == 1 && ($neu[$p_a['ko']] != '' || $neu[$p_a['bi']] != ''))) {

            # echo "Bild- Box $key wird angezeigt <br>";

            echo "<div class='w3-half'><fieldset>";
            echo "<div style='float:left;'>";

            if ($p_a['ko'] != "") {
                if (isset($Tabellen_Spalten_COMMENT[$p_a['ko']])) {
                    echo $Tabellen_Spalten_COMMENT[$p_a['ko']];
                } else {
                    echo $p_a['ko'];
                }
                echo "<textarea class='w3-input' rows='7' cols='25' name='".$p_a['ko']."' >" . $neu[$p_a['ko']] . "</textarea> ";
            }
            if ($neu[$p_a['bi']] != "") {
                $fo = $neu[$p_a['bi']];

                $fo_arr = explode("-", $neu[$p_a['bi']]);
                $cnt_fo = count($fo_arr);

                if ($cnt_fo >= 3) {   // URH-Verz- Struktur de dsn
                    $urh = $fo_arr[0]."/";
                    $verz = $fo_arr[1]."/";
                    if ($cnt_fo > 3) {
                        if (isset($fo_arr[3])) {
                            $s_verz = $fo_arr[3]."/";
                        }
                    }
                    $p = $path2ROOT ."login/AOrd_Verz/$urh/09/06/".$verz.$neu[$p_a['bi']] ;

                    if (!is_file($p)) {
                        $p = $pict_path . $neu[$p_a['bi']];
                    }
                } else {
                    $p = $pict_path . $neu[$p_a['bi']];
                }

                echo "</div><div style='float:right;'>";

                $f_arr = pathinfo($neu[$p_a['bi']]);
                if ($f_arr['extension'] == "pdf") {
                    echo "<a href='$p' target='Bild $key' > Dokument</a></div>";
                } else {
                    echo "<a href='$p' target='Bild $key' > <img src='$p' alter='$p' width='200px'></a></div>";
                    echo $neu[$p_a['bi']];
                }

            }

            # $show_upload = ($hide_area == 0) || ($hide_area == 1 && $button_clicked_flag);

            $show_upload = true;
            'display:' . ($show_upload ? 'block' : 'none') . ';">';

            echo "<fieldset style='margin:10px; padding:10px; border:1px solid #ccc;'>";
            echo "<legend>Foto $key</legend>";

            // Datei-Input
            echo "<input type='file' id='f_Doc_$key' name='f_Doc_Name_$key' /><br/><br/>";

            # echo "<input type='file' id='$FeldName'  name='$FeldName' onchange='uploadImage(\"$FeldName\", $key)' accept='image/*' /><br/><br/>";
            // Verste process
            echo "<input type='hidden' id='f_Doc_$key' name='f_Doc_Name_$key' value='".$neu[$p_a['bi']]."'/>";
            echo "</fieldset>";

            echo '</div>';

        }

        echo "</fieldset></div>";
    }
    echo "</div>";
    echo "</div>";
    # echo "</td></tr>";

    if ($debug) {
        echo "<pre class=debug>VF_Mult_ L End: <pre>";
    }
    return;
}

/**
 * Bundesland- Liste eines Staates
 *
 * @param string $land
 *            Bundesland- Abkürzugn+ (BGL, STMK, ..)
 * @param string $sub_funct
 *            0 .. Neueingabe, 8 .. Keine Auswahl als default
 * @param string $stabkz
 *            Landes- (Staats-) Abkürzung (AT,DE,..)
 * @return array Optionen für Auswahlliste
 *
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 * @global array $db Datenbank Handle
 */
function VF_Sel_Bdld($land, $sub_funct = "", $stabkz = "")
{
    global $debug, $db, $flow_list,$module;

    flow_add($module, "VF_Comm_Funcs.inc Funct: VF_Sel_Bdld");

    if ($debug) {
        echo "<pre class=debug>Bundesld Sel L Beg:  \$land $land <pre>";
    }

    $opt_val = array();
    $sql = "SELECT * FROM `fh_bundesld` ";
    $where = "";

    if ($stabkz != "") {
        $where = " WHERE bl_stabkz= '$stabkz' ";
    }

    $sql .= $where;

    if ($debug) {
        echo "<pre class=debug>Bundesld Sel L sql: \$sql $sql  <pre>";
    }

    $return_bl = SQL_QUERY($db, $sql);

    if ($sub_funct == 0) {
        $opt_val['Neueingabe'] = "Neuen Datensatz engeben";
    }
    if ($sub_funct == 8) {
        $bdld = ' ';
        $opt_val[' '] = "keine Auswahl getroffen";
    }

    while ($row = mysqli_fetch_assoc($return_bl)) {
        $opt_val[$row['bl_blabkz']] = $row['bl_name'];
    }

    if ($sub_funct == 7) {
        if ($land == "alle") {
            $opt_val['alle'] = "alle auswählen";
        }
    }

    if ($debug) {
        print_r($opt_val);
        echo "<pre class=debug><br>F Bdld_sel L End: <pre>";
    }
    return $opt_val;
}

// Ende von function VF_Sel_Bdld

/**
 * Verknüpfung der hier aktuellen Daten zu de Beschreibungen (KFZ - > Dokumente wie ZulSchein usw)
 *
 * offen, muss überarbeiter werden, da die Definitionen durch viele SStruktur- Änderungen verschoben wurden
 * Zuordnung von Detailbechreibugen (.html, .php) zu einer Sammlung
 *
 * @param string $sammlung
 *            Sammlugs- Abkürzung
 * @param string $invblink
 *            Abkürzug des Staates (At, DE, ...)
 * @param string $sub_funktion:
 *            0 = neuen Datensatz eingeben; 8 = keine Auswahl getroffen; ins Ausgabe-Array
 *
 * @return array $opt_val[Link= .html|.php] = Titel
 *
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 * @global array $db Datenbank Handle
 * @global array $neu Eingelesene Daten Felder
 * @global string $module Modul-Name für $_SESSION[$module] - Parameter
 * @global string $pict_path Verzeichnis, in das die Bilder/Dokumente kommen sollen
 */
function VF_Sel_Det($sammlg, $invblink, $sub_funct)
{
    global $debug, $db, $neu, $module, $pict_path, $flow_list;

    flow_add($module, "VF_Comm_Funcs.inc Funct: VF_Sel_Det");

    if ($debug) {
        echo "<pre class=debug>VF_Det_Ausw L Beg: \$sammlg $sammlg <pre>";
    }
    $opt_val = array('Momentan keine Detailanfragen möglich.','Struktur muss neu aufgebaut werden.');
    /*
        $select = " WHERE `id_sammlg`='$sammlg' ";

        $opt_val = array();

        $sql = "SELECT * FROM `in_details` $select ";
        $return_st = mysqli_query($db, $sql) or die("Datenbankabfrage gescheitert. " . mysqli_error($db));
        if ($sub_funct == 0) {
            $opt_val['Neueingabe'] = "Neuen Datensatz engeben";
        }
        if ($sub_funct == 8) {
            $opt_val['0'] = "keine Auswahl getroffen";
        }
        while ($row = mysqli_fetch_assoc($return_st)) {
            # $id = "$row->id_link";
            $opt_val[$row['id_link']] = $row['id_titel'];
        }

        mysqli_free_result($return_st);
    */
    return ($opt_val);
}

// Ende von function VF_Sel_Det


/**
 * Auwahl für Sammlungs- Auswahl
 *
 * @param string $sam_grp
 *            Referat blank, A0_F,A0_G B0,C0,D0,F0,K0 (Kaiser),W0 (wI/II)
 * @param string $sammlung
 *            Option List für Select_Feld
 * @return string
 *
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 * @global array $db Datenbank Handle
 * @global string $module Modul-Name für $_SESSION[$module] - Parameter
 */
function VF_Sel_Sammlg($sam_grp, $sammlung)
{
    global $debug, $db, $module, $flow_list;

    flow_add($module, "VF_Comm_Funcs.inc Funct: VF_Sel_Sammlg");

    if ($debug) {
        echo "<pre class=debug>L 1318 Referat/Sammlungs-Auswahl L Beg: \$sam_grp $sam_grp\$sammlung $sammlung <pre>";
    }
    $Grp = array();
    switch ($sam_grp) {
        case "S":
            $Grp = VF_Sammlung;
            break;
        case "T":
            $Grp = VF_Sammlung_Tausw;
            break;
        case "A0":
            $Grp = VF_Sammlung_A0;
            break;
        case "A0_F":
            $Grp = VF_Sammlung_A0_F;
            break;
        case "A0":
            $Grp = VF_Sammlung_A0_G;
            break;
        case "B0":
            $Grp = VF_Sammlung_B0;
            break;
        case "C0":
            $Grp = VF_Sammlung_C0;
            break;
        case "D0":
            $Grp = VF_Sammlung_D0;
            break;
        case "F0":
            $Grp = VF_Sammlung_F0;
            break;
        case "K0":
            $Grp = VF_Sammlung_K0;
            break;
        case "W0":
            $Grp = VF_Sammlung_W0;
            break;
    }

    $sam_sel = "<select name='sammlg' id='sammlg' >";

    foreach ($Grp as $key => $value) {
        $selected = '';
        if ($key == $sammlung) {
            $selected = 'selected';
        }
        $sam_sel .= "<option $selected value='$key' >$value</option>\n";
    }

    $sam_sel .= '</select>';
    # echo "samm__sel $sam_sel <br>";
    return $sam_sel;
}

# ende VF_Sel_Sammlung

/**
 * Auswahl eines Staates für select Liste
 *
 * @param string $Dok_Thema
 *            Gruppen- Abkürzung
 * @param string $sub_funkt:
 *            0 = neuen Datensatz eingeben; 7 = Internations (Ctif); ins Ausgabe-Array
 *
 * @return array $opt_val[abk] = Staat Auswahlliste für Select_Feld
 *
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 * @global array $db Datenbank Handle
 * @global array $neu Eingelesene Daten Felder
 *
 */
function VF_Sel_Staat($FeldName, $sub_funct)
{
    global $debug, $db, $neu, $flow_list,$module;

    flow_add($module, "VF_Comm_Funcs.inc Funct: VF_Sel_Staat");

    if ($debug) {
        echo "<pre class=debug> Staat Ausw L Beg: \$FeldName \$sub_funct $sub_funct<pre>";
    }

    $opt_val = array();
    $sql = "SELECT * FROM `fh_staaten` ";
    $return_st = mysqli_query($db, $sql) or die("Datenbankabfrage gescheitert. " . mysqli_error($db));
    if ($sub_funct == 0) {
        $opt_val['Neueingabe'] = "Neuen Datensatz engeben";
    }
    if ($sub_funct == 8) {
        $opt_val['0'] = "keine Auswahl getroffen";
    }
    if ($sub_funct == 7) {
        $opt_val['INT'] = "International (CTIF)";
    }
    while ($row = mysqli_fetch_assoc($return_st)) {
        $opt_val[$row['st_abkzg']] = $row['st_name'];
    }

    mysqli_free_result($return_st);
    /*
     * if ($debug) {
     * print_r($opt_val);
     * echo "<pre class=debug>F Staat_Ausw L End: \$FeldName $FeldName \$sub_funct $sub_funct <pre>";
     * }
     */
    return ($opt_val);
} # Ende von function VF_Sel_Staat

/**
 * Bereitstellung der Urheber- Anzeige beim jeweiligen Bild
 *
 * @return array Für die Anzeige des Urhebers beim Bild
 *
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 * @global array $db Datenbank Handle
 * @global string $module Modul-Name für $_SESSION[$module] - Parameter
 */
function VF_Sel_Urheber_n()
{
    global $debug, $db, $module, $urheb_arr, $flow_list, $path2ROOT;

    flow_add($module, "VF_Comm_Funcs.inc.php Funct: VF_Sel_Urheber_n");

    if ($debug) {
        echo "<pre class=debug>Urheber-Auswahl L Beg:  <pre>";
    }

    $urheb_arr[0] = "Kein Urheber ausgewählt.";

    $sql_ur = "SELECT * FROM `fh_eigentuemer` WHERE ei_urh_kurzz != '' ORDER BY ei_id ASC ";

    $return_ur = SQL_QUERY($db, $sql_ur);

    while ($row = mysqli_fetch_object($return_ur)) {

        if ($row->ei_org_typ == 'Privat') {
            $fotogr = $row->ei_name ." ". $row->ei_vname;
        } else {
            $fotogr = $row->ei_org_typ ." ".$row->ei_org_name;
        }
        $_SESSION[$module]['Urheber_List'][$row->ei_id] =  array('ei_media' => $row->ei_media,'ei_fotograf' => $fotogr, 'ei_urh_kurzz' => $row->ei_urh_kurzz );

        $sql_su = "SELECT * FROM fh_eign_urh WHERE fs_eigner='$row->ei_id'  ";

        $return_su = SQL_QUERY($db, $sql_su);

        $num_rec = mysqli_num_rows($return_su);

        if ($num_rec > 0) {
            while ($row_su = mysqli_fetch_object($return_su)) {

                $_SESSION[$module]['Urheber_List'][$row->ei_id][$row_su->fs_urh_kurzz] = array('typ' => $row_su->fs_typ,'fotogr' => $row_su->fs_fotograf,'urh_nr' => $row_su->fs_urh_nr);

                $urheb_arr[$row->ei_id] = $row_su->fs_fotograf;
            }
        }


    }
    /**
     * Feststellen der Urheber- Nummer bei Organisation
     * $_SESSION[$module]['URHEBER'][$urh_nr] = Eigentümer- Nummer
     * $_SESSION[$module]['URHEBER'][$urh_nr]['ei_media'] = Media Kennung A,F,I,V  Audio, Foto, Film, Video
     * $_SESSION[$module]['URHEBER'][$urh_nr]['ei_fotograf'] = Name der Org (Verfüger) oder Name des Urhebers
     * $_SESSION[$module]['URHEBER'][$urh_nr]['ei_urh_kurzz'] = Urheber- Kennzeichen, wenn kein urh_kenzz ausgewählt ist
     * $_SESSION[$module]['URHEBER'][$urh_nr]['urh_abk'] array
     * $_SESSION[$module]['URHEBER'][$urh_nr]['urh_abk']['fs_urhnr'] = Eigentümer- Nummer des Urhebers (wenn <> $urh_nr, diese nutzen)
     * $_SESSION[$module]['URHEBER'][$urh_nr]['urh_abk']['fs_fotograf'] = Name des Urhebers für Anzeige bei Bild
     * $_SESSION[$module]['URHEBER'][$urh_nr]['urh_abk']['fs_urh_kurzz'] = Kennzeichen des Urhebers (für Dateinamens- Beginn)
     * $_SESSION[$module]['URHEBER'][$urh_nr]['urh_abk']['fs_typ'] = für die Zuordnung im Archiv
     * $_SESSION[$module]['URHEBER'][$urh_nr]['urh_abk']['fs_verz'] = für ein Verzeichnis
     *
     * $_SESSION[$module]['URHEBER']['Media]['urh_nr']= array(['urh_nr]['type']['kurzz']['fotogr']['verz'])
     */
    mysqli_free_result($return_ur);
    mysqli_free_result($return_su);

}

# ende VF_Sel_Urheber_n

/**
 * Setzen der Update- Variablen $_SESSION[$module]['all_upd'] und der Wartungs- Variablen $_SESSION['Config']['c_Wartung'] == N -> Keine Updates/neuanlagen möglichConfig
 *
 * Variable
 *
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 * @global array $db Datenbank Handle
 * @global string $module Modul-Name für $_SESSION[$module] - Parameter
 */
function VF_set_module_p()
{
    global $debug, $db, $module, $flow_list, $path2ROOT;

    flow_add($module, "VF_Comm_Funcs.inc.php Funct: VF_set_module_p");

    $ini_arr = parse_ini_file($path2ROOT.'login/common/config_s.ini', true, INI_SCANNER_NORMAL);

    if (!isset($_SESSION[$module])) {
        $_SESSION[$module] = array();
    }

    $_SESSION[$module]['p_zug'] = $_SESSION['VF_Prim'][$module];
    if ($_SESSION[$module]['p_zug'] == "V") {
        $_SESSION[$module]['all_upd'] = true;
    } else {
        if ($ini_arr['Config']['wart'] == "N" and $_SESSION[$module]['p_zug'] == "Q") {
            $_SESSION[$module]['all_upd'] = true;
        } else {
            $_SESSION[$module]['all_upd'] = false;
        }
    }
}

# Ende Funktion VF_set_module_p


/**
 * Einlesen der existierenden Tabellen in der Datenbank
 *
 * Ausgabe der existierenden Tabellen (mittels SHOW_TABLES) in global Arrays je Prefix (fz_, fo_,...)
 *
 *
 * @global array $db Datenbank Handle
 * @global string $LinkDB_database Datenbank- Name
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 * @global array $ar_arr Tabelle mit den Archivalien- Tabellen
 * @global array $fo_arr Tabelle mit den Foto- Tabellen -> wird dm_arr
 * @global array $fm_arr Tabelle mit den Muskel- Tabellen
 * @global array $ge_arr Tabelle mit den Geräte- Tabellen
 * @global array $in_arr Tabelle mit den Inventar- Tabellen
 * @global array $zt_arr Tabelle mit den Zeitungs- Tabellen
 *
 */   // add dm_arr für dm_edien, del fo_arr, fz_arr, fm-arr und updates in den jeweiligen scripts
function VF_tableExist()
{
    global $db, $LinkDB_database, $debug, $ar_arr, $dm_arr, $maf_arr, $mag_arr, $muf_arr, $mug_arr, $in_arr, $zt_arr, $mar_arr, $flow_list,$module;

    flow_add($module, "VF_Comm_Funcs.inc Funct: VF_tableExists_");

    $eror = false;

    $result = SQL_QUERY($db, "SHOW TABLES "); # like '$table_name%'

    if ($result !== false) {
        // if at least one table in result
        if ($result->num_rows > 0) {
            // traverse the $result and store all tables into an array
            while ($row = $result->fetch_assoc()) {
                # print_r($row);
                $tables_db[] = $row['Tables_in_' . $LinkDB_database]; # ($table_name."%")
            }
        } else {
            $eror[] = 'There is no table in "' . $LinkDB_database . '"';
        }
    } else {
        $eror[] = 'Unable to check the "' . $LinkDB_database . '"';
    }

    // if $eror not False, output errors and returns false, otherwise, returns true
    if ($eror !== false) {
        echo implode('<br/>', $eror);
        return false;
    } else {
        # print_r($tables_db);echo "<br>L 1124 <br>";
        foreach ($tables_db as $key => $table) {
            if (substr($table, 0, 5) == "ar_ch" || substr($table, 0, 5) == "ar_or") {
                $ar_arr[$table] = 1;
                continue;
            }
            if (substr($table, 0, 5) == "dm_ed") {
                $dm_arr[$table] = 1;
                continue;
            }

            if (substr($table, 0, 4) == "ma_f") {
                $maf_arr[$table] = 1;
                continue;
            }
            if (substr($table, 0, 4) == "ma_g") {
                $mag_arr[$table] = 1;
                continue;
            }

            if (substr($table, 0, 5) == "mu_fa") {
                $muf_arr[$table] = 1;
                continue;
            }
            if (substr($table, 0, 5) == "mu_ge") {
                $mug_arr[$table] = 1;
                continue;
            }

            if (substr($table, 0, 5) == "in_ve") {
                $in_arr[$table] = 1;
                continue;
            }
            if (substr($table, 0, 5) == "zt_in") {
                $zt_arr[$table] = 1;
                continue;
            }

            if (substr($table, 0, 5) == "ma_ar") {
                $mar_arr[$table] = 1;
                continue;
            }
        }
        return true;
    }
}

# End of function tableeists

/**
 * Festlegung der Zugriffs- Berechtigung in Abhängigkeit der Wartungs- Information (aus fh_config) je Module
 *
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 * @global string $module Modul-Name für $_SESSION[$module] - Parameter
 */
function VF_upd()
{
    global $debug, $module, $flow_list,$path2ROOT;

    flow_add($module, "VF_Comm_Funcs.inc Funct: VF_upd");

    $ini_arr = parse_ini_file($path2ROOT.'login/common/config_s.ini', true, INI_SCANNER_NORMAL);

    if ($ini_arr['Config']['wart'] == "J" || $ini_arr['Config']['wart'] == "u") {
        $_SESSION[$module]['all_upd'] = false;
    } else {
        if ($module == "VF_Prim") {
            $_SESSION[$module]['p_zug'] = "R";
            $_SESSION[$module]['all_upd'] = true;
        } else {
            if ($_SESSION[$module]['p_zug'] == "V") {
                $_SESSION[$module]['all_upd'] = true;
            } else {
                if ($_SESSION[$module]['p_zug'] == "Q") {
                    $_SESSION[$module]['all_upd'] = false;
                    foreach ($_SESSION['VF_Prim']['zu_eign'] as $indx => $val) {
                        $eignr_ = $_SESSION['Eigner']['eig_eigner'];
                        if ($val == $eignr_) {
                            $_SESSION[$module]['all_upd'] = true;
                        }
                    }
                }
            }
        }
    }
}

# end Funct VF_upd

/**
 * Hochladen von Dateien
 *
 * Bei allen Dateien:  ändern Umlaute auf alte Schreibweise Ä -> AE
 * Bei Grafischen Dateien: wenn Urheber-Abkürzung und Foto-Datum vorhanden, Umbenennen nach Foto-Vorgabe (Urh-Datum-Dateiname)
 *
 *
 * @param string $uploaddir      Zielverzeichnis
 * @param string $i              index zur uploadfile $files[uploadfile_x
 * @param string $urh_abk        Abkürzung des Urhebernamens
 * @param string $fo_aufn_datum  Aufnahmedatum
 * @return string Dsn der Datei  Name der Datei zum Eintrag in Tabelle
 */
function VF_Upload($uploaddir, $fdsn, $urh_abk = "", $fo_aufn_datum = "")
{
    global $debug, $module, $flow_list;

    flow_add($module, "VF_Comm_Funcs.inc Funct: VF_Upload");

    echo " L 01752 Upl upldir $uploaddir fdsn $fdsn <br>";
    var_dump($_FILES);
    $target = "";
    if (! empty($_FILES[$fdsn])) {
        $target = basename($_FILES[$fdsn]['name']);

        if ($target != "") {
            $target = VF_trans_2_separate($target);

            $fn_arr = pathinfo($target);
            $ft = strtolower($fn_arr['extension']);

            if (in_array($ft, GrafFiles) && $urh_abk != "" && $fo_aufn_datum != "") {
                $newfn_arr = explode('-', $target);
                $cnt = count($newfn_arr);
                if ($cnt == 1) { # original- Dateiname, nicht im Format urh-datum-Aufn_dateiname.ext,
                    $target = "$urh_abk-$fo_aufn_datum-" . $fn_arr['basename'];
                }
            } else {
                $target = $fn_arr['basename'];
            }
            echo "L 01773 fdsn $fdsn ; uploaddir $uploaddir; target $target <br>";
            var_dump($_FILES[$fdsn]);
            if (move_uploaded_file($_FILES[$fdsn]['tmp_name'], $uploaddir . $target)) {
                var_dump($_FILES[$fdsn]);
                return $target;
            }
        }
    }

} # end Funct VF_upload

/**
 * Hochladen von Dateien
 *
 * Bei allen Dateien:  ändern Umlaute auf alte Schreibweise Ä -> AE
 * Bei Grafischen Dateien: wenn Urheber-Abkürzung und Foto-Datum vorhanden, Umbenennen nach Foto-Vorgabe (Urh-Datum-Dateiname)
 *
 *
 * @param string $uploaddir      Zielverzeichnis
 * @param string $i              index zur uploadfile $files[uploadfile_x
 * @param string $urh_abk        Abkürzung des Urhebernamens
 * @param string $fo_aufn_datum  Aufnahmedatum
 * @return string Dsn der Datei  Name der Datei zum Eintrag in Tabelle
 */
function VF_Upload_M($uploaddir, $fdsn, $urh_abk = "", $fo_aufn_datum = "")
{
    global $debug, $module, $flow_list;

    flow_add($module, "VF_Comm_Funcs.inc Funct: VF_Upload_M");

    echo " L 01803 Upl upldir $uploaddir fdsn $fdsn <br>";
    # var_dump($_FILES[$fdsn]);
    $target = "";
    if (! empty($_FILES[$fdsn])) {
        $target = basename($_FILES[$fdsn]['name']);

        if ($target != "") {
            $target = VF_trans_2_separate($target);

            $fn_arr = pathinfo($target);
            $ft = strtolower($fn_arr['extension']);

            if (in_array($ft, GrafFiles) && $urh_abk != "" && $fo_aufn_datum != "") {
                $newfn_arr = explode('-', $target);
                $cnt = count($newfn_arr);
                if ($cnt == 1) { # original- Dateiname, nicht im Format urh-datum-Aufn_dateiname.ext,
                    $target = "$urh_abk-$fo_aufn_datum-" . $fn_arr['basename'];
                }
            } else {
                $target = $fn_arr['basename'];
            }
            echo "L 01784 fdsn $fdsn ; uploaddir $uploaddir; target $target <br>";
            # var_dump($_FILES[$fdsn]);
            if (move_uploaded_file($_FILES[$fdsn]['tmp_name'], $uploaddir . $target)) {
                # var_dump($_FILES[$fdsn]);
                return $target;
            }
        }
    }

} # end Funct VF_upload

/**
 * Hochladen von Dateien
 *
 * Bei allen Dateien:  ändern Umlaute auf alte Schreibweise Ä -> AE
 * Bei Grafischen Dateien: wenn Urheber-Abkürzung und Foto-Datum vorhanden, Umbenennen nach Foto-Vorgabe (Urh-Datum-Dateiname)
 *
 *
 * @param string $FldName
 * @param string $uploaddir
 * @param string $urh_abk
 * @param string $fo_aufn_datum
 * @return string Dsn der Datei
 */
function VF_Upload_Pic($FldName, $uploaddir, $urh_abk = "", $fo_aufn_datum = "")
{
    global $debug, $module, $flow_list;

    flow_add($module, "VF_Comm_Funcs.inc.php Funct: VF_Upload_Pic");

    $target1 = "";
    if (! empty($_FILES['uploaddatei_1'])) {
        $target1 = basename($_FILES['uploaddatei_1']['name']);

        if ($target1 != "") {
            $target1 = VF_trans_2_separate($target1);

            $fn_arr = pathinfo($target1);
            $ft = strtolower($fn_arr['extension']);
            if (in_array($ft, GrafFiles)) {
                $newfn_arr = explode('-', $target1);
                $cnt = count($newfn_arr);
                if ($cnt == 1 && $urh_abk != "" && $fo_auf_datum !=  "") { # original- Dateiname, nicht im Format urh-datum-Aufn_dateiname.ext,
                    $target1 = "$urh_abk-$fo_aufn_datum-" . $fn_arr['basename'];
                } else {
                    $target1 = $fn_arr['basename'];
                }

                if (move_uploaded_file($_FILES['uploaddatei_1']['tmp_name'], $uploaddir . $target1)) {
                    return $target1;
                }
            }
        }
    }
    return "";

    /*

    */

} # end Funct VF_upload_pic

/**
 * Umlaute  auf getrennte Schreibung Ä -> AE
 *
 * @param string $string
 * @return string
 */
function VF_trans_2_separate($string)
{
    global $debug, $module, $flow_list;

    flow_add($module, "VF_Comm_Funcs.inc.php Funct: VF_trans_2_separate");

    # UP to separate A -> AE
    $trans = array("ä" => "ae","Ä" => "AE","ö" => "oe","Ö" => "OE","ü" => "ue","Ü" => "UE","ß" => "sz");

    $new = strtr($string, $trans);

    return $new;
    /* Teststring:
          $ori = "abcdeÄÖÜäöüß@€";
          $out = VF_trans_2_separate($ori);
          echo "ori $ori out $out<br>";

     */
} # end Funct VF_trans_2_separate

function VF_Eig_Ausw()
{
    global $debug, $module, $flow_list, $tit_eig_leih;

    flow_add($module, "VF_Comm_Funcs.inc.php Funct: VF_Eig_Ausw");

    if (!isset($tit_eig_leih)) {
        $tit_eig_leih = 'Eigentümer';
    }

    echo "<tr><td colspan='2'>";
    echo "<div class='w3-container' style='background-color: PeachPuff '>";

    echo "    <div class='w3-container w3-light-blue'> ";
    echo "         <b>Auswahl des ".$tit_eig_leih."s</b>";
    echo "    </div>";

    echo "    <div class='w3-container w3-third'>";
    echo "         <label for='Level_e'>Namen des ".$tit_eig_leih."s eingeben &nbsp;  </label>";
    echo "    </div>";
    echo "    <div class='w3-container w3-twothird'> ";
    echo "        <br><input class='w3-input' type='text' id='autocomplete' name='auto' placeholder='".$tit_eig_leih." Name eingeben...' /> <br>Zur Auswahl auf den gewünschten Namen klicken<br>";
    echo "      <br>    <div id='suggestions'></div>";

    echo "     </div>";

    echo "</div>";

    ?>

    <script>
    document.observe("dom:loaded", function() {
        var inputField = $("autocomplete");
        var suggestionsBox = $("suggestions");
       
        
        inputField.observe("keyup", function() {
            var query = this.value.trim();

            if(query.length > 0) {
                new Ajax.Request('common/API/VF_Auto_Compl.API.php', { //   common/API/BA_Auto_Compl.API.php  VF_Z_E_Ausw.php
                    method: 'post',
                    parameters: { query : query},
                    onSuccess: function(response) {
                        suggestionsBox.update(response.responseText);
                        suggestionsBox.setStyle({ display: 'block' });
                    }
                });
            } else {
                suggestionsBox.update('');
                suggestionsBox.setStyle({ display: 'none' });
            }
        });
            
            suggestionsBox.observe("click", function(event) {
                var target = event.findElement('.autocomplete-suggestion');
                if(target) {
                    inputField.value = target.innerHTML;
                    suggestionsBox.update('');
                    suggestionsBox.setStyle({ display: 'none' });
                }
            });
    });
        </script>
    <?php
} # end Funct VF_Eig_Ausw

/**
 * Multi Dropdown select für verschiedene Auswahlen
 *
 * benötigt jquery
 *
 * @param string  $in_val
 * @param string $titel
 */
function VF_Multi_Dropdown($in_val, $titel = 'Mehrfach- Abfrage')
{
    global $debug,$path2ROOT, $MS_Init,$MS_Lvl,$MS_Opt, $MS_Txt, $module  ;

    flow_add($module, "VF_Comm_Funcs.inc Funct: VF_Multi_Dropdown");

    echo "<input type='hidden' id='opval' value='$MS_Opt' >";

    echo "<div class='container ' style='background-color: PeachPuff '>";  # div cont

    echo "    <div class='container  w3-light-blue'> ";  # div blue
    echo "         <b>$titel</b>";
    echo "    </div>";                                     # div end blue

    echo "<div class='w3-row'>";   # div w3 row

    echo "    <div class='w3-container w3-third'>";        # div label
    echo "         <label for='Level1'>".$MS_Txt[0]." &nbsp; </label>";
    echo "    </div>";                                     # div label end
    echo "    <div class='w3-container w3-twothird'> ";    # div data
    echo "        <select class='w3-input'  id='level1' name='level1' >";
    echo "             <option value='Nix'>Bitte wählen</option>";
    $checkd = "";
    foreach ($MS_Init as $samlg => $name):
        if ($samlg == $in_val) {
            $checkd = 'checked';
        }
        echo "<option value='$samlg' $checkd>$name </option>";
    endforeach;

    echo "         </select>";
    echo "     </div>";                                    # div data end
    echo "     </div>";                                    # div row  end


    if ($MS_Lvl >= 2) {
        echo "<div class='w3-row'>";

        echo "    <div class='w3-container w3-third'>";
        echo "         <label for='Level2'>".$MS_Txt[1]." &nbsp;  </label>";
        echo "    </div>";
        echo "    <div class='w3-container w3-twothird'> ";
        echo "        <select class='w3-input' id='level2' name='level2'>";
        echo "             <option value='Nix'>Bitte wählen</option>
                   </select>";
        echo "     </div>";

        echo "</div>";

        if ($MS_Lvl >= 3) {

            echo "<div class='w3-row'>";

            echo "    <div class='w3-container w3-third'>";
            echo "         <label for='Level3'>".$MS_Txt[2]." &nbsp;  </label>";
            echo "    </div>";
            echo "    <div class='w3-container w3-twothird'> ";
            echo "        <select class='w3-input' id='level3' name='level3'>";
            echo "             <option value='Nix'>Bitte wählen</option>
                   </select>";
            echo "     </div>";

            echo "</div>";

            if ($MS_Lvl >= 4) {
                echo "<div class='w3-row'>";
                echo "    <div class='w3-container w3-third'>";
                echo "         <label for='Level4'>".$MS_Txt[3]." &nbsp;  </label>";
                echo "    </div>";
                echo "    <div class='w3-container w3-twothird'> ";
                echo "        <select class='w3-input' id='level4' name='level4' >";
                echo "             <option value='Nix'>Bitte wählen</option>
                   </select>";
                echo "     </div>";
                echo "</div>";

                if ($MS_Lvl >= 5) {
                    echo "<div class='w3-row'>";
                    echo "    <div class='w3-container w3-third'>";
                    echo "         <label for='Level5'>".$MS_Txt[4]." &nbsp;  </label>";
                    echo "    </div>";
                    echo "    <div class='w3-container w3-twothird'> ";
                    echo "        <select class='w3-input' id='level5' name='level5'>";
                    echo "             <option value='Nix'>Bitte wählen</option> ";
                    echo "        </select>";
                    echo "     </div>";
                    echo "</div>";

                    if ($MS_Lvl == 6) {
                        echo "<div class='w3-row'>";
                        echo "    <div class='w3-container w3-third'>";
                        echo "         <label for='Level6'>".$MS_Txt[5]." &nbsp; </label>";
                        echo "    </div>";
                        echo "    <div class='w3-container w3-twothird'> ";
                        echo "         <select class='w3-input' id='level6' name='level6'  >";
                        echo "             <option value='Nix'>Bitte wählen</option>
                                      </select>";
                        echo "     </div>";

                        echo "</div>";
                    }
                }
            }
        }
    }

    echo "</div>";               # end div cont

    #echo "</div>";

} # ende function MultiSdropdown

/**
 *  Auswertung der Eingabe vom Multi_Select_Dropdown
 *
 *  Daten werden direkt von $_POST ausgewertet
 *
 * @return string Sammlungs. Kennung
 */
function VF_Multi_Sel_Input()
{
    global $debug,$path2ROOT, $module  ;
    # var_dump($_POST);
    flow_add($module, "VF_Comm_Funcs.inc Funct: VF_Multi_Sel_Input");

    $response = "";
    if (isset($_POST['level1']) && ($_POST['level1'] != "")) {
        $response = trim($_POST['level1']);
    }

    if (isset($_POST['level2']) && ($_POST['level2'] != "")) {
        if ($_POST['level2'] != "Nix" && $_POST['level2'] != "Nix") {
            $response = trim($_POST['level2']);
        }
    }

    if (isset($_POST['level3']) && ($_POST['level3'] != "")) {
        if ($_POST['level3'] != "Nix" && $_POST['level3'] != "Nix") {
            $response = trim($_POST['level3']);
        }
    }

    if (isset($_POST['level4']) && ($_POST['level4'] != "")) {
        if ($_POST['level4'] != "Nix" && $_POST['level4'] != "Nix") {
            $response = trim($_POST['level4']);
        }
    }

    if (isset($_POST['level5']) && ($_POST['level5'] != "")) {
        if ($_POST['level5'] != "Nix" && $_POST['level5'] != "Nix") {
            $response = trim($_POST['level5']);
        }
    }

    if (isset($_POST['level6']) && ($_POST['level6'] != "")) {
        if ($_POST['level6'] != "Nix" && $_POST['level6'] != "Nix") {
            $response = trim($_POST['level6']);
        }
    }

    return $response;

} # Ende Function VF_Multi_Sel_input

/**
 * AuswahlListe der EigentÃ¼mer fÃ¼r select Liste
 *
 * @param string $FeldName
 *            Name des Eingabefeldes
 * @param string $sub_funktion:
 *            0 = neuen Datensatz eingeben; 81 = keine Auswahl getroffen; ins Ausgabe-Array
 *
*
* @return array $opt_val_ei[eig_nr] = Eigner_Name
*
* @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
* @global array $db Datenbank Handle
* @global array $neu Eingelesene Daten Felder
*/
function VF_Sel_Eigner($FeldName, $sub_funct)
{
    global $debug, $db, $neu,$flow_list;

    if ($debug) {
        echo "<pre class=debug>Eigent. Auswahl L Beg: \$FeldName $FeldName \$sub_func $sub_funct <pre>";
    }

    $opt_val_ei = array();
    $sql = "SELECT * FROM `fh_eigentuemer` ORDER BY ei_name ASC";
    $return_bl = mysqli_query($db, $sql) or die("Datenbankabfrage gescheitert. " . mysqli_error($db));
    if ($sub_funct == 0) {
        $opt_val_ei['Neueingabe'] = "Neuen Datensatz eingeben";
    }
    if ($sub_funct == 81) {
        $opt_val_ei['0'] = "keine Auswahl getroffen";
    }

    while ($row = mysqli_fetch_assoc($return_bl)) {
        $key = $row['ei_id'];
        $opt_val_ei[$key] = $row['ei_name'];
    }

    mysqli_free_result($return_bl);

    if ($debug) {
        echo "<pre class=debug>F SeL_Eigner L End:  \$FeldName $FeldName <pre>";
        # print_r($opt_val_ei);
    }
    return $opt_val_ei;
} // ende vf-sel-eigner


/**
 * Autocomple für die Auswahl von Aufbauer des  Fahrzeuges
 * Autocomplete mit prototype.js
 * immer in Verbindung mit BA_Aufo_Funktion() -> durchführung
 *
 */
function VF_Auto_Aufbau()
{
    global $debug, $module, $flow_list;
    flow_add($module, "VF_Comm__Funcs.lib.php Funct: BA_Auto_Aufbau");
    ?>
    <div class='w3-container' style='background-color: PeachPuff '> <!--   -->
        <b>Suchbegriff für Aufbau- Hersteller eingeben:</b> <input type="text" class="autocomplete" data-proc="Aufbauer" data-target="suggestAufbauer" data-feed="aufbauer" size='50'/>
    </div>  
    <div id="suggestAufbauer" class="suggestions">
       <input type="hidden" name="aufbauer" id="aufbauer" />
    <div>
    <?php
} // Ende VF_Auto_Aufbau

// Beispiel: Funktion für das Eingabefeld von gradually
function VF_Auto_Eigent($t, $cl = false)
{
    global $debug, $module, $flow_list;
    flow_add($module, "VF_Comm_Funcs.lib.php Funct: VF_Auto_Eigent");
    console_log('autoeigent');
    ?>
    <div class='w3-container' style='background-color: PeachPuff; padding: 10px;'>
    <?php
        if (!isset($t) && $t = 'E') {
            echo "<b>Suchbegriff für Eigentümer eingeben:</b>";
        } else {
            echo "<b>Suchbegriff für Urheber eingeben:</b>";
        }
    ?>
        <input type="text" class="autocomplete" data-proc="Eigentuemer" data-target="suggestEigener" data-feed="eigentuemer" size='50'/>
    </div>
    <div id="suggestEigener" class="suggestions">
       <input type="hidden" name="eigentuemer" id="eigentuemer" />
    </div>
    <?php
    if ($cl) {
        echo "<button type='submit' name='phase' value='1' class=green>Weiter</button></p>";
    }
} // Ende VF_Auto_Eigent

function VF_Auto_Herstell()
{
    global $debug, $module, $flow_list;
    flow_add($module, "VF_Comm_Funcs.lib.php Funct: VF_Auto_Herstell");
    console_log('autoherstell');
    ?>
    <div class='w3-container' style='background-color: PeachPuff '> 
    <b>Suchbegriff für Hersteller eingeben:</b> <input type="text" class="autocomplete" data-proc="Hersteller" data-target="suggestHersteller" data-feed="hersteller" size='50'/>
    </div>  
    <div id="suggestHersteller" class="suggestions">
       <input type="hidden" name="hersteller" id="hersteller" />
    </div>
    <?php
} // Ende VF_Auto_Herstell

function VF_Auto_Taktb()
{
    global $debug, $module, $flow_list;
    flow_add($module, "VF_Comm_Funcs.lib.php Funct: VF_Auto_Taktb");
    console_log('autotaktb');
    ?>
    <div class='w3-container' style='background-color: PeachPuff '> 
    <b>Suchbegriff für Taktische Bezeichnung eingeben:</b> <input type="text" class="autocomplete" data-proc="Taktisch" data-target="suggestTaktisch" data-feed="taktisch" size='50' />
    </div>  
    <div id="suggestTaktisch" class="suggestions">
       <input type="hidden" name="taktisch" id="taktisch" />
    </div>
    <?php
} // Ende VF_Auto_Taktb


/**
 * Setzen des Speicherpfades per  Return zurückgegeben
 * VF_Upload_Pfad_M
 *
 *
 * @param string $aufndat
 *            Datum oder Jahr der Aufnahme - oder Pfadname  - Darf nicht leer sein
 * @param string $basepfad
 *            Basispfad darf leer sein
 * @param string $suffix
 *            Zusatzpfad darf leer sein
 * @param string $aoPfad Archiv- Ordnungs- Teil, kann auch leer sein
 * @param string $urh_nr Urheber- Nummer
 *
 * @return string $d_path
 *
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 * @global string $module Modul-Name für $_SESSION[$module] - Parameter
 *
 */
function VF_Upload_Pfad_M($aufnDatum, $suffix = '', $aoPfad = '', $urh_nr = '')
{
    global $debug, $module, $flow_list, $path2ROOT;

    flow_add($module, "VF_Comm_Funcs.lib.php Funct: VF_Upload_Pfad_M");
    console_log('uploadpfad');

    $basepath = $path2ROOT.'login/'.$_SESSION['VF_Prim']['store'].'/';

    $grp_path = $ao_path = $verzeichn = $subverz = "";

    $mand_mod = array('INV', 'FOT', 'F_G','F_M');

    if (in_array($module, $mand_mod)) { // Mandanten- Modus
        if ($urh_nr == "") {
            $grp_path = $_SESSION['Eigner']['eig_eigner'].'/';
        } else {
            $grp_path = $urh_nr.'/';
        }

        switch ($module) {

            case 'INV':
                $verzeichn =  'INV/';
                break;
            case 'F_G':
                if (substr($_SESSION[$module]['sammlung'], 0, 4) == 'MA_F') {
                    $verzeichn =  'MaF/';
                } else {
                    $verzeichn =  'MaG/';
                }
                break;
            case 'F_M':
                if (substr($_SESSION[$module]['fm_sammlung'], 0, 4) == 'MU_F') {
                    $verzeichn =  'MuF/';
                } else {
                    $verzeichn =  'MuG/';
                }
                break;
            case 'FOT':
                $ao_path = $aoPfad.'/';
                break;
        }

    } else {
        switch ($module) {
            case 'OEF':
                break;
            case 'PSA':
                if ($_SESSION[$module]['proj'] == 'AERM') {
                    $verzeichn = 'PSA/AERM/';
                } else {
                    $verzeichn = 'PSA/AUSZ/';
                }
                break;

        }
    }
    $dPath = $basepath.$grp_path.$ao_path.$verzeichn.$subverz;
    #echo "L 0236 UplLib dPath $dPath <br>";
    return $dPath;

} // end VF Upload_Pfad_M

/**
 * Formular- Teil zum hochladen von mehrfach-Dateien (fotos, Dokumente, ..) Modifizierte Vwers
 *
 *
 * @return
 *
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 * @global array $db Datenbank Handle
 * @global array $neu Eingelesene Daten Felder
 * @global array $Tabellen_Spalten_COMMENT Global Array (Schlüssel: Spaltenname) mit Texten zu den Spalten
 * @global string $flow_lost True = Ausgabe der Aufruf- Trace
 * @global boolean $hide_area True - Bereich nur bei Neueingabe oder klicken auf Button Anzeigen (Ausser Foto, da nur die leeren nicht anzeigen)
 * @global string  §path2ROOT Pfad zum Root
 */
function VF_Upload_Form_M()
{
    global $debug, $db, $neu, $module, $Tabellen_Spalten_COMMENT, $flow_list, $hide_area, $path2ROOT;

    flow_add($module, "VF_Upload.lib.php Funct: VF_Upload_Form_M");
    console_log('uploadform');

    /**
     * Parameter für die Fotos:
     *
     * $_SESSION[$module]['Pct_Arr'][] = array("k1" => 'fz_b_1_komm', 'b1' => 'fz_bild_1', 'rb1' => '', 'up_err1' => '', 'f1' => '','f2'=>'');
     * wobei k1 = blank : kein Bild- Text- Feld - kein Bildtext , keinegeminsame Box, rb1 und up_err werden vom Uploader gesetzt,
     *                           f1 und f2 sind 2 Felder, die zusätzlich im Block eingegeben, angezeigt werden können
     */
    /* Schalten der Foto- Update blöcke */

    if (!isset($hide_area)) {
        $hide_area = 0;
    }

    $hide_area_group1 = $hide_area_group2 = $hide_area;

    if ($debug) {
        echo "<pre class=debug>VF_M_Foto L Beg: \$Picts ";
        var_dump($_SESSION[$module]['Pct_Arr']);
        echo "<pre>";
    }

    $pic_cnt = count($_SESSION[$module]['Pct_Arr']);

    /**
     * Floating Block mit Bild, Bildbeschreibung , Bildname und Upload-Block
     */
    echo "<div class='w3-container'>";                           // container für Foto und Beschreibung
    echo "<div class = 'w3-row w3-border'>";                     // Responsive Block start
    echo "<fieldset>";

    ?>

    <div style="margin-bottom:20px; border:1px solid #ccc; padding:10px;"> 
            
         
          <div id="gruppe1" class="foto-upd-container" 
              style="<?php echo ($hide_area_group1 == 1) ? 'display:none;' : ''; ?>">
             <div class="foto-upd">
             <p>Optionale Parameter für Upload, noch in Planung für Upload mit Bildbearbeitung.</p>
              <!-- 
                 <! -- Auswahl: Bibliothek oder Upload -- >
                 <p>Fotos aus den Foto-Bibliotheken einfügen?</p>
                 <label>
                     <input type='radio' class='sel_libs' name='sel_libs' value='Ja' checked> Ja
                 </label>
                 <label>
                     <input type='radio' class='sel_libs' name='sel_libs' value='Nein'> Nein - Neu hochladen
                 </label>
        
        		
        		<! -- 
        		wenn sel_libs: Ja    -- Auswahl nach Sammlung, Anzeige fotos, auswahl in Menge der Fotos
        		                        ausgesuchter Dsn in das entsprechenden Eigabefeld stellen
        		               Nein  -- normale Eingabe über File-Upload, bevorzugt AJAX mit resize und Wasserzeichen,
        		                        Eingabe Urheber, Aufnahmedatum, Wasserzeichen J/N, 
        		
                <div id='upl_libs' style='displ:none'>
                  <p>Aussuchen aus libs
                   </p>
                </div>
              
                <div id='upl_new' style='displ:none'>
                  hochladen
                
                </div>
        --- >
                <div id='upl_libs' style='display:block; margin-top:10px;'>
                 <p>Suchbegriff für die Bibliothek:</p>
                 <input type='text' id='suche' placeholder='Suchbegriff' />
                 <button type='button' onclick='sucheBibliothek()'>Suchen</button>
                    <div id=' suchergebnis' style='margin-top:10px; max-height:150px; overflow:auto; border:1px solid #ccc;'></div>
                </div>    
             -->
             </div>
         </div>
        
    <?php

    for ($i = 0; $i < $pic_cnt; $i++) {
        $p_a = $_SESSION[$module]['Pct_Arr'][$i];

        $j = $i + 1; /** Für die Bil- Nr- Anzeige */

        #$pict_path = VF_Upload_Pfad_M ('', '', '', '');

        /**
         * Responsive Container innerhalb des loops
         */
        echo "<div class = 'block-container w3-container w3-half ' data-index='$i'  data-hide-area='$hide_area'>";                 // start half contailer
        echo "<fieldset>";
        echo "Bild $j <br>";

        if ($p_a['udir'] != "") {
            $uploaddir = $p_a['udir'];
        }
        if ($p_a['ko'] != "") {
            if (isset($Tabellen_Spalten_COMMENT[$p_a['ko']])) {
                echo $Tabellen_Spalten_COMMENT[$p_a['ko']];
            } else {
                echo $p_a['ko'];
            }
            echo "<textarea class='w3-input' rows='7' cols='20' name='".$p_a['ko']."' >" . $neu[$p_a['ko']] . "</textarea> ";
        }
        if ($p_a['f1'] != '') {
            Edit_Daten_Feld_Button($p_a['f1'], 30);
        }
        if ($p_a['f2'] != '') {
            Edit_Daten_Feld_Button($p_a['f2'], 30);
        }

        echo "<div class='bild-detail' >";

        if ($neu[$p_a['bi']] != "") {
            $fo = $neu[$p_a['bi']];
            #console_log('L 02528 foto '.$fo);

            $fo_arr = explode("-", $neu[$p_a['bi']]);
            $cnt_fo = count($fo_arr);


            if ($cnt_fo >= 3) {
                $uploaddir .= "09/";
            } else {
                #$uploaddir .= 'MaG/';
            }

            $f_a = pathinfo(strtolower($fo));

            $aossg = "";
            $ext = $f_a['extension'];

            if (stripos($uploaddir, '09/') >= 1) {
                $ext = $f_a['extension'];
                $ao_ssg = "";
                if (in_array($ext, AudioFiles)) {
                    $ao_ssg = "02/";
                }
                if (in_array($ext, GrafFiles)) {
                    $ao_ssg = "06/";
                }
                if (in_array($ext, VideoFiles)) {
                    $ao_ssg = "10/";
                }
                $ao_ssg;
            }
            if ($cnt_fo >= 3) {   // URH-Verz- Struktur de dsn
                $urh = $fo_arr[0]."/";
                $verz = $fo_arr[1]."/";
                /*
                if ($cnt_fo > 3)  {
                    if (isset($fo_arr[2]))
                        $verz .= $fo_arr[2]."/";
                }
                   */
                $d_p = $path2ROOT ."login/AOrd_Verz/$urh/09/".$ao_ssg.$verz;

                $p = $d_p.$neu[$p_a['bi']] ;

                if (!is_file($p)) {
                    $p = $p;
                }
            } else {
                $p = $uploaddir . $neu[$p_a['bi']];
            }

            #$f_arr = pathinfo($neu[$p_a['bi']]);
            if ($f_a['extension'] == "pdf") {
                echo "<a href='$p' target='Bild $j' > Dokument</a>";
            } else {
                echo "<a href='$p' target='Bild $j' > <img src='$p' alter='$p' width='200px'></a>";
                echo $neu[$p_a['bi']];
            }

        } else {
            echo "kein Bild hochgeladen";
        }

        echo "</div>";
        ?>
        
        <div id="gruppe2" class="foto-upd-container" 
            style="<?php echo ($hide_area_group2 == 1) ? 'display:none;' : ''; ?>">
           <div class='foto-upd'  style='margin-bottom:20px; border:1px solid #ccc; padding:10px;'> 
          
            <h3>Bild <?php echo $j; ?></h3>
            <input type='hidden' id='foto_<?php echo $j; ?>' name='foto_<?php echo $j; ?>' value=''>

            <!-- Hochladen Bereich -->
            <div id='upl_new_<?php echo $j; ?>' margin-top:10px;'>
                <input type='file' name='datei_<?php echo $j; ?>' />
            </div>
          </div>   
        </div>

        <?php

        echo "</fieldset>";
        echo "</div>";
    }

    echo "</fieldset>";
    echo "</div>";  // Responsive Block end
    echo "</div>";        // end container

} // end VF_Upload_Form_M

/**
 * Hochladen von Dateien
 *
 * Bei allen Dateien:  ändern Umlaute auf alte Schreibweise Ä -> AE
 * Bei Grafischen Dateien: wenn Urheber-Abkürzung und Foto-Datum vorhanden, Umbenennen nach Foto-Vorgabe (Urh-Datum-Dateiname)
 *
 *
 * @param string $uploaddir      Zielverzeichnis
 * @param string $i              index zur uploadfile $files[uploadfile_x
 * @param string $urh_abk        Abkürzung des Urhebernamens
 * @param string $fo_aufn_datum  Aufnahmedatum
 * @return string Dsn der Datei  Name der Datei zum Eintrag in Tabelle
 */
function VF_Upload_Save_M($uploaddir, $fdsn, $urh_nr = "", $md_aufn_datum = "")
{
    global $debug, $module, $flow_list;

    flow_add($module, "VF_Comm_Funcs.inc Funct: VF_Upload_Save_M");
    console_log('uploadsave');
    if ($md_aufn_datum != "") {
        $md_aufn_datum_n = "$md_aufn_datum/";
    }
    #echo " L 02704 Upl upldir $uploaddir fdsn $fdsn <br>";
    # var_dump($_FILES[$fdsn]);
    $target = "";
    if ($_FILES[$fdsn]['name'] != "") {

        if ($_FILES[$fdsn]['error'] >= 1) {
            $errno = $_FILES[$fdsn]['error'];
            $err = "Upload Fehler: ";
            switch ($errno) {
                case 1:
                case 2:
                    $err .= "Err: Datei zu groß";
                    break;
                case 8:
                    $err .= "Err: Falsche Datei (Erweiterung)";
                    break;
            }
            return $err;
        }

        $f_a = pathinfo($_FILES[$fdsn]['name']);
        # var_dump($f_a);
        $target = $f_a['basename'];
        #echo "L 2755 uploaddir $uploaddir <br>";
        if (stripos($uploaddir, '09/') >= 1) {
            $ext = strtolower($f_a['extension']);
            $ao_ssg = "";
            if (in_array($ext, AudioFiles)) {
                $ao_ssg = "02/";
            }
            if (in_array($ext, GrafFiles)) {
                $ao_ssg = "06/";
            }
            if (in_array($ext, VideoFiles)) {
                $ao_ssg = "10/";
            }
            $uploaddir .= $ao_ssg.$md_aufn_datum_n;
        }
        # echo "L 02687 uploaddir $uploaddir <br>";

        if (! file_exists($uploaddir)) {
            mkdir($uploaddir, 0770, true);
        }

        if ($target != "") {
            $target = VF_trans_2_separate($target);

            $fn_arr = pathinfo($target);
            $ft = strtolower($fn_arr['extension']);
            #var_dump($fn_arr);
            if (in_array($ft, GrafFiles) && $urh_nr != "" && $md_aufn_datum != "") {
                $newfn_arr = explode('-', $target);
                $cnt = count($newfn_arr);
                if ($cnt == 1) { # original- Dateiname, nicht im Format urh-datum-Aufn_dateiname.ext,
                    $target = "$urh_nr-$md_aufn_datum-" . $fn_arr['basename'];
                }
            } else {
                $target = $fn_arr['basename'];
            }
            # echo "L 02658 fdsn $fdsn ; uploaddir $uploaddir; target $target <br>";
            # var_dump($_FILES[$fdsn]);
            if (move_uploaded_file($_FILES[$fdsn]['tmp_name'], $uploaddir . $target)) {
                # var_dump($_FILES[$fdsn]);
                # echo "L 02745 target $target <bR>";
                return $target;
            }
        }
    }

} // end VF_Upload_Save_M


/**
 * Ende der Bibliothek
 *
 * @author Josef Rohowsky - 20250616
 */
?>
