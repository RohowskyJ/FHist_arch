<?php

/**
 * Bibliothek zur Dokumentation von Scripts
 */
# ----------------------------------------------------------------------------------
# XR_Funcs_v2.php - Gemeinsame Konstantendefinitionen und Unterprogramme - Version 2 by B.Richard Gaicki
# Mod for TOOL 2019-07 JR
# change Avtivity:
# 2018 B.R.Gaicki - neu

# ----------------------------------------------------------------------------------
# Unterprogramme:
# - XR_Recursive_Scan - einlesen aller Verzeichnis- Einträge aus der angegebenen Root-Datei
# - XR_File_Add - teilt die Dateien nach den Endungen zu den entsprechenden Funcs zu (die nächsten 4 Eintragungen)
# - XR_Htacc_Add - fügt htacces-daten ein
# - XR_Graf_Add - fügt Grafische Dateien ein
# - XR_Script_Add - fügt Script, HTML, CSS, JS Daateien ein
# - XR_Text_Add - fügt Text , csv, odp, odt, doc, xls Daten ein
# - XR_Dir_Add - fügt Verzeichnis Daten ein
# - XR_Func_Add - fügt eine Funktion in die funkt_def datei ein
# - XR_Func_Ref_Add - fügt einen Aufruf (Referenz) zu einer Funktion hinzu
# - XR_File_Ref_Add - fügt einen Aufruf (Referenz) zu einer Datei hinzu
# - XR_Graf_Ref_Add - fügt einen Aufruf (Referenz) zu einer Grafik Datei hinzu
# - XR_Script_Ref_Add - fügt einen Aufruf (Referenz) zu einer Script Datei hinzu
# - XR_Text_Ref_Add - fügt einen Aufruf (Referenz) zu einer Text Datei hinzu
#
#
// --------------------------------------------------------------------------------
const GrafFiles = array(
    ".gif",
    ".ico",
    ".jpeg",
    ".jpg",
    ".png",
    ".tif",
    ".pdf",
    ".webp"
);

const ScrFiles = array(
    ".css",
    ".htm",
    ".php",
    ".js",
    ".inc"
);

const TxtFiles = array(
    ".csv",
    ".dbf",
    ".doc",
    ".log",
    ".pps",
    ".odp",
    ".odt",
    ".txt",
    ".xml"
);

/**
 * Anlegen von .htaccess Datei Daten (Welche .htaccess Dateien gibt es wo)
 *
 * @param array $db
 *            Handle zur Datenbank
 * @param string $V_Pfad
 *            Pfad zur .htaccess Datei
 * @param string $File
 * @param array $Stat
 *            Dateiinformationen (Dateiame, Größe,letze Änderng, letzer Zugriff)
 */
function XR_Htacc_Add($db, $V_Pfad, # der Pfad ab root
$File, # zum hinzufügen
$Stat) # Statistische File-Daten
        #
        // --------------------------------------------------------------------------------
{
    global $debug, $Project, $Sachg_arr;

    # $stat = stat($Filename);
    # $sta[]: 0 .. Device Number, 7 .. size, 8 . Time last Access, 9 . Time Last Mod
    # $stat['size'] $stat['atime'] $stat['mtime']
    $Table = "doc_htaccess_$Project";
    $Size = $Stat['size'];
    $L_Aend_T = date("Y-m-d", $Stat['mtime']);
    $L_Acc_T = date("Y-m-d", $Stat['atime']);

    $Sachgeb = "";
    if (array_key_exists($V_Pfad, $Sachg_arr)) {
        $Sachgeb = $Sachg_arr[$V_Pfad];
    }

    $sql = "INSERT INTO $Table (H_Pfad,H_Acc_Name,H_H_L_Aend,H_H_L_Use,H_PW_Path,H_PW_Name,H_Sachgeb,H_PW_L_Aend,H_PW_L_Use,H_Aufruf_Sequ)
                    VALUES ('$V_Pfad','$File','$L_Acc_T','$L_Aend_T','','','$Sachgeb','','','')       ";
    # echo "<br/>L 059 \$return \$sql $sql <br/> ";
    $return = XR_SQL_QUERY($db, $sql);
    # print_r($return); echo "<br/>L 060 \$return \$sql $sql ". mysqli_error($db)."<br/> ";

    # print_r($return);
    # echo "<br/> XR_htacc_add L 321: \$return \$sql $sql <br/>";
    # echo "XR_htacc L 322: MYSQL-Err: ".mysqli_error($db)."<br/>";
}

// Ende von function

/**
 * Update Inhalte von .htaccess Daten (Inhalte der jeweiligen Datei)
 *
 * @param array $db
 *            Datenbank Handle
 * @param string $D_Str
 *            Aufrufs- Sequenz
 * @param string $P_Str
 *            Name der Passwort- Datei
 * @param string $Sachgeb
 *            Commentar Text aus der .htaccess Datei
 */
function XR_Htacc_Upd($db, $D_Str, # Aufrufs -sequenz aus .htaccess
$P_Str, # Name der htpasswd Datei
$Sachgeb = "")
#
// --------------------------------------------------------------------------------
{
    global $debug, $Project, $H_Id, $D_PWPfad;

    # $stat = stat($Filename);
    # $sta[]: 0 .. Device Number, 7 .. size, 8 . Time last Access, 9 . Time Last Mod
    # $stat['size'] $stat['atime'] $stat['mtime']
    $Table = "doc_htaccess_$Project";
    # echo "Funcs L 308: \$D_PWPfad $D_PWPfad \$P_Str $P_Str\§ <br/>";
    $Pwd_File = "$D_PWPfad/$P_Str";
    if (file_exists($Pwd_File)) {
        $Stat = stat($Pwd_File);
        $Size = $Stat['size'];
        $L_Aend_T = date("Y-m-d", $Stat['mtime']);
        $L_Acc_T = date("Y-m-d", $Stat['atime']);
    } else {
        $Size = 0;
        $L_Aend_T = $L_Acc_T = "";
    }
    $sql = "UPDATE $Table SET H_PW_Path='$D_PWPfad',H_PW_Name='$P_Str',H_Sachgeb='$Sachgeb',
                           H_PW_L_Aend='$L_Aend_T',H_PW_L_Use='$L_Acc_T',H_Aufruf_Sequ='$D_Str'
                           WHERE H_id = '$H_Id'  ";
    $return = XR_SQL_QUERY($db, $sql);
    # echo "L 097 htacc_upd sql $sql <br>";
}

// Ende von function

/**
 * Speichen der vorhandenen Grafik- Dateien (Welche ist wo)
 *
 * @param array $db
 *            Datenbank Handle
 * @param string $V_Pfad
 *            Dateipfad
 * @param string $File
 *            Dateiname
 * @param array $Stat
 *            Dateiinformationen (Name, Größe, ...)
 */
function XR_Graf_Add($db, $V_Pfad, # Pfad ab Root
$File, # Dateiname
$Stat) # Status Info der Date
        #
        // --------------------------------------------------------------------------------
{
    global $debug, $Project;

    # $stat = stat($Filename);
    # $sta[]: 0 .. Device Number, 7 .. size, 8 . Time last Access, 9 . Time Last Mod
    # $stat['size'] $stat['atime'] $stat['mtime']
    $Table = "doc_g_dateien_$Project";
    $Size = $Stat['size'];
    $L_Aend_T = date("Y-m-d", $Stat['atime']);
    $L_Acc_T = date("Y-m-d", $Stat['mtime']);

    $sql = "INSERT INTO $Table (F_Dir,F_Name,F_Groesse,F_L_Aend,F_L_Use)
                    VALUES ('$V_Pfad','$File','$Size','$L_Acc_T','$L_Aend_T')       ";
    $return = XR_SQL_QUERY($db, $sql);
}

// Ende von function

/**
 * Speichen der Script Dateien (Welche Datei ist wo, html,php,css,..)
 *
 * @param array $db
 *            Datebank handle
 * @param string $V_Pfad
 *            Dateipfad
 * @param string $File
 *            Dateiname
 * @param array $Stat
 *            Dateiinformationen (Name,Größe, ..)
 */
function XR_Script_Add($db, $V_Pfad, # Pfad ab Root
$File, # Dateiname
$Stat) # Status
        #
        // --------------------------------------------------------------------------------
{
    global $debug, $Project;

    # $stat = stat($Filename);
    # $sta[]: 0 .. Device Number, 7 .. size, 8 . Time last Access, 9 . Time Last Mod
    # $stat['size'] $stat['atime'] $stat['mtime']
    $Table = "doc_dateien_$Project";

    $Size = $Stat['size'];
    $L_Acc_T = date("Y-m-d", $Stat['mtime']);
    $L_Aend_T = date("Y-m-d", $Stat['atime']);

    $sql = "INSERT INTO $Table (F_Dir,F_Name,F_Groesse,F_L_Aend,F_L_Use)
                    VALUES ('$V_Pfad','$File','$Size','$L_Acc_T','$L_Aend_T')       ";
    $return = XR_SQL_QUERY($db, $sql);
}

// Ende von function

/**
 * Speichern der vorhandenen Text (txt, odt, pdf, ..) Dateien
 *
 * @param array $db
 *            Datenbak Handle
 * @param string $V_Pfad
 *            Dateipfad
 * @param string $File
 *            Dateiname
 * @param array $Stat
 *            Datei Informationen
 */
function XR_Text_Add($db, $V_Pfad, # Pfad ab Root
$File, # Dateiname
$Stat) # Status
        #
        // --------------------------------------------------------------------------------
{
    global $debug, $Project;

    $Table = "doc_t_dateien_$Project";
    $Size = $Stat['size'];
    $L_Aend_T = date("Y-m-d", $Stat['atime']);
    $L_Acc_T = date("Y-m-d", $Stat['mtime']);

    $sql = "INSERT INTO $Table (F_Dir,F_Name,F_Groesse,F_L_Aend,F_L_Use)
                    VALUES ('$V_Pfad','$File','$Size','$L_Acc_T','$L_Aend_T')       ";
    $return = XR_SQL_QUERY($db, $sql);
}

// Ende von function

/**
 * Speichern dr Verzeichnisse
 *
 * @param array $db
 *            Datenbank handle
 * @param string $V_Pfad
 *            Pfad in dem das Verzeichnis ist
 * @param string $File
 *            Vezeichnisname
 * @param number $V_cnt
 *            Anzahl der Verzeichnisse eingefügt
 */
function XR_Dir_Add($db, $V_Pfad, # Pfad ab Root
$File, # Dateiname
&$V_cnt)
#
// --------------------------------------------------------------------------------
{
    global $debug, $Project;

    # echo "XR_Funcs L 438: \$V_Pfad $V_Pfad \$File $File <br/>";
    if ($V_Pfad == "" && $File == "") {
        return;
    }

    # $stat = stat($Filename);
    # $sta[]: 0 .. Device Number, 7 .. size, 8 . Time last Access, 9 . Time Last Mod
    # $stat['size'] $stat['atime'] $stat['mtime']
    $Table = "doc_verzeichn_$Project";

    $sql = "SELECT * FROM doc_verzeichn_$Project WHERE V_Pfad='$V_Pfad' AND V_Name='$File'";
    $return = XR_SQL_QUERY($db, $sql);
    # print_r($return); echo "<br/>L 0477 \$return \$sql $sql ". mysqli_error($db)."<br/> ";
    $numrows = mysqli_num_rows($return);
    if ($numrows == 0) {
        $sql = "INSERT INTO $Table (V_Pfad,V_Name)
                    VALUES ('$V_Pfad','$File')       ";
        $return = XR_SQL_QUERY($db, $sql);
        $V_cnt ++;
    }
}

// Ende von function

/**
 * Hinzufügen von Dateien in die jeweiligen Gruppen (Grafik, Script, test, ..)
 *
 * @param array $db
 *            Datenbank Handle
 * @param string $V_Pfad
 *            Pfad
 * @param string $File
 *            Dateiname
 * @param array $Stat
 *            Dateiinformatione
 */
function XR_File_Add($db, $V_Pfad, # Pfad ab Root
$File, # Dateiname
$Stat) # Statistik der Datei
        #
        // --------------------------------------------------------------------------------
{
    global $debug, $Projects;
    # print_r($Dir_Arr);
    # echo "XR_Funcs L 236: \$V_Pfad $V_Pfad \$File $File <br/>";
    if ($V_Pfad == "") {
        $V_Pfad = "/";
    }
    if ($File === "." || $File === ".." || $File === ".buildpath" || $File === ".project" || $File === ".settings" || mb_stristr($File, "thumb_")) { // || $File === "$Project"
        return;
    }

    if (mb_stristr($File, 'htaccess', 'UTF-8')) {
        XR_Htacc_Add($db, $V_Pfad, $File, $Stat);
    }

    foreach (GrafFiles as $needle) {
        if (mb_stristr($File, $needle, 'UTF-8')) {
            XR_Graf_Add($db, $V_Pfad, $File, $Stat);
        }
    }

    foreach (ScrFiles as $needle) {
        if (mb_stristr($File, $needle, 'UTF-8')) {
            # echo "<br/>XR_Anal_Struct L 250: HTML und Co \$File $File <br/>";
            XR_Script_Add($db, $V_Pfad, $File, $Stat);
        }
    }
    foreach (TxtFiles as $needle) {
        if (mb_stristr($File, $needle, 'UTF-8')) {
            # echo "<br/>XR_Anal_Struct L 256: HTML und Co \$V_Pfad $V_Pfad - \$File $File <br/>";
            XR_Text_add($db, $V_Pfad, $File, $Stat);
        }
    }
}

// Ende von function

// --------------------------------------------------------------------------------
# Unterprogramm zum Speichern der Sib_Verzeichnis--Daten
# Fügt ein:
# -
# -
# -
#
/**
 * Analyse des Dateiformates und dann einfügen der Daten i die jeweilige Referenz Tabelle
 *
 * @param array $db
 *            Datenbank Handle
 * @param string $F_Id
 *            Record- Nummer
 */
function XR_File_Ref_Add($db, $F_Id) # Datensat-Nummer
                                      #
                                      // --------------------------------------------------------------------------------
{
    global $debug, $Project, $Caller, $Zeil_Nr;
    # print_r($Dir_Arr);
    # echo "XR_Funcs L 547:\$Caller $Caller \$F_Id $F_Id <br/>";

    foreach (GrafFiles as $needle) {

        if (mb_stristr($Caller, $needle, 'UTF-8')) {
            XR_Graf_Ref_Add($db, $F_Id);
        }
    }

    foreach (ScrFiles as $needle) {
        if (mb_stristr($Caller, $needle, 'UTF-8')) {
            XR_Script_Ref_Add($db, $F_Id);
        }
    }

    foreach (TxtFiles as $needle) {
        if (mb_stristr($Caller, $needle, 'UTF-8')) {
            # echo "<br/>DOC_Anal_Struct L 524: Text, csv und Log \$File $File <br/>";
            XR_Text_Ref_Add($db, $F_Id);
        }
    }
}

// Ende von function

/**
 * Abspeichern der Referenzierung der Grafk Dateien
 *
 * @param array $db
 *            Datenbank Handle
 * @param
 *            Record- Nummer $F_Id
 */
function XR_Graf_Ref_Add($db, $F_Id) # Satz Nummer der aufgerufenen Datei
                                      #
                                      // --------------------------------------------------------------------------------
{
    global $debug, $Project, $Caller, $Zeil_Nr, $Src_Path; // global $debug, $db, $Project, $GrafFiles,$ScrFiles,$TxtFiles,$Caller,$Zeil_Nr;

    # echo "XR_Funcs L 597: \$Caller $Caller \$F_Id $F_Id <br/>";

    $Table = "doc_g_dat_ref_$Project";

    $sql = "INSERT INTO $Table (F_Id,R_Unter,R_Zeilenrnr)
                    VALUES ('$F_Id','$Caller','$Zeil_Nr')       ";
    $return = XR_SQL_QUERY($db, $sql);
}

// Ende von function

/**
 * Abspeichern der Referenzierung der Script Dateien
 *
 * @param array $db
 *            Datenbank Handle
 * @param
 *            Record- Nummer $F_Id
 */
function XR_Script_Ref_Add($db, $F_Id) # Satz Nummer der aufgerufenen Datei
                                        #
                                        // --------------------------------------------------------------------------------
{
    global $debug, $Project, $Caller, $Zeil_Nr, $Src_Path;

    $Table = "doc_dat_ref_$Project";

    $sql = "INSERT INTO $Table (F_Id,R_Unter,R_Zeilenrnr)
                    VALUES ('$F_Id','$Caller','$Zeil_Nr')       ";
    $return = XR_SQL_QUERY($db, $sql);
}

// Ende von function

/**
 * Abspeichern der Referenzierung der Text Dateien
 *
 * @param array $db
 *            Datenbank Handle
 * @param
 *            Record- Nummer $F_Id
 */
function XR_Text_Ref_Add($db, $F_Id) # Satz Nummer der aufgerufenen Datei
                                      #
                                      // --------------------------------------------------------------------------------
{
    global $debug, $Project, $Caller, $Zeil_Nr, $Src_Path;

    # echo "XR_Funcs L 651: \$V_Pfad $V_Pfad \$File $File <br/>";

    $Table = "doc_t_dat_ref_$Project";

    $sql = "INSERT INTO $Table (F_Id,F_Unter,F_Zeilennr)
                    VALUES ('$F_Id','$Caller','$Zeil_nr')       ";
    $return = XR_SQL_QUERY($db, $sql);
    # print_r($return); echo "<br/>L 0425 \$return \$sql $sql ". mysqli_error($db)."<br/> ";
    # print_r($return);
    # echo "<br/> XR_text_add L 793: \$return \$sql $sql <br/>";
}

// Ende von function

/**
 * Recurviver Scan des Projektes
 *
 * @function recursive_scan
 * @description Recursively scans a folder and its child folders
 * @param $path ::
 *            Path of the folder/file
 */
function XR_recursive_scan($path)
{
    # global $file_info;
    $path = rtrim($path, '/');
    if (! is_dir($path)) {
        $file_info[] = $path;
    } else {
        $files = scandir($path);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {

                XR_recursive_scan($path . '/' . $file);
            }
        }
    }
}

/**
 * Abspeichern der Funktionsdefinitionen
 *
 * @param array $db
 *            Datenbank Handle
 * @param string $T_Id
 *            Record. Nr. der Bibliothek
 * @param string $FD_Fnkt_Name
 *            Funktionsname
 * @param number $FD_Zeile
 *            Zeilennummer
 */
function XR_Func_Add($db, $T_Id, # ID-Nr desFunkt_Dsn
$FD_Fnkt_Name, # Funktions-Name
$FD_Zeile) # Zeilennr.
            #
            // --------------------------------------------------------------------------------
{
    global $debug, $Project;

    $Table = "doc_funkt_def_$Project";

    $sql = "SELECT * FROM $Table WHERE T_Id='$T_Id' AND FD_Fnkt_Name='$FD_Fnkt_Name'";
    $return = XR_SQL_QUERY($db, $sql); // // print_r($return); echo "<br/>L 0484 \$return \$sql $sql ". mysqli_error($db)."<br/> ";
    $numrows = mysqli_num_rows($return);
    if ($numrows === 0) {
        $sql = "INSERT INTO $Table (T_Id,FD_Fnkt_Name,FD_Zeile)
                    VALUES ('$T_Id','$FD_Fnkt_Name','$FD_Zeile')       ";
        $return = XR_SQL_QUERY($db, $sql);
    }
}

// Ende von function

/**
 * Abspeichern der Referzerung der Fuktionen
 *
 * @param array $db
 *            Datnbank Handle
 * @param number $FD_Id
 *            ibliotheks- Nummer
 */
function XR_Func_Ref_Add($db, $FD_Id) # ID-Nr des Funkt_Dsn
                                       #
                                       // --------------------------------------------------------------------------------
{
    global $debug, $Project, $Caller, $Zeil_Nr;

    # echo "XR_Funcs L 512: \$Call_Func $Call_Func \$Caller $Caller \$Zeil_Nr $Zeil_Nr <br/>";

    $Table = "doc_funkt_ref_$Project";
    $sql = "INSERT INTO $Table (FD_Id,R_Name,R_Zeile)
                    VALUES ('$FD_Id','$Caller','$Zeil_Nr')       ";
    $return = XR_SQL_QUERY($db, $sql);
} // Ende von function

?>
