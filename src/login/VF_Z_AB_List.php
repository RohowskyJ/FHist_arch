<?php
/**
 * Abkürzungen bei der Feuerwehr, Liste
 *
 * @author Josef Rohowsky - neu 2018
 *
 */
session_start(); # die SESSION aktivieren  
const Module_Name   = 'F_M';
$module             = Module_Name;
$tabelle = 'fh_abk'; 

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT          = "../";

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
 *   - SelectAnzeige          Ein: Anzeige der SQL- Anforderung
 *   - SpaltenNamenAnzeige    Ein: Anzeige der Apsltennamen
 *   - DropdownAnzeige        Ein: Anzeige Dropdown Menu
 *   - LangListe              Ein: Liste zum Drucken
 *   - VarTableHight          Ein: Tabllenhöhe entsprechend der Satzanzahl
 *   - CSVDatei               Ein: CSV Datei ausgeben
 */
if (!isset($_SESSION['VF_LISTE'])) {
    $_SESSION['VF_LISTE']    = array(
        "select_string"       => "",
        "SelectAnzeige"       => "Aus",
        "SpaltenNamenAnzeige" => "Aus",
        "DropdownAnzeige"     => "Ein",
        "LangListe"           => "Ein",
        "VarTableHight"       => "Ein",
        "CSVDatei"            => "Aus"
    );
}
initial_debug(); 
  
# ===========================================================================================
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
# ===========================================================================================
$T_list_texte  = array("Alle" =>"Alle Benutzer ( Auswahl ) "
    ,'MA_F' => 'Motorisierte Fahrzeuge'
    ,'MA_G' => ' Motorisierte Geräte'
    ,'MU_F' => 'Muskelgezogene Fahrzeuge'
    ,'MU_G' => 'Muskelbetriebene Geräte'
    ,"NeuItem" =>"<a href='VF_Z_AB_Edit.php?ID=0' >Neuen Abkürzung eingeben</a>"
);

# ===========================================================================================================
#                                            Haeder ausgeben
# ===========================================================================================================
  $title = "Benutzer ";
  
  $logo = 'NEIN';
  BA_HTML_header('Abkürzungen','','Admin','150em'); # Parm: Titel,Subtitel,HeaderLine,Type,width 
  
  echo "<fieldset>";

  List_Prolog($module,$T_list_texte); #  Paramerter einlesen und die Listen Auswahl anzeigen

  $Tabellen_Spalten = Tabellen_Spalten_parms($db,$tabelle);
 
  switch($T_List)
  { 
    case "Alle"      : 
        $Tabellen_Spalten =  array('ab_id','ab_grp','ab_abk','ab_bezeichn','ab_gruppe'
            );
        break;
      default    :            
          $Tabellen_Spalten =  array('ab_id','ab_grp','ab_abk','ab_bezeichn','ab_gruppe'
              );
          break;
 
   }
   
  $Tabellen_Spalten_style['be_id'     ] = 

  $Tabellen_Spalten_style['be_mitglnr'  ] =
  $Tabellen_Spalten_style['va_begzt'  ] ='text-align:center;';
 
  
  $List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar'
                 . '<ul style="margin:0 1em 0em 1em;padding:0;">'
                 . '<li>Mitglieder Daten ändern: Auf die Zahl in Spalte <q>be_id</q> 
dd.</li>';
  switch($T_List)
  { 
      case "Alle":

          
      case "AdrList":
          break;

    default        :
         
  }
  $List_Hinweise .= '</ul></li>';

  List_Action_Bar($tabelle,"Abkürzungen "
      ,$T_list_texte,$T_List,$List_Hinweise); # Action Bar ausgeben
  
# ===========================================================================================================
#  Je nach ausgewähltem Radio Button das sql SELECT festlegen
# ===========================================================================================================
  $act_datum = date("Y-m-d");
  $sql_where = "";
  $order_By = ' ORDER BY ab_abk ';
  $sql = "SELECT * FROM $tabelle "; 
  switch($T_List)
  { 
      case "Alle"     : 
          $sql_where=" WHERE ab_abk!='' ";     
          break;

      case 'Ma_F':
          $sql_where=" WHERE ab_grp LIKE '%MA_F%' ";   $orderBy = ' ORDER BY ab_abkd ';
          break;
      case 'Ma_G':
          $sql_where=" WHERE ab_grp LIKE '%MA_G%' ";   $orderBy = ' ORDER BY ab_abkd ';
          break;
      case 'Mu_F':
          $sql_where=" WHERE ab_grp LIKE '%MU_F%' ";   $orderBy = ' ORDER BY ab_abkd ';
          break;
      case 'Mu_G':
          $sql_where=" WHERE ab_grp LIKE '%MU_G%' ";   $orderBy = ' ORDER BY ab_abkd ';
          break;
  }
 
    
  if ($select_string<>'' )
  { switch($T_List)
    { 
    
      # default        : $sql_where .= " AND (be_org_name LIKE '%$select_string%' OR be_name LIKE '%$select_string%' )";  
    }
  } 
  
   $sql .= $sql_where.$order_By;
  

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

$ab_id = $row['ab_id'];
$row['ab_id'] = "<a href='VF_Z_AB_Edit.php?ID=$ab_id'>$ab_id</a>";

# echo "M_List L 083: \$row['mi_einversterkl'] ".$row['mi_einversterkl']." \$row['mi_id'] ".$row['mi_id']." <br/>";


return True;
} # Ende von Function modifyRow

?>