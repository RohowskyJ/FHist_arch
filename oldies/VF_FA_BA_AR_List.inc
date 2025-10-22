<?php

/**
 * Liste der Archivalien zum Fahrzeg
 *
 * @author Josef Rohowsky - neu 2018
 *
 */

# ===========================================================================================
#
# Definition der Auswahlmöglichkeiten (mittels radio Buttons)
#
# ===========================================================================================
$T_list_texte = array(
   # "NeuItem" => "<a href='VF_FA_BA_AR_Edit.php?ID=0' >Neuen Datensatz eingeben</a>"
);

# Haeder ausgeben

$logo = 'NEIN';

List_Prolog($module,$T_list_texte); # Paramerter einlesen und die Listen Auswahl anzeigen

$LinkDB_database = '';

$db = LinkDB('VFH');

$tabelle_xr = "ma_arc_xref";

$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle_xr);

$tabelle_xr_a = $tabelle_xr . "_" . $_SESSION['Eigner']['eig_eigner'];

$return = Cr_n_ma_arc_xref($tabelle_xr_a);
if ($return != True) {
    echo "error: mysqli_errno($return)";
}

$select_xr = " WHERE `fa_fzgnr` = '$fz_id' && fa_sammlg LIKE '".$_SESSION[$module]['fz_sammlg']."' ";
$sort_xr = " ORDER BY `fa_id`";
$sql_xr = "SELECT * FROM `$tabelle_xr_a` $select_xr  $sort_xr ";
# echo "L 064 sql_xr $sql_xr <br>";
$return_xr = mysqli_query($db, $sql_xr);
if ($return_xr) {
    $zeilen = mysqli_num_rows($return_xr);
    if ($zeilen > 0) {
        while ($row = mysqli_fetch_object($return_xr)) {
            $xr_arr[] = $row->fa_arcnr;
        }

        $zeilen = count($xr_arr);
    }
} else {
    echo "Noch keine Daten vorhanden, wenn bekannt, neue Daten eingeben.<br>";
    $zeilen = 0;
}

$tabelle_ar = "ar_chivdt";
$Tabellen_Spalten = Tabellen_Spalten_parms($db, $tabelle_ar);

$tabelle = $tabelle_ar . "_" . $_SESSION['Eigner']['eig_eigner'];

switch ($T_List) {

    default:
        $Tabellen_Spalten = array(
            'ad_id',
            'ad_beschreibg'
        );
        break;
}

$Tabellen_Spalten_style['ad_id'] = 'text-align:center;';

$List_Hinweise = '<li>Blau unterstrichene Daten sind Klickbar' . '<ul style="margin:0 1em 0em 1em;padding:0;">' . '<li>Zur Ansicht: Auf die Zahl in Spalte <q>ad_id</q> Klicken.</li>';

$List_Hinweise .= '</ul></li>';

List_Action_Bar($tabelle,"", $T_list_texte, $T_List, $List_Hinweise); # Action Bar ausgeben

if ($zeilen != 0) {
    # ===========================================================================================================
    # Die Daten lesen und Ausgeben (substitute von ListCreate)
    # ===========================================================================================================

    echo '<div class=white>';

    echo '</div>';

    if ($debug) {
        echo "<pre class=debug>Tabellen_Spalten ";
        print_r($Tabellen_Spalten);
        echo "</pre>";
        echo "<pre class=debug>Tabellen_Spalten_COMMENT ";
        print_r($Tabellen_Spalten_COMMENT);
        echo "</pre>";
        echo "<pre class=debug>Tabellen_Spalten_style ";
        print_r($Tabellen_Spalten_style);
        echo "</pre>";
    }

    # =======================================================================================================
    # Tabelle ausgeben
    # =======================================================================================================

    $hide_columns = ' ' . $List_parm['hide'] . ' '; # nicht anzuzeigenden Spalten

    $T_Style = "style='min-height:8cm; '";
    if (1==1) {
        if ($zeilen >= 15) {
            $T_Style = "style='height:15cm;'";
        } else {
            $T_Style = "style='min-height:8cm; '";
        }
    }

    ?>

<div class='resize' <?php echo $T_Style ?>>
	<table class='w3-table w3-striped w3-hoverable scroll'
		style='border: 1px solid black; background-color: white; margin: 0px;'>


		<!-- =================================================================================================== -->
		<!-- Tabellen Kopfzeile Ausgeben                                                                         -->
		<!-- =================================================================================================== -->
		<thead>
			<tr style='border-bottom: 1px solid black;'>

<?php
    $CSV_Text_zeile = $CSV_Text_zeile2 = ";";
    $i = 0; # Spaltenzähler

    foreach ($Tabellen_Spalten as $key => $column_name) # ================ für alle Spalten =================
    {
        if (mb_strpos($hide_columns, " $column_name ") !== false) {
            continue;
        } # skip hidden column
        $i ++; # Spaltenzähler
        echo "<th>";
        echo "<div class='dropdown'>";

        # -------------------------------------------- Spalten Titel -----------------------------------------
        $Pfeil = '';
    if ( $column_name==$List_parm['sort_column'] # Sortierrichtungs Pfeil nur für diese Spalte Anzeigen
       & $zeilen>1 )                   # Sortierrichtungs Pfeil nur Anzeigen wenn es mehrere Zeilen gibt 
    {
            if ($VF_List_parm['sort_richtung'] == 'ASC') {
                $Pfeil = "<img src='" . $path2VF . "login/common/imgs/arrowUp.gif'   alt='Asc'> ";
            } else {
                $Pfeil = "<img src='" . $path2VF . "login/common/imgs/arrowDown.gif' alt='Desc'>";
            }
        }

        if ($SpaltenNamenAnzeige != 'Ein' & isset($Tabellen_Spalten_COMMENT[$column_name])) {
            $txt = $Tabellen_Spalten_COMMENT[$column_name];
            $txta = explode('<', $txt);
            $txt = $txta[0];
        } else {
            $txt = str_replace("_", " ", $column_name);
        }
        # echo " <button class='dropbtn'>".str_replace("_"," ",$column_name)."</button>$Pfeil";
        echo " <button class='dropbtn'>$txt</button>$Pfeil";

        # -------------------------------------------- Spalten Drop Down Liste -----------------------------------
        if ($i < 6) {
            echo " <div class='dropdown-content' style='text-align:left;color:black;'>";
        } else {
            echo " <div class='dropdown-content' style='text-align:left;color:black;right:0;'>";
        }

        if (isset($Tabellen_Spalten_COMMENT[$column_name])) {
            echo $Tabellen_Spalten_COMMENT[$column_name];
        }
        
            if ($zeilen > 1 & # Sortierrichtung nur wenn es mehrere Zeilen gibt
            isset($Tabellen_Spalten_tabelle[$column_name])) # Sortierrichtung nur wenn die Spalte aus einer Tabelle kommt
            {
                echo "  <a href='$_SERVER[PHP_SELF]?scol=$column_name&sord=ASC' >Aufsteigend sortieren</a>";
                echo "  <a href='$_SERVER[PHP_SELF]?scol=$column_name&sord=DESC'>Absteigend sortieren</a>";
            }

            echo "  <a href='$_SERVER[PHP_SELF]?hide=$column_name'>Spalte nicht Anzeigen</a>";
       
        echo "  </div>"; # Drop Down Liste beenden
        echo "</div>";
        echo "</th>";

        if (isset($Tabellen_Spalten_tabelle[$column_name])) {
            $CSV_Text_zeile .= ";$column_name";
            $CSV_Text_zeile2 .= ";";
            if (isset($Tabellen_Spalten_COMMENT[$column_name])) {
                $CSV_Text_zeile2 .= str_replace($weg, "", $Tabellen_Spalten_COMMENT[$column_name]);
            }
        }
    } # Header Spalten Schleife
    ?>
  </tr>
		</thead>
		<tbody>
<?php
    $Zeilen_Nr = 0;
    if ($zeilen > 0) {
        foreach ($xr_arr as $key) {

            $sql = "SELECT * FROM $tabelle WHERE ad_id = $key ";
            $return = SQL_QUERY($db, $sql);
            $row = mysqli_fetch_assoc($return);
            $modRC = modifyRow($row, $tabelle);
            if ($modRC === False) {
                continue;
            }

            # ====================================================================================================================
            # alle Spalten ausgeben
            # ====================================================================================================================
            if (gettype($modRC) == 'string') {
                echo "<tr style='$modRC'>";
            } else {
                echo "<tr>";
            }
            $Zeilen_Nr ++;
            $CSV_Text_zeile = $Zeilen_Nr;
            foreach ($Tabellen_Spalten as $key => $column_name) # alle Spalten ausgeben
            {
                if (mb_strpos($hide_columns, " $column_name ") !== false) {
                    continue;
                } # hide column : skip ip
                $value = '';
                if (isset($row[$column_name])) {
                    $value = $row[$column_name];
                }
                $style = "";
                if (isset($Tabellen_Spalten_style[$column_name])) {
                    $style = $Tabellen_Spalten_style[$column_name];
                }
                echo "<td style='$style'>$value</td>";

                if (isset($Tabellen_Spalten_tabelle[$column_name])) {
                    if (isset($csvrow[$column_name])) {
                        $CSV_Text_zeile .= ';' . str_replace($weg, "", $csvrow[$column_name]);
                    } else {
                        $CSV_Text_zeile .= ';';
                    }
                }
            } # alle Spalten ausgeben

            echo "</tr>";
        }
    }
}

beenden:
?>
  </tbody>
	</table>
</div>

