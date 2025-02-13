<?php

/**
 * Home-Page setup. Mode, Kenndaten, Funktionen
 *
 * @author Josef Rohowsky - neu 2023
 *
 *
 */
if ($debug) {
    echo "<pre class=debug>Proj_Config_Edit_ph0.inc.php ist gestarted</pre>";
}

echo "<div class='white'>";

# =========================================================================================================
Edit_Tabellen_Header('Definitionen');
# =========================================================================================================

# =========================================================================================================
Edit_Separator_Zeile('Betreiber- Daten');
# =========================================================================================================

Edit_Daten_Feld('c_Institution', 100);

Edit_Daten_Feld('c_Vereinsreg', 15);

Edit_Daten_Feld('c_Eignr',5);
Edit_Daten_Feld('c_Verantwort', 100);
Edit_Daten_Feld('c_Ver_Tel', 30);
Edit_Daten_Feld('c_email', 60);
Edit_Daten_Feld('c_Homepage', 60);

# =========================================================================================================
Edit_Separator_Zeile('Beschreibungs- Nutzungs- Daten');
# =========================================================================================================

Edit_Select_Feld('c_mode', array(
    "Single" => "Keine Mandanten",
    "Mandanten" => "Mandanten"
));

Edit_Select_Feld('c_Wartung', array(
    "J" => "System in Wartung",
    "N" => "System in Betrieb",
    "U" => "System in Sonderzustand - siehe Wartungsgrund"
));

Edit_Daten_Feld('c_Wart_Grund', 100);

echo "<input type='hidden' name='c_logo' value='".$neu['c_logo']."' >";
echo "<input type='hidden' name='c_1page' value='".$neu['c_1page']."' >";

/*
Edit_Daten_feld('c_logo', 50);
Edit_Upload_file('c_logo');

Edit_Daten_feld('c_1page', 50);
Edit_Upload_file('c_1page');

*/
$pict_path = 'imgs/';
$Feldlaenge = "100px";

$pic_arr = array(
    "01" => "|||c_logo",
    "02" => "|||c_1page"
);
VF_Multi_Foto($pic_arr);


const Ja_Nein = array(
    'J' => 'Ja',
    'N' => 'Nein'
);
Edit_Select_Feld('c_Module_1', Ja_Nein);
Edit_Select_Feld('c_Module_2', Ja_Nein);
Edit_Select_Feld('c_Module_3', Ja_Nein);
Edit_Select_Feld('c_Module_4', Ja_Nein);
Edit_Select_Feld('c_Module_5', Ja_Nein);
Edit_Select_Feld('c_Module_6', Ja_Nein);
Edit_Select_Feld('c_Module_7', Ja_Nein);
Edit_Select_Feld('c_Module_8', Ja_Nein);
Edit_Select_Feld('c_Module_9', Ja_Nein);
Edit_Select_Feld('c_Module_10', Ja_Nein);
Edit_Select_Feld('c_Module_11', Ja_Nein);
Edit_Select_Feld('c_Module_12', Ja_Nein);
Edit_Select_Feld('c_Module_13', Ja_Nein);
Edit_Select_Feld('c_Module_14', Ja_Nein);
Edit_Select_Feld('c_Module_15', Ja_Nein);
# =========================================================================================================
Edit_Tabellen_Trailer();

echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
echo "<button type='submit' name='phase' value='1' class='green'>Daten abspeichern</button></p>";

echo "</div>";
# =========================================================================================================

if ($debug) {
    echo "<pre class=debug>Proj_Config_Edit_ph0.inc.php beendet</pre>";
}
?>