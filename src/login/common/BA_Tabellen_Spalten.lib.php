<?php

/**
 * Bibliothek für Datenbanktabellen Kennwerte auslesen
 * 2019       B.R.Gaicki - neu.
 *
 * KEXI_Tabellen_Spalten_parms - liest aus sql die Spaltendefinitionen und stellt diese in 6 Arrays
 *  - $Tabellen_Spalten           Array (Schlüssel: interner) mit den Spaltennamen (mit 'return' zurückgegeben)
 *  - $Tabellen_Spalten_NULLABLE  Global Array (Schlüssel: SpaltenName  = 'Y' Feld ist Nullable
 *  - $Tabellen_Spalten_COMMENT   Global Array (Schlüssel: Spaltenname) mit Texten zu den Spalten
 *  - $Tabellen_Spalten_style     Global Array (Schlüssel: Spaltenname) mit den Style für das <td> Element
 *  - $Tabellen_Spalten_typ       Global Array (Schlüssel: Spaltenname) mit 'text'/'num'
 *  - $Tabellen_Spalten_tabelle   Global Array (Schlüssel: Spaltenname) mit dem Namen der Tabelle
 *         (für Spalten ohne Tabellen Name wird im heading pull down keine sortiermöglichkeit Angeboten)
 *
 * Änderungen:
 *  2019       B.R.Gaicki - neu
 *  2019-05-09 B.R.Gaicki - Spalten mit Komentar=blank anzeigen. Spalten mit Kommentar Position 1=! NICHT anzeigen
 *  2021-01-15 B.R.Gaicki - prefix KEXI_ zu funktionsnamen hinzugefügt
 *  2021-04-14 B.R.Gaicki - $Tabellen_Spalten_typ hinzugefügt
 *  2022-02-05 B.R.Gaicki - V5 (PixRipTab & login).
 *
 *
 */

if ($debug) {
    echo "L 027: Tabellen_Spalten.inc.php ist geladen. <br/>";
}

/**
 * Enlesen der Tabellen Spezifikationen in Tabellen zur weiteren Verwendung
 *
 * @param array $dblink
 *            Datenbank Handle
 * @param string $tabelle
 *            Tabellen- Name
 * @param string $database
 *            Datenbankname
 * @return array $Tabellen_Spalten Globales Array der Datenbankfelder für die Ausgabe in Listen
 *        
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 * @global string $KEXI_LinkDB_database Datenbankname
 * @global array $Tabellen_Spalten_NULLABLE Global Array (Schlüssel: SpaltenName = 'Y' Feld ist Nullable
 * @global array $Tabellen_Spalten_COMMENT Global Array (Schlüssel: Spaltenname) mit Texten zu den Spalten
 * @global array $Tabellen_Spalten_style Global Array (Schlüssel: Spaltenname) mit den Style für das <td> Element
 * @global array $Tabellen_Spalten_typ Global Array (Schlüssel: Spaltenname) mit 'text'/'num'
 * @global array $Tabellen_Spalten_tabelle Global Array (Schlüssel: Spaltenname) mit dem Namen der Tabelle
 *        
 */
function Tabellen_Spalten_parms($dblink, $tabelle, $database = '')
{
    global $debug, $LinkDB_database, $Tabellen_Spalten_NULLABLE, $Tabellen_Spalten_COMMENT, $Tabellen_Spalten_style, $Tabellen_Spalten_typ, $Tabellen_Spalten_tabelle;

    $Tabellen_Spalten = array(); # wird mit 'return $Tabellen_Spalten' zurückgegeben

    if (! isset($Tabellen_Spalten_NULLABLE)) {
        $Tabellen_Spalten_NULLABLE = array();
    }
    if (! isset($Tabellen_Spalten_COMMENT)) {
        $Tabellen_Spalten_COMMENT = array();
    }
    if (! isset($Tabellen_Spalten_style)) {
        $Tabellen_Spalten_style = array();
    }
    if (! isset($Tabellen_Spalten_typ)) {
        $Tabellen_Spalten_typ = array();
    }
    if (! isset($Tabellen_Spalten_tabelle)) {
        $Tabellen_Spalten_tabelle = array();
    }

    if ($database == '') {
        $database = $LinkDB_database;
    }
    $sql = "SELECT COLUMN_NAME,COLUMN_COMMENT,COLUMN_TYPE,IS_NULLABLE FROM information_schema.COLUMNS" . " WHERE TABLE_SCHEMA='$database' AND table_name='$tabelle'";
    $sqlresult = SQL_QUERY($dblink, $sql);

    while ($row = mysqli_fetch_assoc($sqlresult)) {
        if (substr($row['COLUMN_COMMENT'], 0, 1) == '!') {
            continue;
        }
        /*
        if ($debug) {
            echo "<pre class=debug>row ";
            print_r($row);
            echo '</pre>';
        }
        */
        $column = $row['COLUMN_NAME'];

        if ($row['IS_NULLABLE'] == 'YES') {
            $Tabellen_Spalten_NULLABLE[$column] = 'Y';
        }

        $Tabellen_Spalten[] = $column;
        $Tabellen_Spalten_tabelle[$column] = $tabelle;
        $Tabellen_Spalten_COMMENT[$column] = $row['COLUMN_COMMENT'];

        $type = $row['COLUMN_TYPE'];
        if (mb_strpos($type, "int(") !== false)
            {
            $Tabellen_Spalten_style[$column] = 'text-align:right;padding-right:3px;';
            $Tabellen_Spalten_typ[$column] = 'num';
        }
        elseif ( mb_strpos($type,"char(" )===false & $type<>'text' ) {
            $Tabellen_Spalten_style[$column] = 'text-align:center;';
            $Tabellen_Spalten_typ[$column] = '';
        }
        else {
            $Tabellen_Spalten_typ[$column] = 'text';
        }
    }
/*
    if ($debug) {
        echo '<pre class=debug>$Tabellen_Spalten: ';
        print_r($Tabellen_Spalten);
        echo '</pre>';
        echo '<pre class=debug>$Tabellen_Spalten_NULLABLE: ';
        print_r($Tabellen_Spalten_NULLABLE);
        echo '</pre>';
        echo '<pre class=debug>$Tabellen_Spalten_COMMENT: ';
        print_r($Tabellen_Spalten_COMMENT);
        echo '</pre>';
        echo '<pre class=debug>$Tabellen_Spalten_style: ';
        print_r($Tabellen_Spalten_style);
        echo '</pre>';
    }
*/
    return $Tabellen_Spalten;
}

/**
 * Ende der Bibliothek
 *
 * @author Josef Rohowsky - 202403^8
 */
?>