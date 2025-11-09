<?php
/**
 * Benutzervrwaltung, Liste
 *
 * @author Josef Rohowsky - neu 2018
 *
 */
session_start(); # die SESSION aktivieren   

$module  = 'MVW';
$sub_mod = 'Bez';

$tabelle = 'fh_benutzer'; 

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT          = "../";

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$_SESSION[$module]['Inc_Arr']  = array();
$_SESSION[$module]['Inc_Arr'][] = "VF_Z_B_List.php";

$debug = True;  $debug = False;  // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';

$flow_list = False;

$LinkDB_database  = '';
$db = LinkDB('VFH'); 

VF_chk_valid();
VF_set_module_p();

VF_Count_add();

$mitgl_nrs = "";
$mitgl_einv_n = 0;

if (isset($_POST['select_string'])) {$select_string = $_POST['select_string'];} else {$select_string = "";}
$_SESSION['VF']['$select_string'] = $select_string;

/**
 * Aussehen der Listen, Default-Werte, Änderbar (VF_List_Funcs.inc)
 *
 * @global array $_SESSION['VF_LISTE']
 *   - select_string
 *   - DropdownAnzeige        Ein: Anzeige Dropdown Menu
 *   - LangListe              Ein: Liste zum Drucken
 *   - VarTableHight          Ein: Tabllenhöhe entsprechend der Satzanzahl
 *   - CSVDatei               Ein: CSV Datei ausgeben
 */
if (!isset($_SESSION['VF_LISTE'])) {
    $_SESSION['VF_LISTE']    = array(
        "select_string"       => "",
        "DropdownAnzeige"     => "Ein",
        "LangListe"           => "Ein",
        "VarTableHight"       => "Ein",
        "CSVDatei"            => "Aus"
    );
}
# ===========================================================================================================
#                                            Haeder ausgeben
# ===========================================================================================================
$title = "Benutzer ";

$logo = 'NEIN';
BA_HTML_header('Benutzer- und Zugriffs- Verwaltung','','Admin','150em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

echo "<fieldset>";

initial_debug(); 
  
# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
# ===========================================================================================
$T_list_texte  = array("Alle" =>"Alle Benutzer ( Auswahl ) "
);

  $NeuRec = " &nbsp; &nbsp; &nbsp; &nbsp; <a href='VF_Z_B_Edit.php?ID=0' >Neuen Benutzer eingeben</a>";
  List_Prolog($module,$T_list_texte); #  Paramerter einlesen und die Listen Auswahl anzeigen

  $Tabellen_Spalten = Tabellen_Spalten_parms($db,$tabelle);
 
  switch($T_List)
  { 
    case "Alle"      : 
 
          /*
          $Tabellen_Spalten =  array('be_id','be_mitglnr','be_staat','be_bdld','be_bezirk','be_orgtyp','be_org_name','kont_name','be_fwkz','be_grgjahrmi_ort'
              ,'mi_gebtag','mi_tel_handy','mi_handy','mi_fax','mi_email','mi_email_status','mi_ref_leit','mi_ref_ma','mi_ref_int','mi_sterbdat'
              ,'mi_austrdat','mi_einv_art','mi_einversterkl','mi_einv_dat','mi_uidaend','mi_aenddat'
              );
      break;
      */
      case "AdrList":
          $Tabellen_Spalten =  array('be_id','be_org_typ','be_org_name','kont_name','be_anrede','be_titel','be_name','be_vname','be_anschr','be_plz','be_ort'
              );
          break;
 

      default    :            
          $Tabellen_Spalten =  array('be_id','be_org_typ','be_org_name','kont_name','be_anrede','be_titel','be_name','be_vname','be_anschr','be_plz','be_ort'
              );
          break;
 
   }
   
  $Tabellen_Spalten_style['be_id'     ] = 

  $Tabellen_Spalten_style['be_mitglnr'  ] =
  $Tabellen_Spalten_style['va_begzt'  ] ='text-align:center;';
 
  
  $List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar'
                 . '<ul style="margin:0 1em 0em 1em;padding:0;">'
                 . '<li>Mitglieder Daten ändern: Auf die Zahl in Spalte <q>be_id</q> Klicken.</li>';
  switch($T_List)
  { 
      case "Alle":

          
      case "AdrList":
          break;

    default        :
         
  }
  $List_Hinweise .= '</ul></li>';

  List_Action_Bar($tabelle,"Benutzer- Verwaltung - Administrator "
      ,$T_list_texte,$T_List,$List_Hinweise); # Action Bar ausgeben
  
# ===========================================================================================================
#  Je nach ausgewähltem Radio Button das sql SELECT festlegen
# ===========================================================================================================
  $act_datum = date("Y-m-d");
  $sql_where = $order_By = "";
  $sql = "SELECT * FROM $tabelle "; 
  switch($T_List)
  { 
      case "Alle"     : $sql_where=" WHERE be_name!='' ";   $orderBy = ' ORDER BY be_id ';                              break;
  #    case "AdrList"  : $sql_where=" WHERE ((be_sterbdat<='0000-00-00' AND be_abgdat<='0000-00-00') OR (be_sterbdat IS NOT NULL AND be_abgdat IS NULL) ) ";    $orderBy = ' ORDER BY be_name';          break;

  }
    
  if ($select_string<>'' )
  { switch($T_List)
    { 
    
      default        : $sql_where .= " AND (be_org_name LIKE '%$select_string%' OR be_name LIKE '%$select_string%' )";  
    }
  } 
   $sql .= $sql_where.$order_By;
  
   
   echo "<div class='toggle-SqlDisp'>";
   echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>Z B List vor list_create $sql </pre>";
   echo "</div>";
   
# ===========================================================================================================
#  Die Daten lesen und Ausgeben
# ===========================================================================================================

List_Create($db,$sql,'',$tabelle,''); # die liste ausgeben

echo "</fieldset>";

BA_HTML_trailer();

/**
 * Diese Funktion verändert die Zellen- Inhalte für die Anzeige in der Liste
 *
 * Funktion wird vom List_Funcs einmal pro Datensatz aufgerufen.
 * Die Felder die Funktioen auslösen sollen oder anders angezeigt werden sollen, werden hier entsprechend geändert
 *
 *
 * @param array $row
 * @param string $tabelle
 * @return boolean immer true
 *
 * @global string $path2ROOT String zur root-Angleichung für relative Adressierung
 * @global string $T_List Auswahl der Listen- Art
 * @global string $module Modul-Name für $_SESSION[$module] - Parameter
 */
function modifyRow(array &$row,$tabelle)
{ global $path2ROOT,$T_List;


$defjahr = date("y");   // Beitragsjahr, ist Gegenwärtiges Jahr

$be_id = $row['be_id'];
$row['be_id'] = "<a href='VF_Z_B_Edit.php?ID=$be_id'>$be_id</a>";

# echo "M_List L 083: \$row['mi_einversterkl'] ".$row['mi_einversterkl']." \$row['mi_id'] ".$row['mi_id']." <br/>";


return True;
} # Ende von Function modifyRow

?>