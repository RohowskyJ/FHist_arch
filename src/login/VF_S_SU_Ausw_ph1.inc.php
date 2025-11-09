<?php 
/**
 * Auswahl nach Suchbegriffen zur Anzeige, Verarbeitung der Such-Parameter
 *
 * @author Josef Rohowsky - neu 2018
 *
 * Abfrage nach was / mit welchen Möglichkeiten gesucht werden soll
 *
 *
 *
 */

/**
 * Includes-Liste
 * enthält alle jeweils includierten Scritpt Files
 */
$_SESSION[$module]['Inc_Arr'][] = "VF_S_Ausw_ph1.inc.php";

if ($debug) {echo "<pre class=debug>VF_S_Ausw_ph1.inc.php ist gestarted</pre>";}

# print_r($_POST);echo "<br>L 015 post <br>";
#----------------------------------------------------------------------------------
#var_dump($_POST);

# var_dump($neu);

$Sel_Text = array("Eignr" => "A","Such"=>"SB");     // Anzeige der Such- Parameter

if (isset($_POST['suchumfang'])) {
    $_SESSION[$module]['su_Umf'] = "A";
    foreach ( $_POST['suchumfang'] as $key) {
        $_SESSION[$module]['su_Umf'] .= $key;
    }

} else {$_SESSION[$module]['su_Umf'] = "A,I,G,F";} # $s_suchumfang="";

if (isset($_POST['suche_bei'])) {
    if ($_POST['suche_bei'] === "auswahl") {
       if (isset($_POST['eigentmr']))   {
           $eig_arr = explode("-",$_POST['eigentmr']);
           $_SESSION[$module]['su_Eig'] = $eig_arr[0];} 
    } elseif ($_POST['suche_bei'] === "allen"){
        $_SESSION[$module]['su_Eig'] = "A";
    }
    # $s_suchb_ausw = $_POST['suchb_ausw']; 
} else {
    $_SESSION[$module]['su_Eig'] = "A";}  # $s_suchb_ausw="";

if (isset($_POST['suchb_ausw']))  {
    $s_suchb_ausw = $_POST['suchb_ausw'];
    if ($_POST['suchb_ausw'] == "sammlung") {$_SESSION[$module]['su_Su']  = "SA";}
    if ($_POST['suchb_ausw'] == "findbuch") {$_SESSION[$module]['su_Su']  = "FI";}
    if ($_POST['suchb_ausw'] == "namen") {$_SESSION[$module]['su_Su']  = "NA";}
    if ($_POST['suchb_ausw'] == "ffname") {$_SESSION[$module]['su_Su']  = "FF";}
    if ($_POST['suchb_ausw'] == "autnam") {$_SESSION[$module]['su_Su']  = "AU";}
    
    $s_suchb_ausw = $_POST['suchb_ausw'];
}

# $_SESSION[$module]['su_Su']  = "SB";

if (isset($_POST['Submit'])) {$name = $_POST['Submit'];} else {$name="";}
if (isset($_POST['suchtext'])) {$s_suchtext = $_POST['suchtext'];} else {$s_suchtext  ="";}
if (isset($_POST['suchname'])) {$s_suchname = $_POST['suchname'];} else {$s_suchname  = "";}
if (isset($_POST['suchffname'])) {$s_suchffname = $_POST['suchffname'];} else {$s_suchffname="";}
if (isset($_POST['suchztauth'])) {$s_suchztauth = $_POST['suchztauth'];} else {$s_suchztauth="";}

$eignr = "";

# Suche nach Eigentümer oder alle
if ($_SESSION[$module]['su_Eig'] == "A") {  // suche über alle Eigentümer
    $eig_arr = array();
    $i = 0;
    $sql_eig  = "SELECT *  FROM `fh_eigentuemer`  ORDER BY `ei_id`";
    $return_eig  = SQL_QUERY($db,$sql_eig);
    while ($row = mysqli_fetch_object($return_eig)) {
        $eig_arr[$i] = "$row->ei_id";
        $i++;
    }
    mysqli_free_result($return_eig); 
} else {                                    // suche nur beim gewählten Eigner
    $eig_arr[0] = $_SESSION[$module]['su_Eig'];
}

$ar_arr = array();
$dm_arr = array();
$in_arr = array();
$maf_arr = array();
$mag_arr  = array();
$muf_arr  = array();
$mug_arr  = array();
$ge_arr = array();
$zt_arr = array();
$tables_act = VF_tableExist();          // Array der existierenden Tabellen
#  print_r($in_arr);echo "<br>in_arr <br";
if (!$tables_act) {
    echo "keine Tabellen gefunden - ABBRUCH <br>";
    exit;
}
# var_dump($dm_arr);
if (isset($_POST['eigentmr']))      {$_SESSION[$module]['eig_ausw'] = $s_such_eigent    = $_POST['eigentmr'];}

if (isset($_POST['s_such_eigent'])) {$s_such_eigent    = $_POST['s_such_eigent'];}

$arc_liste = $inv_liste = $fzg_1_liste = $fzg_2_liste = $ger_liste = $mug_liste = $foto_liste = $zeitg_liste = $auth_liste = "";

if ($s_suchb_ausw == "sammlung") {
    
    /**
     * Sammlung auswählen, Input- Analyse
     */
    if (isset($_POST['level1'])) {
        $response = VF_Multi_Sel_Input();
        if ($response == "" || $response == "Nix" ) {
            
        } else {
            $neu['s_suchtext'] = $_SESSION[$module]['sammlung'] = $_SESSION[$module]['sammlung'] = $response;
        }
    }

    echo "sammlung <br>";
 #    echo "SU_Ausw L 0160: \$s_suchb_ausw $s_suchb_ausw \$s_suchtext $s_suchtext <br>";
    $titel = "<legend><font size=\"-1\">Verein Feuerwehrhistoriker in NÖ</font><br/>Suchen nach Sammlung: </legend>";
    #if (isset($_POST['sammlg'])) {$s_suchtext = $_POST['sammlg'];} else {$s_suchtext = "";}
    if ($neu['s_suchtext']=="") {
        $phase = 0;
    } else {
        $s_titel  = ", Suche in der Sammlung <cite>".$neu['s_suchtext']."</cite>";
        require "VF_S_Find_Inv_sa.inc.php" ;
        if (substr($neu['s_suchtext'],0,4) == "MU_F" ) {
            require "VF_S_Find_MuF_sa.inc.php";
        } elseif (substr($neu['s_suchtext'],0,4) == "MU_G" ) {
            require "VF_S_Find_MuG_sa.inc.php";
        } elseif (substr($neu['s_suchtext'],0,4) == "MA_F" ) {
            require "VF_S_Find_MaF_sa.inc.php";
        } elseif (substr($neu['s_suchtext'],0,4) == "MA_G" ) {
            require "VF_S_Find_MaG_sa.inc.php";
        }

    }
    
} elseif ($s_suchb_ausw == "findbuch") {
    echo "findbuch <br>";
    $titel = "<legend><font size=\"-1\">Verein Feuerwehrhistoriker in NÖ</font><br/>Suchen nach Namen (Archivalien im Findbuch): </legend>";
    if ($s_suchtext=="") {
        $phase = 0;
    } else {
        $s_titel  = ", Suche im Findbuch <cite>$s_suchtext</cite>";
        $arc_liste = "";
        # require "VF_S_Find_Arc_fb.inc.php" ;
        FindB_Archiv ($s_suchtext);
        $foto_liste = "";
        FindB_Foto ($s_suchtext);
        
    }
} elseif ($s_suchb_ausw == "namen") {
    if ($debug) {
        echo "Suche nach Namen $s_suchname <br>";
    }
    $titel = "<legend><font size=\"-1\">Verein Feuerwehrhistoriker in NÖ</font><br/>Suchen nach Namen (Archivalien, Museales und Fotos im Namensfindbuch): </legend>";
    if ($s_suchname=="") {
        $phase = 0;
    } else {
        $s_titel  = ", Suche nach Namen <cite>$s_suchname</cite>";
        if (stripos($_SESSION[$module]['su_Umf'],"I") >= 1 ) {   // Suchen in Inventar und Archiv) 

            require "VF_S_Find_Arc_na.inc.php" ;
            require "VF_S_Find_Inv_na.inc.php" ;
        }
        if (stripos($_SESSION[$module]['su_Umf'],"F") >= 1 ) {   // Suchen in Fotografien
            require "VF_S_Find_Foto_na.inc.php" ;
        }
    }
} elseif ($s_suchb_ausw == "ffname") {
    $titel = "<legend><font size=\"-1\">Verein Feuerwehrhistoriker in NÖ</font><br/>Suchen nach Name einer Feuerwehr: </legend>";
    if ($s_suchffname=="") {
        $phase = 0;
    } else {
        $s_titel  = ", Suche nach Feuerwehrnamen <cite>$s_suchffname</cite>";
        require "VF_S_Find_FNam.inc.php" ;
    }
} elseif ($s_suchb_ausw == "autnam") {
    $titel = "<legend><font size=\"-1\">Verein Feuerwehrhistoriker in NÖ</font><br/>Suchen nach Authoren: </legend>";
    if ($s_suchztauth=="") {
        $phase = 0;
    } else {
        $s_titel  = ", Suche nach Authoren <cite>$s_suchztauth</cite>";
        require "VF_S_Find_Auth.inc.php" ;
    }
}

if (!empty($arc_liste)) {
    require 'VF_S_Find_Arc_List.inc.php' ;
}
if (!empty($inv_liste)) {
    require 'VF_S_Find_Inv_List.inc.php' ;
}
if (!empty($foto_liste)) {
    require 'VF_S_Find_Foto_List.inc.php' ;
}
if (!empty($fzg_1_liste)) {
    require 'VF_S_Find_MuF1_List.inc.php' ;
}
if (!empty($fzg_2_liste)) {
   require 'VF_S_Find_MaF2_List.inc.php' ;
}
if (!empty($ger_liste)) {
    require 'VF_S_Find_MaG_List.inc.php' ;
}
if (!empty($mug_liste)) {
    require 'VF_S_Find_MuG_List.inc.php' ;
}
if (!empty($zeitg_liste)) {
    require 'VF_S_Find_FNam_List.inc.php' ;
}
if (!empty($auth_liste)) {
    require 'VF_S_Find_Auth_List.inc.php' ;
}

echo "<a href='VF_S_SU_Ausw.php'>Suche neu starten</a>";

# =========================================================================================================
 $debug= False;
if ($debug) {echo "<pre class=debug>VF_S_Ausw_ph1.inc beendet</pre>";}


/**
 * Findbuch- Auswahl Archiv-Daten
 * Liste der Daten wird in global $arc_liste übergeben
 * 
 * @param string Suchbegriff
 * @return boolean
 * 
 */
Function FindB_Archiv ($suchbegr)
{
    global $db, $debug,$arc_liste;
    
    $sql_in = "SELECT * FROM  `fh_findbuch` WHERE fi_table like '%ar_chivdt_%' ";
    
    echo "<div class='toggle-SqlDisp'>";
    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>SU Findb Arch $sql_in </pre>";
    echo "</div>";
    
    $return_in = SQL_QUERY($db, $sql_in);
    while ($row = mysqli_fetch_object($return_in)) {
        $s_usuchname = strtoupper($suchbegr);
        $s_unaname = strtoupper($row->fi_suchbegr);
        if (strstr($s_unaname, $s_usuchname)) {
            if ($arc_liste == "") {
                $arc_liste = "$row->fi_table|$row->fi_fdid";
            } else {
                $arc_liste .= ",$row->fi_table|$row->fi_fdid";
            }
        }
    }
    return true;
} # Ende Funktion FindB_Archiv

/**
 * Findbuch- Auswahl Foto-Daten
 * Liste der Daten wird in global $arc_liste übergeben
 *
 * @param string Suchbegriff
 * @return boolean
 */
Function FindB_Foto ($suchbegr)
{
    global $db, $debug,$foto_liste;
    
    $sql_in = "SELECT * FROM  `fh_findbuch` WHERE fi_table like '%fo_todaten_%' ";
    
    echo "<div class='toggle-SqlDisp'>";
    echo "<pre class=debug style='background-color:lightblue;font-weight:bold;'>SU FindB Foto $sql_in </pre>";
    echo "</div>";
    
    $return_in = SQL_QUERY($db, $sql_in);
    while ($row = mysqli_fetch_object($return_in)) {
        $s_usuchname = strtoupper($suchbegr);
        $s_unaname = strtoupper($row->fi_suchbegr);
        if (strstr($s_unaname, $s_usuchname)) {
            if ($foto_liste == "") {
                $foto_liste = "$row->fi_table|$row->fi_fdid";
            } else {
                $foto_liste .= ",$row->fi_table|$row->fi_fdid";
            }
        }
    }
    return true;
} # Ende Funktion FindB_Archiv

?>