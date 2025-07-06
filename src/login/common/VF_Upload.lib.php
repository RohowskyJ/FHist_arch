<?php
/**
 * PHP Funktionen für Daten- Upload, Fokus auf Foto- und Dokumenten- Upload
 * 
 * Neu 20250614, Neufassung bestehender Scripts
 * 
 *    folgende Funktionen sind in diesr Datei:
 *  BA_M_Foto      Funktion zur Anzeige, eingabe der Daten, Auswahl Urheber + Aufnahmedatum und Datei
 *  BA_M_Upl_Pfad  Funktion zur generierung des Pfades zur Abspeicherung der hochgeladenen Daten
 *  BA_M_Upload    Funktion zum uploaden mit Umbenennen   
 */

/**
 * Formular- Teil zum hochladen von mehrfach-Dateien (fotos, Dokumente, ..) Modifizierte Vwers
 *
 *
 * @return
 *
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 * @global array $db Datenbank Handle
 * @global array $neu Eingelesene Daten Felder
 * @global array $Tabellen_Spalten_COMMENT Global Array (Schlüssel: Spaltenname) mit Texten zu den Spalten
 * @global string $flow_lost True = Ausgabe der Aufruf- Trace
 * @global boolean $hide_area True - Bereich nur bei Neueingabe oder klicken auf Button Anzeigen (Ausser Foto, da nur die leeren nicht anzeigen)
 * @global string  §path2ROOT Pfad zum Root
 */
function VF_M_Foto_N ()
{
    global $debug, $db, $neu, $module, $Tabellen_Spalten_COMMENT, $flow_list, $hide_area, $path2ROOT;
    
    flow_add($module,"VF_Upload.lib.php Funct: VF_M_Foto" );

    /**
     * Parameter für die Fotos:
     *
     * $_SESSION[$module]['Pct_Arr'][] = array("k1" => 'fz_b_1_komm', 'b1' => 'fz_bild_1', 'rb1' => '', 'up_err1' => '', 'f1' => '','f2'=>'');
     * wobei k1 = blank : kein Bild- Text- Feld - kein Bildtext , keinegeminsame Box, rb1 und up_err werden vom Uploader gesetzt,
     *                           f1 und f2 sind 2 Felder, die zusätzlich im Block eingegeben, angezeigt werden können
     */
    /* Schalten der Foto- Update blöcke */

    if (!isset($hide_area)) {$hide_area = 0;}
    $hide_area_group1 = $hide_area_group2 = $hide_area;

    if ($debug) {
        echo "<pre class=debug>VF_M_Foto L Beg: \$Picts ";
        var_dump($_SESSION[$module]['Pct_Arr']);
        echo "<pre>";
    }
    
    $pic_cnt = count($_SESSION[$module]['Pct_Arr']);
    #console_log('L 048 Anz Upl. '.$pic_cnt);
    #var_dump($_SESSION[$module]['Pct_Arr']);
    # var_dump($neu);
    
    /**
     * Floating Block mit Bild, Bildbeschreibung , Bildname und Upload-Block
     */
    echo "<div class='w3-container'>";                           // container für Foto und Beschreibung
    #console_log('L 056 vor class w3-row ');
    echo "<div class = 'w3-row w3-border'>";                     // Responsive Block start
    echo "<fieldset>";
    #console_log('L 059 vor pct_arr loop ');
    
    # var_dump($_SESSION[$module]['Pct_Arr']);
    
    ?>

    <div style="margin-bottom:20px; border:1px solid #ccc; padding:10px;"> 
            
         
          <div id="gruppe1" class="foto-upd-container" 
              style="<?php echo ($hide_area_group1 == 1) ? 'display:none;' : ''; ?>">
             <div class="foto-upd">
             <p>Optionale Parameter für Upload, noch in Planung für Upload mit Bildbearbeitung.</p>
              <!-- 
                 <! -- Auswahl: Bibliothek oder Upload -- >
                 <p>Fotos aus den Foto-Bibliotheken einfügen?</p>
                 <label>
                     <input type='radio' class='sel_libs' name='sel_libs' value='Ja' checked> Ja
                 </label>
                 <label>
                     <input type='radio' class='sel_libs' name='sel_libs' value='Nein'> Nein - Neu hochladen
                 </label>
        
        		
        		<! -- 
        		wenn sel_libs: Ja    -- Auswahl nach Sammlung, Anzeige fotos, auswahl in Menge der Fotos
        		                        ausgesuchter Dsn in das entsprechenden Eigabefeld stellen
        		               Nein  -- normale Eingabe über File-Upload, bevorzugt AJAX mit resize und Wasserzeichen,
        		                        Eingabe Urheber, Aufnahmedatum, Wasserzeichen J/N, 
        		
                <div id='upl_libs' style='displ:none'>
                  <p>Aussuchen aus libs
                   </p>
                </div>
              
                <div id='upl_new' style='displ:none'>
                  hochladen
                
                </div>
        --- >
                <div id='upl_libs' style='display:block; margin-top:10px;'>
                 <p>Suchbegriff für die Bibliothek:</p>
                 <input type='text' id='suche' placeholder='Suchbegriff' />
                 <button type='button' onclick='sucheBibliothek()'>Suchen</button>
                    <div id=' suchergebnis' style='margin-top:10px; max-height:150px; overflow:auto; border:1px solid #ccc;'></div>
                </div>    
             -->
             </div>
         </div>
        
    <?php 
    
    for ($i=0; $i < $pic_cnt; $i++) {
        $p_a = $_SESSION[$module]['Pct_Arr'][$i];
 
        // Entscheidung, ob versteckt wird bei bestehendem Daten
        $hide_upl = $hide_bild = '';
        if ($hide_area == 1) {
            if (empty($neu[$p_a['ko']]) && empty($neu[$p_a['bi']])) {
                $hide_upl = $hide_bild = 'hide'; // wird versteckt
            }
        }

        $j = $i +1; /** Für die Bil- Nr- Anzeige */

        $pict_path = VF_Upload_Pfad_M ('', '', '', '');
        
        /**
         * Responsive Container innerhalb des loops
         */
        echo "<div class = 'block-container w3-container w3-half ' data-index='$i'  data-hide-area='$hide_area'>";                 // start half contailer
        echo "<fieldset>";
        echo "Bild $j <br>";
        #echo "<div class='bild-data >";
        
             
            #console_log('L 0128 komm '.$key);
            if ($p_a['ko'] != "") {
                if (isset($Tabellen_Spalten_COMMENT[$p_a['ko']])) {
                    echo $Tabellen_Spalten_COMMENT[$p_a['ko']];
                } else {
                    echo $p_a['ko'];
                }
                echo "<textarea class='w3-input' rows='7' cols='20' name='".$p_a['ko']."' >" . $neu[$p_a['ko']] . "</textarea> ";
            }
            if ($p_a['f1'] != '')  {
                Edit_Daten_Feld_Button($p_a['f1'],30);
            }
            if ($p_a['f2'] != '')  {
                Edit_Daten_Feld_Button($p_a['f2'],30);
            }
            
            echo "<div class='bild-detail' >";
           
            if ($neu[$p_a['bi']] != "") {
                $fo = $neu[$p_a['bi']];
                #console_log('L 02528 foto '.$fo);
                $fo_arr = explode("-",$neu[$p_a['bi']]);
                $cnt_fo = count($fo_arr);
                
                if ($cnt_fo >=3) {   // URH-Verz- Struktur de dsn
                    $urh = $fo_arr[0]."/";
                    $verz = $fo_arr[1]."/";
                    if ($cnt_fo > 3)  {
                        if (isset($fo_arr[3]))
                            $s_verz = $fo_arr[3]."/";
                    }
                    $p = $path2ROOT ."login/AOrd_Verz/$urh/09/06/".$verz.$neu[$p_a['bi']] ;
                    
                    if (!is_file($p)) {
                        $p = $pict_path . $neu[$p_a['bi']];
                    }
                } else {
                    $p = $pict_path . $neu[$p_a['bi']];
                }

                $f_arr = pathinfo($neu[$p_a['bi']]);
                if ($f_arr['extension'] == "pdf") {
                    echo "<a href='$p' target='Bild $j' > Dokument</a>";
                } else {
                    echo "<a href='$p' target='Bild $j' > <img src='$p' alter='$p' width='200px'></a>";
                    echo $neu[$p_a['bi']];
                }

            } else {
                echo "kein Bild hochgeladen";
            }
            #echo "</div>";
            echo "</div>";
        ?>
        
        <div id="gruppe2" class="foto-upd-container" 
            style="<?php echo ($hide_area_group2 == 1) ? 'display:none;' : ''; ?>">
           <div class='foto-upd'  style='margin-bottom:20px; border:1px solid #ccc; padding:10px;'> 
          
            <h3>Bild <?php echo $j; ?></h3>
            <input type='hidden' id='foto_<?php echo $j; ?>' name='foto_<?php echo $j; ?>' value=''>

            <!-- Hochladen Bereich -->
            <div id='upl_new_<?php echo $j; ?>' margin-top:10px;'>
                <input type='file' name='datei_<?php echo $j; ?>' />
            </div>
          </div>   
        </div>

        <?php
        
        echo "</fieldset>";
        echo "</div>";  
    }
    
    echo "</fieldset>";
    echo "</div>";  // Responsive Block end
    echo "</div>";        // end container
    
} // end VF_M_Foto

function VF_M_Foto_N_ori ()
{
    global $debug, $db, $neu, $module, $Tabellen_Spalten_COMMENT, $flow_list, $hide_area, $path2ROOT;
    
    flow_add($module,"VF_Upload.lib.php Funct: VF_M_Foto" );
    
    /**
     * Parameter für die Fotos:
     *
     * $_SESSION[$module]['Pct_Arr'][] = array("k1" => 'fz_b_1_komm', 'b1' => 'fz_bild_1', 'rb1' => '', 'up_err1' => '', 'f1' => '','f2'=>'');
     * wobei k1 = blank : kein Bild- Text- Feld - kein Bildtext , keinegeminsame Box, rb1 und up_err werden vom Uploader gesetzt,
     *                           f1 und f2 sind 2 Felder, die zusätzlich im Block eingegeben, angezeigt werden können
     */
    
    if ($debug) {
        echo "<pre class=debug>VF_M_Foto L Beg: \$Picts ";
        var_dump($_SESSION[$module]['Pct_Arr']);
        echo "<pre>";
    }
    
    $pic_cnt = count($_SESSION[$module]['Pct_Arr']);
    console_log('L 048 Anz Upl. '.$pic_cnt);
    #var_dump($_SESSION[$module]['Pct_Arr']);
    # var_dump($neu);
    
    /**
     * Floating Block mit Bild, Bildbeschreibung , Bildname und Upload-Block
     */
    echo "<div class='w3-container'>";                           // container für Foto und Beschreibung
    #console_log('L 056 vor class w3-row ');
    echo "<div class = 'w3-row w3-border'>";                     // Responsive Block start
    echo "<fieldset>";
    #console_log('L 059 vor pct_arr loop ');
    
    var_dump($_SESSION[$module]['Pct_Arr']);
    
    ?>

    <div style="margin-bottom:20px; border:1px solid #ccc; padding:10px;"> 
       
             <!-- Optional: Parameter-Input -->
             <div class="file-upload-block" id="parameterBlock">
                 <!-- Auswahl: Bibliothek oder Upload -->
                 <p>Fotos aus den Foto-Bibliotheken einfügen?</p>
                 <label>
                     <input type='radio' class='sel_libs' name='sel_libs' value='Ja' checked> Ja
                 </label>
                 <label>
                     <input type='radio' class='sel_libs' name='sel_libs' value='Nein'> Nein - Neu hochladen
                 </label>
        
        		
        		<!-- 
        		wenn sel_libs: Ja    -- Auswahl nach Sammlung, Anzeige fotos, auswahl in Menge der Fotos
        		                        ausgesuchter Dsn in das entsprechenden Eigabefeld stellen
        		               Nein  -- normale Eingabe über File-Upload, bevorzugt AJAX mit resize und Wasserzeichen,
        		                        Eingabe Urheber, Aufnahmedatum, Wasserzeichen J/N, 
        		--->
                <div id='upl_libs' style='displ:none'>
                  <p>Aussuchen aus libs
                   </p>
                </div>
              
                <div id='upl_new' style='displ:none'>
                  hochladen
                
                </div>
        
                <div id='upl_libs' style='display:block; margin-top:10px;'>
                 <p>Suchbegriff für die Bibliothek:</p>
                 <input type='text' id='suche' placeholder='Suchbegriff' />
                 <button type='button' onclick='sucheBibliothek()'>Suchen</button>
                    <div id=' suchergebnis' style='margin-top:10px; max-height:150px; overflow:auto; border:1px solid #ccc;'></div>
                </div>    
            </div>

    <?php 
    
    for ($i=0; $i < $pic_cnt; $i++) {
        $p_a = $_SESSION[$module]['Pct_Arr'][$i];

        console_log('L 0107 foto '.$i);
        $j = $i +1;
        #var_dump($p_a);echo "L 02504 hide_area $hide_area <br>";
        
        #echo $neu[$p_a['ko']]. " ".$p_a['bi'] . " " . $neu[$p_a['f1']]." ". $p_a['f2'] ."<br>";
        
        # if ($hide_area == 0 || ($hide_area == 1 && ($neu[$p_a['ko']] != '' || $neu[$p_a['bi']] != ''  ))) {
            #console_log('L 02508 Bild '.$key);
            # echo "Bild- Box $key wird angezeigt <br>";
            $pict_path = VF_Upload_Pfad_M ('', '', '', '');
            
            /**
             * Responsive Container innerhalb des loops
             */
            echo "<div class = 'w3-container w3-half'>";                                  // start half contailer
            echo "<fieldset>";
            echo "Bild $j <br>";
            
            if ($hide_area == 0 || ($hide_area == 1 && ($neu[$p_a['ko']] != '' || $neu[$p_a['bi']] != ''  ))) {
                
            #console_log('L 0128 komm '.$key);
            if ($p_a['ko'] != "") {
                if (isset($Tabellen_Spalten_COMMENT[$p_a['ko']])) {
                    echo $Tabellen_Spalten_COMMENT[$p_a['ko']];
                } else {
                    echo $p_a['ko'];
                }
                echo "<textarea class='w3-input' rows='7' cols='25' name='".$p_a['ko']."' >" . $neu[$p_a['ko']] . "</textarea> ";
            }
            if ($p_a['f1'] != '')  {
                Edit_Daten_Feld_Button($p_a['f1'],30);
            }
            if ($p_a['f2'] != '')  {
                Edit_Daten_Feld_Button($p_a['f2'],30);
            }
            if ($neu[$p_a['bi']] != "") {
                $fo = $neu[$p_a['bi']];
                #console_log('L 02528 foto '.$fo);
                $fo_arr = explode("-",$neu[$p_a['bi']]);
                $cnt_fo = count($fo_arr);
                
                if ($cnt_fo >=3) {   // URH-Verz- Struktur de dsn
                    $urh = $fo_arr[0]."/";
                    $verz = $fo_arr[1]."/";
                    if ($cnt_fo > 3)  {
                        if (isset($fo_arr[3]))
                            $s_verz = $fo_arr[3]."/";
                    }
                    $p = $path2ROOT ."login/AOrd_Verz/$urh/09/06/".$verz.$neu[$p_a['bi']] ;
                    
                    if (!is_file($p)) {
                        $p = $pict_path . $neu[$p_a['bi']];
                    }
                } else {
                    $p = $pict_path . $neu[$p_a['bi']];
                }
                #console_log('L 02547 foto '.$p) ;
 
                $f_arr = pathinfo($neu[$p_a['bi']]);
                if ($f_arr['extension'] == "pdf") {
                    echo "<a href='$p' target='Bild $j' > Dokument</a>";
                } else {
                    #console_log('L 0110 ausgabe '.$p);
                    echo "<a href='$p' target='Bild $j' > <img src='$p' alter='$p' width='200px'></a>";
                    echo $neu[$p_a['bi']];
                }

            }
            
            ## Bilder neu auswählen /Ändern
            
        ?>


        <div class='file-upload-block' id='uploadBlock<?php echo $j; ?>' style="margin-bottom:20px; border:1px solid #ccc; padding:10px;"> 
          <div id='upl_new' >
            <h3>Bild <?php echo $j; ?></h3>
            <input type='hidden' id='foto_<?php echo $j; ?>' name='foto_<?php echo $j; ?>' value=''>

            <!-- Hochladen Bereich -->
            <div id='upl_new_<?php echo $j; ?>' margin-top:10px;'>
                <input type='file' name='datei_<?php echo $j; ?>' />
            </div>
          </div>  
        </div>

        <?php
        }
        echo "</fieldset>";
        echo "</div>";  
        

    }
    

    echo "</fieldset>";
    echo "</div>";  // Responsive Block end
    echo "</div>";        // end container
    
} // end VF_M_Foto
/**
 * Setzen des Speicherpfades per  Return zurückgegeben
 * 
 * 
 *
 * @param string $aufndat
 *            Datum oder Jahr der Aufnahme - oder Pfadname  - Darf nicht leer sein
 * @param string $basepfad
 *            Basispfad darf leer sein
 * @param string $suffix
 *            Zusatzpfad darf leer sein
 * @param string $aoPfad Archiv- Ordnungs- Teil, kann auch leer sein  
 * @param string $urh_nr Urheber- Nummer         
 *
 * @return string $d_path
 *
 * @global boolean $debug Anzeige von Debug- Informationen: if ($debug) { echo "Text" }
 * @global string $module Modul-Name für $_SESSION[$module] - Parameter
 *
 */
function VF_M_Upl_Pfad ($aufnDatum, $suffix='', $aoPfad='', $urh_nr = '')
{
    global $debug, $module, $flow_list, $path2ROOT;
 
    flow_add($module,"VF_Upload.lib.php Funct: VF_M_Upl_Pfad" );
    
    $basepath = $path2ROOT.'login/'.$_SESSION['VF_Prim']['store'].'/';
    
    $grp_path = $ao_path = $verzeichn = $subverz = "";
    
    $mand_mod = array('INV', 'ARC', 'FOT', 'F_G','F_M');
    
    if (in_array($module,$mand_mod)) { // Mandanten- Modus
        if ($urh_nr == "") {
            $grp_path = $_SESSION['Eigner']['eig_eigner'].'/';
        } else {
            $grp_path = $urh_nr.'/';
        }
        
        switch ($module) {
            case 'ARC' :
                break;
            case 'INV' :
                break;
            case 'F_G' :
                if ($aufnDatum == '') {
                    if (substr($_SESSION[$module]['sammlung'],0,4) == 'MA_F') {
                        $verzeichn =  'MaF/';
                    } else {
                        $verzeichn =  'MaG/';
                    }
                } else {
                    $verzeichn = $aoPfad.'/'.$aufnDatum.'/';
                }
                
                break;
            case 'F_M' :
                if ($aufnDatum == '') {
                    if (substr($_SESSION[$module]['sammlung'],0,4) == 'MU_F') {
                        $verzeichn =  'MuF/';
                    } else {
                        $verzeichn =  'MuG/';
                    }
                } else {
                    $verzeichn = $aoPfad.'/'.$aufnDatum.'/';
                }
                
                break; 
            case 'FOT' :
                $ao_path = $aoPfad.'/';
                $verzeichn =  $aufnDatum.'/';
                if ($fuffix != '') {
                    $subverz = $suffix.'/';
                }
                break; 
        }
        
    } else {
        switch ($module) {
            case 'OEF':
                break;
            case 'PSA':
                if($_SESSION[$module]['proj'] == 'AERM') {
                    $verzeichn = 'PSA/AERM/';
                } else {
                    $verzeichn = 'PSA/AUSZ/';
                }
                break;
                
        }
    }
    $dPath = $basepath.$grp_path.$ao_path.$verzeichn.$subverz;
    #echo "L 0236 UplLib dPath $dPath <br>";
    return $dPath;
    
} // end VF_M_Upl_Pfad


/**
 * Hochladen von Dateien
 *
 * Bei allen Dateien:  ändern Umlaute auf alte Schreibweise Ä -> AE
 * Bei Grafischen Dateien: wenn Urheber-Abkürzung und Foto-Datum vorhanden, Umbenennen nach Foto-Vorgabe (Urh-Datum-Dateiname)
 *
 *
 * @param string $uploaddir      Zielverzeichnis
 * @param string $i              index zur uploadfile $files[uploadfile_x
 * @param string $urh_abk        Abkürzung des Urhebernamens
 * @param string $fo_aufn_datum  Aufnahmedatum
 * @return string Dsn der Datei  Name der Datei zum Eintrag in Tabelle
 */
function VF_M_Upload ($uploaddir, $fdsn, $urh_abk="", $fo_aufn_datum="")
{
    global $module;
    flow_add($module,"VF_Comm_Funcs.inc Funct: VF_M_Upload" );
    
    # echo " L 02620 Upl upldir $uploaddir fdsn $fdsn <br>";
    # var_dump($_FILES[$fdsn]);
    $target = "";
    if ($_FILES[$fdsn]['name'] != "") {
        
        $target = basename($_FILES[$fdsn]['name']);
        
        if ($_FILES[$fdsn]['error'] >= 1) {
            $errno = $_FILES[$fdsn]['error'];
            $err = "Upload Fehler: ";
            switch ($errno) {
                case 1:
                case 2:
                    $err .= "Datei zu groß";
                    break;
                case 8:
                    $err .= "Falsche Datei (Erweiterung)";
                    break;
            }
            return $err;
        }
        
        if ($target != "" ) {
            $target = VF_trans_2_separate($target);
            
            $fn_arr = pathinfo($target);
            $ft = strtolower($fn_arr['extension']);
            
            if (in_array($ft, GrafFiles) && $urh_abk != "" && $fo_aufn_datum != "") {
                $newfn_arr = explode('-', $target);
                $cnt = count($newfn_arr);
                if ($cnt == 1) { # original- Dateiname, nicht im Format urh-datum-Aufn_dateiname.ext,
                    $target = "$urh_abk-$fo_aufn_datum-" . $fn_arr['basename'];
                }
            } else {
                $target = $fn_arr['basename'];
            }
            # echo "L 02658 fdsn $fdsn ; uploaddir $uploaddir; target $target <br>";
            # var_dump($_FILES[$fdsn]);
            if (move_uploaded_file($_FILES[$fdsn]['tmp_name'], $uploaddir . $target)) {
                # var_dump($_FILES[$fdsn]);
                return $target;
            }
        }
    }
    
} //  end VF_M_Upload

