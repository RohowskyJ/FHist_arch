<?php

/**
 * Mitgliederverwaltung, Formular
 * 
 * @author Josef Rohowsky - neu 2020
 * 
 */

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$_SESSION[$module]['Inc_Arr'][] = "VF_M_Edit_ph0.inc.php"; 

if ($debug) {
    echo "<pre class=debug>VF_M_Edit_ph0.inc.php ist gestarted </pre>";
}

echo "<div class='white'>";

echo "<input type='hidden' name='mi_id' value='" . $neu['mi_id'] . "'>";
echo "<input type='hidden' name='mi_einversterkl' value='" . $neu['mi_einversterkl'] . "'>";
echo "<input type='hidden' name='mi_einv_dat' value='" . $neu['mi_einv_dat'] . "'>";
echo "<input type='hidden' name='p_uid' value='" . $_SESSION['VF_Prim']['p_uid'] . "'>";

$Edit_Funcs_FeldName = false; // Feldname der Tabelle wird nicht angezeigt !!
                              # =========================================================================================================
Edit_Tabellen_Header('Mitglieder- Daten');
# =========================================================================================================

Edit_Daten_Feld('mi_id');

# =========================================================================================================
Edit_Separator_Zeile('Organisations- Daten');
# =========================================================================================================

Edit_Radio_Feld(Prefix . 'mi_org_typ', M_Org);

Edit_Daten_Feld(Prefix . 'mi_org_name', 50);

# =========================================================================================================
Edit_Separator_Zeile('Mitglieds-Daten');
# =========================================================================================================
Edit_Radio_Feld(Prefix . 'mi_mtyp', M_Typ);
Edit_Radio_Feld(Prefix . 'mi_anrede', array(
    "Fr." => "Frau",
    "Hr." => "Herr"
));
Edit_Daten_Feld(Prefix . 'mi_dgr', 10, 'FF Dienstgrad');
Edit_Daten_Feld(Prefix . 'mi_titel', 10);
Edit_Daten_Feld(Prefix . 'mi_name', 50);
Edit_Daten_Feld(Prefix . 'mi_vname', 50);
Edit_Daten_Feld(Prefix . 'mi_n_titel', 10);
Edit_Daten_Feld(Prefix . 'mi_gebtag', 10, '', "type='date'");

$ST_Opt = VF_Sel_Staat('mi_staat', '9', ''); // VF_Edit_v3Ph0
Edit_Select_Feld(Prefix . 'mi_staat', $ST_Opt);

Edit_Daten_Feld(Prefix . 'mi_anschr', 50);
Edit_Daten_Feld(Prefix . 'mi_plz', 7);
Edit_Daten_Feld(Prefix . 'mi_ort', 50);

Edit_Daten_Feld(Prefix . 'mi_tel_handy', 50);
# Edit_Daten_Feld(Prefix . 'mi_handy', 50);
Edit_Daten_Feld(Prefix . 'mi_fax', 50);
Edit_Daten_Feld(Prefix . 'mi_email', 50);
Edit_Daten_Feld(Prefix . 'mi_email_status', 50);

# =========================================================================================================
Edit_Separator_Zeile('Mitarbeit, Information');
# =========================================================================================================

Edit_Radio_Feld(Prefix . 'mi_vorst_funct', V_Funktion);
Edit_Radio_Feld(Prefix . 'mi_ref_leit', L_Funktion);

# Edit_Select_Feld(Prefix.'mi_ref_int', I_Funktion);

# =========================================================================================================
Edit_Separator_Zeile('Wo sind meine Interessen: ');
# =========================================================================================================

# Edit_Check_Box('mi_ref_int', VF_Referate_anmeld,'Mehrfachauswahl möglich ');
Edit_CheckBox('mi_ref_int_2', VF_Referate_anmeld[2]);
Edit_CheckBox('mi_ref_int_3', VF_Referate_anmeld[3]);
Edit_CheckBox('mi_ref_int_4', VF_Referate_anmeld[4]);


# =========================================================================================================
Edit_Separator_Zeile('Ein- Austritt, Sterbedatum');
# =========================================================================================================
Edit_Daten_Feld(Prefix . 'mi_beitritt', 15, '', "type='date'");
Edit_Daten_Feld(Prefix . 'mi_austrdat', 15, '', "type='date'");
Edit_Daten_Feld(Prefix . 'mi_sterbdat', 15, '', "type='date'");

# =========================================================================================================
Edit_Separator_Zeile('Mitgliedsbeitrag, Abo, Ausgabe');
# =========================================================================================================

# Edit_Daten_Feld(Prefix.'mi_m_beitr',50);
# Edit_Daten_Feld(Prefix.'mi_m_abo',50);
$Edit_Funcs_Protect = True;
Edit_Daten_Feld(Prefix . 'mi_m_beitr_bez', 50);
Edit_Daten_Feld(Prefix . 'mi_m_abo_bez', 50);
$Edit_Funcs_Protect = False;
Edit_Daten_Feld(Prefix . 'mi_abo_ausg', 50);

# =========================================================================================================
Edit_Separator_Zeile('Datenschutz- Erklärung (DSVGO)');
# =========================================================================================================
Edit_Radio_Feld(Prefix . 'mi_einv_art', M_Einv);

Edit_Daten_Feld(Prefix . 'mi_einversterkl');
Edit_Daten_Feld(Prefix . 'mi_einv_dat');

Edit_Daten_Feld(Prefix . 'mi_uidaend');
Edit_Daten_Feld(Prefix . 'mi_aenddat');

# =========================================================================================================
Edit_Tabellen_Trailer();
if ($_SESSION[$module]['all_upd'] || $_SESSION['VF_Prim']['p_uid'] == $neu['mi_id']) {
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' name='phase' value='1' class=green>Daten abspeichern</button></p>";
}

echo "<p><a href='VF_M_List.php>Zurück zur Liste</a></p>";

echo "<div class='w3-container'><fieldset> <label> Auszeichnungs- Details: </label><br/>";
require 'VF_M_EH_List.inc.php';
echo "</fieldset></div>";

# =========================================================================================================
#
#
# =========================================================================================================
function modifyRow(array &$row, # die Werte - das array wird by Name übergeben um die Inhalte ändern zu könnnen !!!!
$tabelle)
{
    global $path2VF, $T_List, $module, $pict_path, $proj;
    # echo "L 86: \$tabelle $tabelle <br/>";

    if ($tabelle == "fh_m_ehrung") {
        # $pict_path = "referat4/AUSZ/".$_SESSION[$proj]['fw_bd_abk']."/";
        $fe_lfnr = $row['fe_lfnr'];
        $row['fe_lfnr'] = "<a href='VF_M_EH_Edit.php?ID=$fe_lfnr' >" . $fe_lfnr . "</a>";
        if ($row['fe_bild1'] != "") {
            $pict_path = "AOrd_Verz/1/MITGL/";
            
            $fe_bild1 = $row['fe_bild1'];
            $p1 = $pict_path . $row['fe_bild1'];
            
            $row['fe_bild1'] = "<a href='$p1' target='Bild 1' > <img src='$p1' alter='$p1' width='70px'>  $fe_bild1  </a>";
        }
    }
    return True;
} # Ende von Function modifyRow

if ($debug) {
    echo "<pre class=debug>VF_M_Edit_ph0.inc.php beendet</pre>";
}
?>