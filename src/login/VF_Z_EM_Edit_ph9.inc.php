<?php

/**
 * Automatische Benachrichtigung für ADMINS bei Änderungen, Auswahl Mitglied
 *
 * @author Josef Rohowsky - neu 2023
 *
 */
if ($debug) {
    echo "<pre class=debug>VF_Z_EM_Edit_ph9.inc.php ist gestarted</pre>";
}

# require $path2ROOT . 'login/common/List_Funcs.inc';

foreach ($_POST as $name => $value) {
    $neu[$name] = $value;
}

$Tabellen_Spalten = Tabellen_Spalten_parms($db, 'fh_mitglieder');

$Tabellen_Spalten = array(
    'mi_id',
    'mi_name',
    'mi_vname',
    'mi_email'
);
if (isset($neu['mi_name'])) {
    $select = "WHERE mi_name LIKE  '%" . $neu['mi_name'] . "%' ";
} else  {
    $select = " WHERE mi_email<>'' AND mi_sterbdat = '' AND mi_austrdat =''";
}
$sql = "SELECT mi_id, mi_name, mi_vname, mi_email FROM fh_mitglieder $select ORDER BY mi_name";

$T_List = "";
$T_list_texte = array(
    "" => ""
);
$List_Hinweise = "";

List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

List_Action_Bar($tabelle,"Auswahl der EMail", $T_list_texte, $T_List, $List_Hinweise); # Action Bar ausgeben

List_Create($db, $sql,'', $tabelle,''); # die liste ausgeben

if ($debug) {
    echo "<pre class=debug>VF_Z_EM_Edit_ph9.inc.php beendet</pre>";
}
?>