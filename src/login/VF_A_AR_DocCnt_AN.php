<?php

/**
 * Ausgabe der anzahl der Dokumente je Archivordnungs- Möglichkeit
 * 
 * @author Josef Rohowsky - neu 2024
 * 
 */
session_start();

const Module_Name = 'ADM';
$module = Module_Name;
if (! isset($tabelle_m)) {
    $tabelle_m = '';
}
$tabelle = "";

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/VF_Comm_Funcs.lib.php';
require $path2ROOT . 'login/common/VF_Const.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';
# require $path2ROOT . 'login/common/BA_Edit_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_List_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Tabellen_Spalten.lib.php';
# require $path2ROOT . 'login/common/VF_F_tab_creat.lib.php';

VF_chk_valid();

BA_HTML_header("Anzahl der Dokumente je Archivordnungs Gruppe",'','Form','70em');

initial_debug();

$LinkDB_database = '';
$db = LinkDB('Mem');

VF_chk_valid();
VF_set_module_p();

if (isset($_GET['ID'])) {
    if ($_GET['ID'] == "NextEig") {
        $_SESSION['Eigner']['eig_eigner'] = "";
        $_SESSION['Eigner']['eig_name'] = "";
        $_SESSION['Eigner']['eig_verant'] = "";
        $_SESSION['Eigner']['eig_staat'] = "";
        $_SESSION['Eigner']['eig_adr'] = "";
        $_SESSION['Eigner']['eig_ort'] = "";
    }
}

if (isset($_POST['select_string'])) {
    $select_string = $_POST['select_string'];
} else {
    $select_string = "";
}

$_SESSION[$module]['$select_string'] = $select_string;

if (isset($_GET['ei_id'])) {
    $_SESSION['Eigner']['eig_eigner'] = $ei_id = $_GET['ei_id'];
} else {
    $ei_id = $_SESSION['Eigner']['eig_eigner'];
}

VF_Displ_Eig($ei_id);

$tabelle_m = $_SESSION[$module]['tabelle_m'] = "ar_chivdt";
$tabelle = $tabelle_m . "_" . $_SESSION['Eigner']['eig_eigner'];
$_SESSION[$module]['tabelle'] = $tabelle;

$sql = "SELECT ad_sg,ad_subsg,ad_lcsg,ad_lcssg,SUM(ad_eignr) AS Anzahl FROM $tabelle GROUP BY ad_sg,ad_subsg,ad_lcsg,ad_lcssg ORDER BY ad_sg,ad_subsg,ad_lcsg,ad_lcssg "; # ,SUM(ad_eignr) AS Anzahl

$return = SQL_QUERY($db, $sql);

$s_cnt = 0;

echo "<table class='w3-table w3-striped w3-hoverable scroll'
    style='border: 1px solid black; background-color: white; margin: 0px;' id='myTable2'>";
echo "<thead>";
echo "<tr><th onclick='sortTable(0)'>Sachgeb</th><th>Sub-Sachgebiet</th><th onclick='sortTable(2)'>Untergruppe</th><th>SubGr.</th><th onclick='sortTable(4)'>Archiv- Ordnung</th><th onclick='sortTable(5)'>Anzahl Einträge</th></tr>";
echo "</thead>";
echo "<tbody>";
while ($row = mysqli_fetch_object($return)) {

    $cnt = $row->Anzahl/21;
    $s_cnt += $cnt;
    $arl_str = "";
    if ($row->ad_lcsg != "0" || $row->ad_lcssg != "0") {
        $arl_str = VF_Displ_Arl($row->ad_sg,$row->ad_subsg,$row->ad_lcsg,$row->ad_lcssg);
    } elseif ($row->ad_sg != "" ) { ## || $row->ad_subsg != "0"
        $arl_str = VF_Displ_Aro($row->ad_sg,$row->ad_subsg);
    } else {
        $arl_str = "Falsche Archiv- Ordnungs- Bezeichnung";
    }
   
    echo "<tr><td>$row->ad_sg</td><td> $row->ad_subsg</td><td> $row->ad_lcsg</td><td> $row->ad_lcssg</td><td>$arl_str</td><td>$cnt </td></tr>";
      
}
echo "<tr><td></td><td> </td><td> </td><td> </td><td> </td><td>$s_cnt </td></tr>";
echo "</tbody>";
echo "</table>";
#cho "</div>";
?>
<script>
function sortTable(n) {
    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.getElementById("myTable2");
    switching = true;
    // Set the sorting direction to ascending:
    dir = "asc";
    /* Make a loop that will continue until
     no switching has been done: */
    while (switching) {
        // Start by saying: no switching is done:
        switching = false;
        rows = table.rows;
        /* Loop through all table rows (except the
         first, which contains table headers): */
        for (i = 1; i < (rows.length - 1); i++) {
            // Start by saying there should be no switching:
            shouldSwitch = false;
            /* Get the two elements you want to compare,
             one from current row and one from the next: */
            x = rows[i].getElementsByTagName("TD")[n];
            y = rows[i + 1].getElementsByTagName("TD")[n];
            /* Check if the two rows should switch place,
             based on the direction, asc or desc: */
            if (dir == "asc") {
                if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                    // If so, mark as a switch and break the loop:
                    shouldSwitch = true;
                    break;
                }
            } else if (dir == "desc") {
                if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                    // If so, mark as a switch and break the loop:
                    shouldSwitch = true;
                    break;
                }
            }
        }
        if (shouldSwitch) {
            /* If a switch has been marked, make the switch
             and mark that a switch has been done: */
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            // Each time a switch is done, increase this count by 1:
            switchcount ++;
        } else {
            /* If no switching has been done AND the direction is "asc",
             set the direction to "desc" and run the while loop again. */
            if (switchcount == 0 && dir == "asc") {
                dir = "desc";
                switching = true;
            }
        }
    }
}
</script>
<?php 

echo "</fieldset>";
BA_HTML_trailer();
?>
