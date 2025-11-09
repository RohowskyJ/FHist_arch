<?php

/**
 * Mail an andere Mitglieder senden
 * 
 * @author  Josef Rohowsky - neu 2023
 * 
 * 
 */
session_start(); # die SESSION aktivieren

$module = 'MVW';
$sub_mod = 'all';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$_SESSION[$module]['Inc_Arr']  = array();
$_SESSION[$module]['Inc_Arr'][] = "VF_M_Mail.php"; 

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_Funcs.lib.php';

$flow_list = False;

$LinkDB_database = '';
$db = LinkDB('VFH');

initial_debug(); # Wenn $debug=true - Ausgabe von Debug Informationen: $_POST, $_GET, $_FILES

# =========================================================================================================== -->
# die HTML Seite ausgeben -->
# =========================================================================================================== -->

BA_HTML_header('E-Mail- Kontakt', '', 'Form', '70em', '');

echo "<div class='white'>";

echo "Diese Liste enthält die derzeit bei uns registrierten Mitglieder.";

echo "</div>";

$sql_where = " ";
$sql_where = "WHERE $sql_where  mi_email LIKE '%@%' AND (mi_austrdat <='0000-00-00' AND mi_sterbdat <='0000-00-00') OR (mi_austrdat IS NULL AND mi_sterbdat IS NULL)";

$sql = "SELECT * FROM fh_mitglieder ";

$sql .= "\n$sql_where" . "\nORDER BY  mi_name ASC";

echo "<div class='toggle-SqlDisp'>";
echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>M Mailt $sql </pre>";
echo "</div>";

$sqlresult = SQL_QUERY($db, $sql);

if (empty($sqlresult)) {
    exit();
}
$zeilen = mysqli_num_rows($sqlresult);
if ($zeilen == 0) {
    exit();
}
?>

<!-- ======================================================================================================= 
<fieldset>
-->
 
<?php
echo "<br>Klicken Sie auf <q><b>Mail</b></q> um eine Nachricht an die Person Ihrer Wahl zu senden";
?>
  <table class='w3-table w3-striped w3-hoverable'>
		<thead>
			<tr style="border-bottom-width: 1pt; border-bottom-style: solid;">
     <?php

    echo '<th></th><th>Name</th>';
    ?>
  </tr>
		</thead>
		<tbody>
<?php
while ($row = mysqli_fetch_assoc($sqlresult)) {
    if ($debug) {
        echo "<pre class=debug>row:";
        print_r($row);
        echo '</pre>';
    }
    echo "\n<tr>";

    echo "<td style='text-align:center;'>";

    $t_id = $row['mi_id'] * 13579;
    echo "<a href='VF_M_Mail_kontakt.php?id=$t_id' target='_blank'>Mail</a>";
    # }
    echo "</td>";
    if ($row['mi_name'] != "") {
        $name = $row['mi_name'] . " " . $row['mi_vname'];
        if ($row['mi_org_name'] != "") {
            $name .= ", " . $row['mi_org_typ'] . " " . $row['mi_org_name'];
        }
    } else {
        $name = $row['mi_org_typ'] . " " . $row['mi_org_name'];
    }
    echo "<td>$name</td>";

    echo "</tr>";
} // while Ende
?>
    </tbody>
	</table>

<!--  /fieldset>  -->
<br>
<?php BA_HTML_trailer();?>