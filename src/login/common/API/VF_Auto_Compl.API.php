<?php
/**
 * Eigentümer- Auswahl
 *
 * Auswahl eines Leihers oder neuer Eigentümer
 * Aufruf mittels AJAX UI mittels BA_Auto_Compl.API.php aus verschiedenen Programmen
 *
 * @author Josef Rohowsky -  neu 2018
 *
 *
 */
session_start();

const Module_Name = 'Eigner-Search';
$module = Module_Name;
$tabelle = "";

# 'fh_zugriffe_n';
const Prefix = '';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../../../";
$debug = False;
require $path2ROOT . 'login/common/BA_Funcs.lib.php';


if (isset($_GET["term"])) {
    $term = $_GET["term"];
}

if (isset($_POST['query']) ) {
    $term = $_POST['query'];
}
if (isset($_POST['q']) ) {
    $term = $_POST['q'];
}
if (isset($_POST['proc'])) {
    $proc = $_POST['proc'];
} else {
    $proc = "Eigent";
}

$LinkDB_database = "";
$db = LinkDB('VFH'); // Connect zur Datenbank
/**/
$dsn = "autocomp.log";
$eintragen = Date("Y-m-d H:i:s")."\n";
$eintragen .= "term $term \n";
$eintragen .= "proc $proc \n";

$datei = fopen($dsn, "a");
fputs($datei, mb_convert_encoding($eintragen, "ISO-8859-1"));
fclose($datei);
/*/
$srch_arr = array();

if (isset($term)) {
    /*
    $eintragen = " isset term term $term \n";
    
    $datei = fopen($dsn, "a");
    fputs($datei, mb_convert_encoding($eintragen, "ISO-8859-1"));
    fclose($datei);
    */
    if ($proc == "Eigent") {
        eigent($term);
    }
        
    
# }
# var_dump($srch_arr);
$result = array(
    array(
        "label" => '0- Kein Eigentümer ausgewählt',
        'var' => '0- Kein Eigentümer ausgewählt'
    )
);
foreach ($srch_arr as $company) {
    $companyLabel = $company["label"];
    if (strpos(strtoupper($companyLabel), strtoupper($term)) !== false) {
        array_push($result, $company);
    }
}

function eigent ($term) {
    global $db, $module, $srch_arr;
    /*
    $dsn = "api_log";
    $eintragen = "f Eig  $term L 094\n";
    
    $datei = fopen($dsn, "a");
    fputs($datei, mb_convert_encoding($eintragen, "ISO-8859-1"));
    fclose($datei);
    */
    $query = "SELECT * FROM fh_eigentuemer WHERE ei_name LIKE '{$term}%' OR ei_org_name  LIKE '{$term}%' LIMIT 100";
    $result = SQL_QUERY($db, $query);
    
    if (mysqli_num_rows($result) > 0) {
        /*
        $eintragen = "num row > 0 \L 0104n";
        
        $datei = fopen($dsn, "a");
        fputs($datei, mb_convert_encoding($eintragen, "ISO-8859-1"));
        fclose($datei);
        */
        while ($user = mysqli_fetch_array($result)) {
            $lab = $user['ei_org_name'] . " - " . $user['ei_name'] . " " . $user['ei_vname'];
            $val = $user['ei_id'] . "- " . $user['ei_org_name'] . " - " . $user['ei_name'] . " " . $user['ei_vname'];
            $srch_arr[] = array(
                "label" => $val,
                "var" => $val
            );
            echo '<div class="autocomplete-suggestion">' . htmlspecialchars($val) . '</div>';
        }
    } else {
        echo "<p style='color:red'>User not found...</p>";
    }
    
} # ende funct eigent

function urheb ($term) {
    global $db, $module, $srch_arr;
    
    $query = "SELECT * FROM fh_eigentuemer WHERE ei_name LIKE '{$term}%' OR ei_org_name  LIKE '{$term}%' LIMIT 100";
    
    $result = SQL_QUERY($db, $query);
    
    if (mysqli_num_rows($result) > 0) {
        
        while ($user = mysqli_fetch_array($result)) {
            $lab = $user['ei_org_name'] . " - " . $user['ei_name'] . " " . $user['ei_vname'];
            $val = $user['ei_id'] . "- " . $user['ei_org_name'] . " - " . $user['ei_name'] . " " . $user['ei_vname'];
            $srch_arr[] = array(
                "label" => $val,
                "var" => $val
            );
            echo '<div class="autocomplete-suggestion">' . htmlspecialchars($val) . '</div>';
        }
    } else {
        echo "<p style='color:red'>User not found...</p>";
    }
    
} # ende funct urheb

# echo json_encode($result);
?>
