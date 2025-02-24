<?php

/**
 * Laden von Daten in ArchivTabellen umd Moven von Dateien in die entsprechenden Verzechnisse
 * 
 * @author Josef Rohowsky - neu 2023
 * 
 * Abfrage der anzuwendenden Archivordnungs-. Punkte
 * 
 * 
 * @global boolean $debug     Anzeige  von Debug- Informationen: if ($debug) { echo "Text" }
 */
if ($debug) {
    echo "<pre class=debug>VF_A_AR_MassUp2_Arch_Tabs_ph0.inc.php ist gestarted</pre>";
}

#require $path2ROOT . "login/common/VF_F_Inc_ArchOrd.inc";

$pfad = $beschreibg = "";

echo "<div class='white'></div>";
echo "<fieldset style=0'border:2px solid:blue;'>";


/**
 * Eigentümer- Auswahl (Autocomplete)
 */
VF_Eig_Ausw();
/**
 * Parameter für den Aufruf von Multi-Dropdown
 *
 * Benötigt Prototype<script type='text/javascript' src='common/javascript/prototype.js' ></script>";
 *
 *
 * @var array $MS_Init  Kostante mt den Initial- Werten (1. Level, die weiteren Dae kommen aus Tabellen) [Werte array(Key=>txt)]
 * @var string $MS_Lvl Anzahl der gewüschten Ebenen - 2 nur eine 2.Ebene ... bis 6 Ebenen
 * @var string $MS_Opt Name der Options- Datei, die die Werte für die weiteren Ebenen liefert
 *
 * @Input-Parm $_POST['Level1...6']
 */

$MS_Txt = array(
    'Auswahl der Obersten (ÖBFV)- Ebene  ',
    'Auswahl der 2. ÖBFV- Ebene ',
    'Auswahl der Lokalen Erweiterung (3. Ebene) ',
    'Auswahl des 2. Erweiterung (4. Ebene)  ',
    'Auswahl des 3. Erweiterung (5. Ebene)  ',
    'Auswahl des 4. Erweiterung (6. Ebene)  '
);

$MS_Lvl   = 6; # 1 ... 6
$MS_Opt   = 2; # 1: SA für Sammlung, 2: AO für Archivordnung

switch ($MS_Opt) {
    case 1:
        $in_val = 'PA_R';
        $MS_Init = VF_Sel_SA_Such; # VF_Sel_SA_Such|VF_Sel_AOrd
        break;
    case 2:
        $in_val = '07';
        $MS_Init = VF_Sel_AOrd; # VF_Sel_SA_Such|VF_Sel_AOrd
        break;
}


$titel  = 'Auswahl aus der Archivordnung';
VF_Multi_Dropdown($in_val,$titel);



echo "<p>Nach Eingabe Archivordungs-Auswahl  drücken Sie ";
echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";

echo "<p><a href='VF_A_AR_MassUp2_Arch_Tabs.php'>Zurück zur Liste</a></p>";

# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>VF_A_AR_MassUp2_Arch_Tabs_ph0.inc.php beendet</pre>";
}
?>