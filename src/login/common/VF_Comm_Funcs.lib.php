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
 *  - VF_Displ_Arl      - Anzeige Archivordnung -6. Ebene Locale Sachgeb + Subsachgeb + 2 erw und Sammlung
 *  - VF_Displ_Aro      - Anzeige Archivordnug, 1+2. Ebene Generelles Sachgebiet und Sub-Sachgebiet
 *  - VF_Displ_Eig      - Daten zur Anzeige der Eigentümer-Daten, Speichern in SESSION[Eigner [
 *  - VF_Displ_Suchb    - Suchbegriffe für Anzeige einlesen - VF_Displ_Suchb    - Suchbegriffe für Anzeige einlesen
 *  - VF_Displ_Urheb    - Urheber Daten in $_Sess[$module]['Fo']['URHEBER'] einlesen  fh_urheber_n/fh_urh_erw_n
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
 *  - VF_Urh_ini        - Auswahl der config_u.ini - Urheber- Nr. - Name refrenz, erstellung bei start von Foto, Bericht. MaFG
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
 *  - VF_Upload_Form_M  - Upload (Single- File- Multiple- upload von Audio, Foto und Video Files
 */
global $debug;
if ($debug) {
   # echo "L 042: VF_Common_Funcs.inc.php ist geladen. <br/>";
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
    # print_r($find_arr);
    # echo "<br> find_arr <br>";
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
               '$date','$p_uid','$module','','',''
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
        echo "<pre class=debug>Displ_Aera L Beg:  \$sub_funct $sub_funct<pre>";
    }

    $sql_fa = "SELECT * FROM `fz_aera` WHERE `ar_id`='$fz_area' ";

    $return_fa = SQL_QUERY($db, $sql_fa);

    while ($row = mysqli_fetch_object($return_fa)) {
        $text = $row->ar_text;
    }

    mysqli_free_result($return_fa);

    if ($debug) {
        echo "<pre class=debug>F Security_sel L End:<pre>";
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
function VF_Displ_Arl($sg, $ssg, $lcsg, $lcssg,$lcssg_s0, $lcssg_s1)
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
    #if ($lcssg != "" && $lcssg != "0") {
        $lcssg_val =  " AND `al_lcssg` = '$lcssg' AND al_lcssg_s0 = '$lcssg_s0' AND al_lcssg_s1 = '$lcssg_s1' ";
    #}
    $sql_al = "SELECT * FROM $table WHERE   `al_sg`='$sg.$ssg' AND `al_lcsg` = '$lcsg' $lcssg_val ORDER BY al_sg ASC";
    $return_al = mysqli_query($db, $sql_al); // or die("Datenbankabfrage gescheitert. ".mysqli_error($db));
    # print_r($return_al);echo "<br> L 424: return \$sql_al $sql_al <br>";
    if ($return_al) {
        while ($row = mysqli_fetch_object($return_al)) {
            # var_dump($row);
            $string_arl = "$row->al_lcsg|$row->al_lcssg|$row->al_lcssg_s0|$row->al_lcssg_s1|$row->al_sammlung|$row->al_bezeich";
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
function VF_Displ_Eig($eigentmr, $id='E')
{
    global $debug, $db, $module, $sub_mod, $flow_list;

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
        if ($id == 'E') {
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
        } else {
            $_SESSION[$module][$sub_mod]['eig_eigner'] = $eigentmr;
            $_SESSION[$module][$sub_mod]['eig_org'] = $leihname;
            $_SESSION[$module][$sub_mod]['eig_name'] = $leihname;
            $_SESSION[$module][$sub_mod]['eig_verant'] = "$row->ei_titel $row->ei_vname $row->ei_name $row->ei_dgr ";
            
            if ($row->ei_org_typ == "Privat") {
                $_SESSION[$module][$sub_mod]['eig_vopriv'] = $row->ei_vopriv;
                $_SESSION[$module][$sub_mod]['eig_verant'] = "";
                $_SESSION[$module][$sub_mod]['eig_staat'] = "";
                $_SESSION[$module][$sub_mod]['eig_adresse'] = "";
                $_SESSION[$module][$sub_mod]['eig_ort'] = "";
                $_SESSION[$module][$sub_mod]['eig_name'] = "$row->ei_titel $row->ei_vname $row->ei_name $row->ei_dgr ";
                $_SESSION[$module][$sub_mod]['eig_urhname'] = "$row->ei_vname $row->ei_name ";
            } else {
                $_SESSION[$module][$sub_mod]['eig_vopriv'] = $row->ei_vopriv;
                $_SESSION[$module][$sub_mod]['eig_verant'] = "$row->ei_titel $row->ei_vname $row->ei_name $row->ei_dgr ";
                $_SESSION[$module][$sub_mod]['eig_staat'] = $row->ei_staat;
                $_SESSION[$module][$sub_mod]['eig_adresse'] = $row->ei_adresse;
                $_SESSION[$module][$sub_mod]['eig_ort'] = $row->ei_plz . " " . $row->ei_ort;
                $_SESSION[$module][$sub_mod]['eig_urhname'] = $leihname;
            }
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
function VF_Displ_Urheb($urhebernr)
// --------------------------------------------------------------------------------
{
    global $debug, $db, $module, $flow_list;

    flow_add($module, "VF_Comm_Funcs.inc.php Funct: VF_Displ_Urheb_n");

    if ($debug) {
        echo "<pre class=debug>VF Displ_Urheb_n L 687 Beg: \$urhebernr $urhebernr fm_typ $fm_typ <pre>";
    }

    $sql = "SELECT * FROM `fh_eigentuemer` WHERE `ei_id`='$urhebernr' ";

    $return_ur = SQL_QUERY($db, $sql);

    while ($row = mysqli_fetch_object($return_ur)) {

        $_SESSION[$module]['Urheber']['ei_id'] = $row->ei_id;

        if ($row->ei_org_typ == "") {
            $urheber = $row->name ." .$row->ei_vname";
        } else {
            $urheber = $row->ei_org_typ ." ". $row->ei_org_name;
        }
        $_SESSION[$module]['Urheber'][$row->ei_id] = array( 'urh_name'=> $urheber,'urh_Kennz'=>$row->ei_urh_kurzz,'urh_Media'=>$row->ei_media);
    }

    mysqli_free_result($return_ur);
}

// Ende von function VF_Displ_Urheb

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
    echo "</fieldset></div>";
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

    echo "<div class='w3-container' max-width='100%' margin='5px '>";

    # var_dump($_SESSION[$module]['Pct_Arr']);

    foreach ($_SESSION[$module]['Pct_Arr'] as $key => $p_a) { # => $value) {
        # var_dump($p_a);
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
    #echo "</div>";
    # echo "</div>";

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
        echo "<pre class=debug>VF_Sel_Det L Beg: \$sammlg $sammlg <pre>";
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
function VF_Urh_ini()
{
    global $debug, $db, $LinkDB_database, $module, $flow_list, $path2ROOT;

    flow_add($module, "VF_Comm_Funcs.inc.php Funct: VF_Sel_Urheber_n");

    if ($debug) {
        echo "<pre class=debug>Urheber-ini L Beg:  <pre>";
    }
 /*   
#echo "L 1393 $LinkDB_database <br>";
    $ar_arr = $dm_arr =  $in_arr = $maf_arr = $mag_arr  = $muf_arr  = $mug_arr  = $ge_arr = $zt_arr = array();
    $tables_act = VF_tableExist(); 
    #var_dump($dm_arr);
    #var_dump($tables_act);
    if (!$tables_act) {
        echo " Fehler beim lesen der DB Tabellen <br>";
        exit;
    }
    
    var_dump($dm_arr);
*/
    $sql_ur = "SELECT * FROM `fh_eigentuemer` WHERE ei_urh_kurzz <> '' ORDER BY ei_id ASC "; # WHERE ei_urh_kurzz != '' ORDER BY ei_id ASC

    $return_ur = SQL_QUERY($db, $sql_ur);
    
    $fotogr = array();
    while ($row = mysqli_fetch_object($return_ur)) {
        # print_r($row);echo "<br>L 01409 <br>";
        #if (in_array($row->ei_id,$dm_arr)) {
            if ($row->ei_org_typ == 'Privat') {
                $fotogr[$row->ei_id]= $row->ei_name ." ". $row->ei_vname;
            } else {
                $fotogr[$row->ei_id] = $row->ei_org_typ ." ".$row->ei_org_name;
            }
       # }
        
    }

    $stream = fopen('common/config_u.ini', 'w');
    $res = fwrite($stream, "\n[Urheber] \n");
    
    foreach ($fotogr as $key => $value) {
        $res  = fwrite($stream, " $key  = $value \n");
    }
    $res = fclose($stream);

}

# ende VF_urh_ini


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
;
    $error = True;

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
            return false;
        }
    } else {
        
        return false;
    }

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
        # var_dump($dm_arr);
        return true;
    
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
            echo "L 01645 fdsn $fdsn ; uploaddir $uploaddir; target $target <br>";
            var_dump($_FILES[$fdsn]);
            if (move_uploaded_file($_FILES[$fdsn]['tmp_name'], $uploaddir . $target)) {
                var_dump($_FILES[$fdsn]);
                return $target;
            }
        }
    }
    return false;
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
                if ($cnt == 1 && $urh_abk != "" && $fo_aufn_datum !=  "") { # original- Dateiname, nicht im Format urh-datum-Aufn_dateiname.ext,
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
            } else {
                echo "<input type='hidden' id='level5' value=''>";
                echo "<input type='hidden' id='level6' value=''>";
            }
        } else {
            echo "<input type='hidden' id='level4' value=''>";
            echo "<input type='hidden' id='level5' value=''>";
            echo "<input type='hidden' id='level6' value=''>";
        }
    } else {
        echo "<input type='hidden' id='level3' value=''>";
        echo "<input type='hidden' id='level4' value=''>";
        echo "<input type='hidden' id='level5' value=''>";
        echo "<input type='hidden' id='level6' value=''>";
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
 * AuswahlListe der Eigentümer für select Liste
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
    flow_add($module, "VF_Comm_Funcs.lib.php Funct: VF_Auto_Aufbau");
    ?>
    <div class='w3-container' style='background-color: PeachPuff '> <!--   -->
        <b>Suchbegriff für Aufbau- Hersteller eingeben:</b>  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <input type="text" class="autocomplete" data-proc="Aufbauer" data-target="suggestAufbauer" data-feed="aufbauer" size='50'/>
    </div>  
    <div id="suggestAufbauer" class="suggestions">
       <input type="hidden" name="aufbauer" id="aufbauer" />
    </div>
    <?php
} // Ende VF_Auto_Aufbau


function VF_Auto_Eigent($t, $cl = false,$j="1")
{
    global $debug, $module, $flow_list;
    flow_add($module, "VF_Comm_Funcs.lib.php Funct: VF_Auto_Eigent");
    console_log('autoeigent');
    ?>
    <div class='w3-container' style='background-color: PeachPuff; padding: 10px;'>
    <?php
        if (isset($t) && $t == 'E') {
            echo "<b>Eigentümer Namen suchen:</b> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ";
        } else {
            echo "<b>Urheber Namen suchen:</b> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ";
        }
    ?>
        <input type="text" class="autocomplete" data-proc="Eigentuemer" data-target="suggestEigener_<?php echo $j; ?>" data-feed="eigentuemer_<?php echo $j; ?>" size='50'/>
    </div>
    <div id="suggestEigener_<?php echo $j; ?> class="suggestions">
       <input type="hidden" name="eigentuemer_<?php echo $j; ?>" id="eigentuemer_<?php echo $j; ?>" />
    </div>
    <?php
    if ($cl) {
        echo "<button type='submit' name='phase' value='1' class=green>Weiter</button></p>";
    }
} // Ende VF_Auto_Eigent

// Beispiel: Funktion für das Eingabefeld von gradually

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
    <b>Suchbegriff für Taktische Bezeichnung eingeben:</b>  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <input type="text" class="autocomplete" data-proc="Taktisch" data-target="suggestTaktisch" data-feed="taktisch" size='50' />
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
    #if (isset($mand_mod[$module]))    {
        
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
    
    flow_add($module, "VF_Upload.lib.php Funct: VF_M_Foto");
    
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
        echo "<pre class=debug>VF_Upload_Form_M L 2262 Beg: \$Picts ";
        var_dump($_SESSION[$module]['Pct_Arr']);
        echo "<pre>";
    }
    
    $pic_cnt = count($_SESSION[$module]['Pct_Arr']);

    /**
     * Floating Block mit Bild, Bildbeschreibung , Bildname und Upload-Block
     */
    echo "<div class='w3-container'>";                           // container für Foto und Beschreibung
    #console_log('L 056 vor class w3-row ');
    echo "<div class = 'w3-row w3-border'>";                     // Responsive Block start
    echo "<fieldset>";  #1
  
    ?>

  <div style="margin-bottom:20px; border:1px solid #ccc; padding:10px;">

    <?php
    echo "<input type='text' id='berPhase' value='init' >";
    echo "<input type='hidden' name='pic_cnt' value='$pic_cnt' >";
    for ($i = 0; $i < $pic_cnt; $i++) {
        $p_a = $_SESSION[$module]['Pct_Arr'][$i];

        // Entscheidung, ob versteckt wird bei bestehendem Daten
        $hide_upl = $hide_bild = '';
        if ($hide_area == 1) {
            if (empty($neu[$p_a['ko']]) && empty($neu[$p_a['bi']])) {
                $hide_upl = $hide_bild = 'hide'; // wird versteckt
            }
        }

        $j = $i + 1; /** Für die Bil- Nr- Anzeige */

        $pict_path = VF_Upload_Pfad_M('', '', '', '');
       
        # echo "L 0129 pct_path $pict_path <br>";
        /**
         * Responsive Container innerhalb des loops
         */
        echo "<div class = 'block-container w3-container w3-half ' data-index='$i'  data-hide-area='$hide_area'>";                 // start half contailer
        echo "<fieldset>";
        echo "Bild $j <br>";
        echo "<div class='bild-data_$j' >";

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
           
            $fo_arr = explode("-", $neu[$p_a['bi']]);
            $cnt_fo = count($fo_arr);

            if ($cnt_fo >= 3) {   // URH-Verz- Struktur de dsn
                $urh = $fo_arr[0];
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

            $f_arr = pathinfo($neu[$p_a['bi']]);
            if ($f_arr['extension'] == "pdf") {
                echo "<a href='$p' target='Bild $j' > Dokument</a>";
            } else {
                echo "<a href='$p' target='Bild $j' > <img src='$p' alter='$p' height='200px'></a><br>";
                echo $neu[$p_a['bi']];
            }

        } else {
            echo "kein Bild hochgeladen";
        }
        
        ?>  
        <!-- Bereich für die diversen Ausgaben von js  -->
        <input type="hidden" id="bild-datei-auswahl_<?php echo $j ?>" name="bild_datei_<?php echo $j ?>" value="" />
        
        <!-- Bereich, um die ausgewählten Bildinfos anzuzeigen (immer im DOM) -->
        <div id="auswahl-bild_<?php echo $j ?>" style="display:none;">  
        <h3>Neu gewähltes Bild:</h3>
        <div id="bild-vorschau-auswahl_<?php echo $j ?>"></div>
              <p>Dateiname: <span id="dateiname-auswahl_<?php echo $j ?>"></span></p>
        </div>

        <!-- Galerie-Container für die Bildauswahl -->
        <div id="bild-galerie_<?php echo $j; ?>" style="display:none; border:1px solid #ccc; padding:10px;"></div>

        <!-- Dialog für die Bilder-Auswahl (separater Dialog, eigene IDs) -->
        <div id="dialog-bilder_<?php echo $j; ?>" style="display:none;">
        <div id="bild-vorschau-dialog_<?php echo $j; ?>"></div>
        <div id="dateiname-dialog_<?php echo $j; ?>"></div>
        <input type="hidden" id="bild-datei-dialog_<?php echo $j; ?>">
        </div>
        <hr>
        <?php 
        echo "</div>"; // Bild detail end

        ?>
        
        <div id="gruppe2" class="foto-upd-container" 
            style="<?php echo ($hide_area_group2 == 1) ? 'display:none;' : ''; ?>">
           <div class='foto-upd'  style='margin-bottom:20px; border:1px solid #ccc; padding:10px;'> 
          
           <!-- Upload Parameter Gruppe -->
                
           <h3>Bild <?php echo $j; ?></h3>

           <?php
           if ($module != 'OEF') {
           ?>
             
                <?php 
                #if ($_SERVER['SERVER_NAME'] == 'localhost' ) {
                   ?> 
                   <!-- Radio Buttons für die Auswahl  -->
                   <label>
                       <input type="radio" name="sel_libs_<?php echo $j; ?>" id="sel_libs_ja<?php echo $j; ?>" value="ja"> aus Bibliothek auswählen
                   </label>
                   <?php 
                #}
           }
           ?>
           <label>
               <input type="radio" name="sel_libs_<?php echo $j; ?>" id="sel_libs_nein<?php echo $j; ?>" value="nein"> neu Hochladen
           </label>
       
           <!-- Bibliothekssuche Gruppe -->
           <div id='sel_lib_suche' style='display:none;'>
                 <!-- Inhalte für die Bibliothekssuche -->
                 <!-- (Preview area moved out) -->
                 
           </div>

            <div id="sel_lib_upload<?php echo $j; ?>" style="display:none;">
    
                 <?php
                 if ($module != 'OEF') {
                     VF_Auto_Eigent('U', false,$j);
                     
                     Edit_Separator_Zeile('Aufnahme- Datum (Ziel- Pfad der Bilder erweitern mit Anhang möglich)');
                     
                     echo "<div class='w3-row'style='background-color:#eff9ff'>"; // Beginn der Einheit Ausgabe
                     echo "<div class='w3-third   ' >";
                     echo "<label for='aufnDat'>Aufnahme- Datum (Haupt- Pfad)</label>";
                     echo "  </div>";  // Ende Feldname
                     echo "  <div class='w3-twothird  ' >"; // Beginn Inhalt- Spalte
                     echo "<input type='text' id='aufnDat_$j' name='aufn_dat_$j'  />  YYYYmmDD Format oder Jahreszahl"; // 
                     echo "</div>";
                     echo "</div>"; // ende Ausgabe- Einhait
                 }
                 
                 echo "<div id='$j'></div>";
                 #echo "<button id='$j'  class='button-sm'>Hochladen</button>";

                 echo "<div class='w3-row'>"; // Beginn der Einheit Ausgabe
                 echo "<div class='w3-third   ' >";
                 echo "<label for='urhEinfg'>Urheber ins Bild einfügen</label>";
                 echo "  </div>";  // Ende Feldname
                 echo "  <div class='w3-twothird ' >"; // Beginn Inhalt- Spalte
                 echo "<input type='radio' name='urheinfueg_$j' id='urhEinfgJa_$j' value='J' checked='checked' ><label for='urheinfgJa_$j'>Ja</label><br>";     // für Fotos
                 echo "<input type='radio' name='urheinfueg_$j' id='urhEinfgNein_$j' value='N'       ><label for='urheinfgNein_$j'>Nein</label><br>";
                 # echo "<input type='hidden' name='urhName' id='urhName' value='' >";     // für Fotos
                 echo "<input type='hidden' name='reSize' id='reSize' value='800' >";         // default size max 800x 800 pixel  für Fotos 
                 echo "</div>";
                 echo "</div>"; // ende foload
                 ?>
                 <br><input type="file" id="upload_file_<?php echo $j; ?>">
             </div>
        <?php

        echo "</fieldset>";
        echo "</div>";
    }

    echo "</fieldset>";
    echo "</div>";  // Responsive Block end
    echo "</div>";        // end container

    ?>
<script>
var bilder = {}; // globale Variable für die Bilderliste

// Funktion zum Umschalten zwischen Bibliothek und Upload --- sollte nicht mehr nätig sein
function toggleGruppen(biNr) {
    console.log('toggle Gruppen');
    const selLibsYes = $('#sel_libs_ja' + biNr);
    const selLibsNo = $('#sel_libs_nein' + biNr);
    const groupSearch = $('#sel_lib_suche' + biNr);
    const groupUpload = $('#sel_lib_upload' + biNr);
    console.log('grupl ',groupUpload);
     
    const auswahlBild = $('#auswahl-bild_' + biNr);

    if (selLibsYes.is(':checked')) {
        groupSearch.show();
        groupUpload.hide();
        auswahlBild.show();
        startAjax(biNr);
    } else if (selLibsNo.is(':checked')) {
        groupSearch.hide();
        groupUpload.show();
        auswahlBild.hide();
    }
}

// Funktion, um Bilder basierend auf der Auswahl zu laden
function startAjax(biNr) {
    // Beispiel: Daten sammeln
    var sammlg = $('#sammlung').val().trim();
    var eigner = $('#eigner').val();
    var aufnDat =$('#aufnDat').val();
    console.log('Sammlg ',sammlg);
    console.log('Eigner ',eigner);
    console.log('AufnDatum ',aufnDat);

    // Level-Filter (hast du ggf. in deiner Seite)
    var level1 = $('#level1').val() || '';
    var level2 = $('#level2').val() || '';
    var level3 = $('#level3').val() || '';
    var level4 = $('#level4').val() || '';
    var level5 = $('#level5').val() || '';
    var level6 = $('#level6').val() || '';

    // Auswahl anhand der Level
    if (level6 && level6.toLowerCase() !== 'nix' && sammlg.length < level6.length) {
        sammlg = level6;
    } else if (level5 && level5.toLowerCase() !== 'nix' && sammlg.length < level5.length) {
        sammlg = level5;
    } else if (level4 && level4.toLowerCase() !== 'nix' && sammlg.length < level4.length) {
        sammlg = level4;
    } else if (level3 && level3.toLowerCase() !== 'nix' && sammlg.length < level3.length) {
        sammlg = level3;    
    } else if (level2 && level2.toLowerCase() !== 'nix' && sammlg.length < level2.length) {
        sammlg = level2;
    }    
console.log('abfr sammlg ',sammlg);

    // AJAX-Anfrage
    $.ajax({
        url: 'common/API/VF_SelPictLib.API.php',
        method: 'POST',
        data: {'sammlg' : sammlg,
               'eigner' : eigner,
               'aufnDat' : aufnDat
         },
        dataType: 'json', 
        success: function(response) {
            console.log('Success ',response);
            bilder[biNr] = response.files;
            // Galerie im Dialog füllen
            var dialog = $('#dialog-bilder_' + biNr);
            var galerieHtml = '<div style="display:flex; flex-wrap:wrap; gap:10px;">';
            for (let i=0; i<response.files.length; i++) {
                let b = response.files[i];
                galerieHtml += `<div class="bild-item" data-index="${i}" style="cursor:pointer; border:1px solid #ccc; padding:5px;">
                        <img src="${b.pfad}" alt="${b.dateiname}" width="200"><br>${b.dateiname}
                    </div>`;
            }
            galerieHtml += '</div>';
            dialog.find('#bild-vorschau-dialog_' + biNr).html(galerieHtml);
            dialog.find('#dateiname-dialog_' + biNr).text('');
            dialog.find('#bild-datei-dialog_' + biNr).val('');
            dialog.find('#bild-nummer-dialog_' + biNr).val(1);
            // Dialog öffnen
            dialog.dialog({ width: 800, modal: true });
        },
        error: function(xhr) {
            alert('Fehler beim Upload: ' + xhr.statusText);
        }
    });
}


// Klick auf Bild in Galerie
$(document).on('click', '[id^=bild-galerie_] .bild-item', function() {
    // This handler is now obsolete, as gallery is only in dialog. No action needed.
});

// Click handler for image selection in dialog gallery
$(document).on('click', '[id^=dialog-bilder_] .bild-item', function() {
    const biNr = $(this).closest('[id^=dialog-bilder_]').attr('id').split('_')[1];
    const index = $(this).data('index');
    if (bilder[biNr]) {
        const bild = bilder[biNr][index];
        // Vorschau im Dialog setzen (optional, or just highlight selection)
        // $('#bild-vorschau-dialog_' + biNr).html(`<img src="${bild.pfad}" width="250">`);
        $('#dateiname-dialog_' + biNr).text(bild.dateiname);
        $('#bild-datei-dialog_' + biNr).val(bild.dateiname);

        // Auch in der Auswahl-Box anzeigen
        $('#bild-vorschau-auswahl_' + biNr).html(`<img src="${bild.pfad}" width="250">`);
        $('#dateiname-auswahl_' + biNr).text(bild.dateiname);
        $('#bild-datei-auswahl_' + biNr).val(bild.dateiname);

        // Zeige den Auswahl-Bereich explizit an (falls versteckt)
        $('#auswahl-bild_' + biNr).show();
        // Optional: Scrolle zum Auswahl-Bereich
        document.getElementById('auswahl-bild_' + biNr).scrollIntoView({behavior: 'smooth', block: 'center'});

        // Dialog schließen
        $('#dialog-bilder_' + biNr).dialog('close');
    }
});

$(document).ready(function() {
    <?php for ($j = 1; $j <= $pic_cnt; $j++): ?>
        // Initiales Umschalten
        toggleGruppen(<?php echo $j; ?>);
        // Event für Radio-Buttons
        $('#sel_libs_ja<?php echo $j; ?>, #sel_libs_nein<?php echo $j; ?>').change(function() {
            toggleGruppen(<?php echo $j; ?>);
        });
    <?php endfor; ?>
});
    
// Reusable upload logic for each upload block
function setupUploadBlock(j) {
    console.log('uploadblock ',j);
    // Make file input accept multiple files
    const fileInput = $('#upload_file_' + j);
    fileInput.attr('multiple', 'multiple');
    // Remove any previous preview/upload UI to avoid duplicates
    $('#preview_' + j).remove();
    $('#upload_btn_' + j).remove();
    $('#filename_' + j).remove();
    $('#rotate_left_' + j).remove();
    $('#rotate_right_' + j).remove();

   // Preview and controls container
    const previewDiv = $('<div id="preview_' + j + '" style="margin:10px 0;"></div>');
    const uploadBtn = $('<button type="button" id="upload_btn_' + j + '" style="color: #cc0000">zum Hochladen der Datei nach dem Ausfüllen anklicken</button>');   
    fileInput.after(previewDiv);
    previewDiv.after(uploadBtn);
    
    // Store selected files and their rotation
    let selectedFiles = [];
    let rotations = [];

    // File input change handler
    fileInput.on('change', function(e) {
        selectedFiles = Array.from(e.target.files);
        rotations = selectedFiles.map(() => 0);
        renderPreviews();
    });

    function renderPreviews() {
        previewDiv.html('');
        if (!selectedFiles.length) return;
        selectedFiles.forEach((file, idx) => {
            const fileRow = $('<div style="margin-bottom:10px;"></div>');
            const filenameInput = $('<input type="text" readonly style="width:200px; margin-right:10px;">').val(file.name);
            const rotateLeftBtn = $('<button type="button" style="margin-right:5px;">⟲</button>');
            const rotateRightBtn = $('<button type="button" style="margin-right:5px;">⟳</button>');
            const imgHolder = $('<span></span>');

            rotateLeftBtn.on('click', function() {
                rotations[idx] = (rotations[idx] - 90) % 360;
                renderImage(file, rotations[idx], imgHolder);
                
            });
            rotateRightBtn.on('click', function() {
                rotations[idx] = (rotations[idx] + 90) % 360;
                renderImage(file, rotations[idx], imgHolder);
            });

            fileRow.append(filenameInput, rotateLeftBtn, rotateRightBtn, imgHolder);
            previewDiv.append(fileRow);
            renderImage(file, rotations[idx], imgHolder);
        });
    }

    function renderImage(file, rotation, imgHolder) {
        if (!file) { imgHolder.html(''); return; }
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = $('<img>').attr('src', e.target.result).css({
                'max-width': '200px',
                'transform': 'rotate(' + rotation + 'deg)'
            });
            imgHolder.html(img);
        };
        reader.readAsDataURL(file);
    }

    // Upload button handler
    // Add error spans if not present
    if ($('#aufnDat_' + j).next('.error-msg').length === 0) {
        $('#aufnDat_' + j).after('<span class="error-msg" style="color:red;display:none;margin-left:8px;"></span>');
    }
    if ($('#eigentuemer_' + j).next('.error-msg').length === 0) {
        $('#eigentuemer_' + j).after('<span class="error-msg" style="color:red;display:none;margin-left:8px;"></span>');
    }

    uploadBtn.on('click', function() {
        let hasError = false;
        // Remove previous error styles/messages
        $('#aufnDat_' + j).removeClass('input-error');
        $('#eigentuemer_' + j).removeClass('input-error');
        $('#aufnDat_' + j).next('.error-msg').hide();
        $('#eigentuemer_' + j).next('.error-msg').hide();

        if (!selectedFiles.length) {
            alert('Bitte wählen Sie mindestens eine Datei aus.');
            return;
        }
        // Collect additional data
        const aufnDat = $('#aufnDat_' + j).val() || ''; 
        const urhEinfg = $('#urhEinfgJa_' + j).val() || 'N';
        
        const urhNr = $('#eigentuemer_' + j).val() || '';
        const urhName = $('#urhName').val() || '';
        const reSize = $('#reSize').val() || '800'; // Default size
        const aord = $('#aord_' + j).val() || '';
        const eigner = $('#eigner_' + j).val() || '';
        let rotation = 0;
        // Validate required fields
        if (urhNr === '') {
            $('#eigentuemer_' + j).addClass('input-error');
            $('#eigentuemer_' + j).next('.error-msg').text('Pflichtfeld!').show();
            hasError = true;
        }
        if (aufnDat === '') {
            $('#aufnDat_' + j).addClass('input-error');
            $('#aufnDat_' + j).next('.error-msg').text('Pflichtfeld!').show();
            hasError = true;
        }
        if (hasError) {
            return;
        }

        const formData = new FormData();
        selectedFiles.forEach((file, idx) => {
            formData.append('file[]', file);
            if(rotations[idx] != 0) {
            rotation = rotations[idx] * -1 ;
            }
            formData.append('rotation', rotation); // []
        });
        formData.append('urhNr', urhNr);
        formData.append('urhName', urhName);
        formData.append('watermark', urhEinfg);
        formData.append('aufnDat', aufnDat);
        formData.append('reSize', reSize);
        formData.append('aord', aord); 
        formData.append('eigner', eigner); 

        console.log('formData ',formData);

        $.ajax({
            url: 'common/API/VF_Upload.API.php',   
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('Upload response typeof:', typeof response, response);
                // If response is a string, try to parse as JSON
                if (typeof response === 'string') {
                    try {
                        response = JSON.parse(response);
                        console.log('Parsed JSON response:', response);
                    } catch(e) {
                        console.log('JSON parse error:', e, response);
                    }
                }
                if (response && response.files) {
                    console.log('Upload response files:', response.files);
                    response.files.forEach(function(fileObj, idx) {
                        var hiddenInput = $("#bild-datei-auswahl_" + j);
                        if (hiddenInput.length) {
                            hiddenInput.val(fileObj.filename);
                        }
                    });
                } else {
                    console.log('No files in response:', response);
                }
                alert('Upload erfolgreich!');
            },
            error: function(xhr) {
                alert('Fehler beim Upload: ' + xhr.statusText);
            }
        });
    });

    // Remove error on input change
    $('#aufnDat_' + j).on('input', function() {
        if ($(this).val() !== '') {
            $(this).removeClass('input-error');
            $(this).next('.error-msg').hide();
        }
    });
    $('#eigentuemer_' + j).on('input', function() {
        if ($(this).val() !== '') {
            $(this).removeClass('input-error');
            $(this).next('.error-msg').hide();
        }
    });

    // Add CSS for error highlight
    if ($('style#input-error-style').length === 0) {
        $('head').append('<style id="input-error-style">.input-error { border: 2px solid red !important; }</style>');
    }
}

// Setup all upload blocks on page load
$(document).ready(function() {        
    console.log('set uploadblocks ', <?php echo $j; ?>);
    <?php for ($j = 1; $j <= $pic_cnt; $j++): ?>
        setupUploadBlock(<?php echo $j; ?>);
    <?php endfor; ?>
});    
</script>
<?php 

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
    return false;
} // end VF_Upload_Save_M


/**
 * Ende der Bibliothek
 *
 * @author Josef Rohowsky - 20250616
 */
?>
