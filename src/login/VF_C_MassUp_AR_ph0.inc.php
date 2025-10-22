<?php
if ($debug) {
    echo "<pre class=debug>VF_C_MassUp_AR_ph1.inc.php ist gestarted</pre>";
}

echo "<h2>Eigentümer und Archivordnung auswählen zum Archivalien hochladen</h2>";
/*
 VF_Eig_Ausw();
 */
/**VF_Eig_Ausw
 * neuen Eigentümer auswählen
 */
if (isset($_SESSION['VF_Prim']['mode']) && $_SESSION['VF_Prim']['mode'] == "Mandanten") {
    if ($_SESSION['Eigner']['eig_eigner'] == "") {
        VF_Auto_Eigent("E");
    }
} else {
    $_SESSION['Eigner']['eig_eigner'] = $_SESSION['VF_Prim']['eignr'];
}

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

echo "<br> <button type='submit' name='phase' value='1' class=green>Auswahl bestätigen</button></p>";

if ($debug) {
    echo "<pre class=debug>VF_C_MassUp_FO_ph2.inc.php ist beendet</pre>";
}
