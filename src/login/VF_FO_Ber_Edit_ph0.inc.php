<?php

/**
 * Liste der Veranstaltungsberichte, Wartung
 *
 * @author Josef Rohowsky - neu 2023
 *
 */
$Inc_Arr = array();
$Inc_Arr[] = "VF_BE_Edit_ph0.inc.php";

if ($debug) {
    echo "<pre class=debug>VF_BE_Edit_ph0.inc.php ist gestarted</pre>";
}

echo $Err_Msg;
echo "<input name='vb_flnr' id='vb_flnr' type='hidden' value='" . $neu['vb_flnr'] . "' />";
echo "<input name='vb_datum' id='aufnDat' type='hidden' value='" . $neu['vb_datum'] . "' />";

$Edit_Funcs_FeldName = false; // Feldname der Tabelle wird nicht angezeigt !!

# =========================================================================================================
Edit_Tabellen_Header('Daten der Veranstaltung');
# =========================================================================================================
if ($_SESSION['VF_Prim']['p_uid'] == 999999999) {
    $Edit_Funcs_Protect = True;
}

Edit_Daten_Feld('vb_flnr');
# =========================================================================================================
Edit_Separator_Zeile('Datum');
# =========================================================================================================

if ($neu['vb_flnr'] == 0) {
    Edit_Daten_Feld('vb_datum', 10, '', "type='date' ");
} else {
    $Edit_Funcs_protect = True;
    Edit_Daten_Feld('vb_datum',10);
    $Edit_Funcs_protect = False;
}

# =========================================================================================================
Edit_Separator_Zeile('Titel, Beschreibung');
# =========================================================================================================

Edit_Daten_feld('vb_titel', 80);
Edit_textarea_Feld('vb_beschreibung', '', 'cols=70 rows=4');

if ($neu['vb_flnr'] == 0) {
    Edit_Select_Feld('vb_unterseiten', VF_Unterseiten,'Unterseiten einrichten'); 
    Edit_Select_Feld('vb_fzg_beschr', VF_Fahrzeugbeschr,' z. B. für Corso- Berichte'); 
} else {
    $Edit_Funcs_protect = True;
    Edit_Select_Feld('vb_unterseiten', VF_Unterseiten,'Unterseiten einrichten'); 
    $Edit_Funcs_protect = False;
}


$button_f = "";
#if ($hide_area != 0) {  //toggle??
    // Der Button, der das toggling übernimmt, auswirkungen in VF_Foto_M()
    $button_f = " &nbsp; &nbsp; &nbsp; <button type='button' class='button-sm'  onclick='startAjax(0)'>Foto Daten eingeben/ändern</button>";
#}
Edit_Separator_Zeile('Fotos',$button_f);  #

echo "<input type='hidden' id='sammlung' value=''>";
echo "<input type='hidden' id='eigner' value=''>";
/**
 * Auswahl der Fotos bei Neueingabe, Anzeige der ausgewählten Fotos mit Einteilung bei Bestand
 */
if ($_SESSION[$module]['vb_flnr'] == 0) {  // Neu- Eingabe
    
    # =========================================================================================================
    Edit_Separator_Zeile('Auswahl der Fotos'); 
    # =========================================================================================================
    
    
    ?>
   
    <!-- Bereich für die diversen Ausgaben von js  -->
   // <input type="hidden" id="bild-datei-auswahl" name="bilder_datei" value="" />
    

    <div id="bild-vorschau-auswahl"></div>
    
    <!-- 
        Bild- AUswahl neu, direkt- Anzeige
     -->
     <div id='bilderAuswahl' class='w3-row'></div> <!-- für die grobe Auswahl -->
     <div class='inputFields' ></div>
     
     &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <button type='button' class='button-sm ' id='analyse' style='visibility:visible;' onclick='startAnal()'>Analyse der Auswahl</button>
    
    <!-- Galerie-Container für die Bildauswahl
    <div id="bild-galerie_0" style="display:block; border:1px solid #ccc; padding:10px;"></div> // none
     -->
    <!-- Dialog für die Bilder-Auswahl (separater Dialog, eigene IDs)
    <div id="dialog-bilder_0" style="display:block;">  // none
    <div id="bild-vorschau-dialog_0"></div>
    <div id="dateiname-dialog_0"></div>
    <input type="hidden" id="bild-datei-dialog_0">
    </div>
    <hr>
     -->
     
 
    <?php 
    
} else {      // Bestand bearbeiten
    # var_dump($_SESSION[$module]['BERI']);
    echo "<p><a href='VF_O_BE_List.php?Act=" . $_SESSION[$module][$sub_mod]['Act'] . "'>Zurück zur Liste</a></p>";

    # var_dump($ber_arr);
    echo "<div class='w3-table-all'>";
    echo "<table><tbody>";
    echo "<tr><th>Lfd.Nr</th><th>Seite</th><th>Zeile</th><th>Titel d. Seite</th><th>Fahrzeug ID</th><th>Beschr. Foto</th><th>Beschr. Lib</th><th>Foto</th></tr>";
    foreach ($ber_arr as $key => $data ) {
        
        $fo_dsarr = explode('-',$data['vd_foto']);
        $urh = $fo_dsarr[0];
        $sql_fo = "SELECT * FROM dm_edien_$urh WHERE md_dsn_1='".$data['vd_foto']."'   ";
        # echo "L 0122 sql_fo $sql_fo <br>";
        $ret_fo = SQL_QUERY($db,$sql_fo);
        $pict = "";
        if ($ret_fo) {
            $row_fo = mysqli_fetch_assoc($ret_fo);
           # var_dump($row_fo);
            $fo_arr = explode('-',$row_fo['md_dsn_1']);
            $cnt_fo = count($fo_arr);
            
            if ($cnt_fo >= 3) {   // URH-Verz- Struktur de dsn
                $urh = $fo_arr[0]."/";
                $verz = $fo_arr[1]."/";
                if ($cnt_fo > 3) {
                    if (isset($fo_arr[3])) {
                        $s_verz = $fo_arr[3]."/";
                    }
                }
                $p1 = $path2ROOT ."login/AOrd_Verz/$urh/09/06/".$verz.$row_fo['md_dsn_1'];
                $pict = "<a href='$p1' target='Bild 1' > <img src='$p1' alter='$p1' width='200px'> ". $row_fo['md_dsn_1']."  </a>";
            } 
        }
        echo "<tr><td>".$data['vd_flnr']." </td><td>".$data['vd_unter']." </td><td>".$data['vd_suffix']."</td><td>".$data['vd_titel']."</td><td>".$data['vd_Fzg_id']."</td><td>".$data['vd_beschreibung']."</td><td>".$row_fo['md_beschreibg']."</td><td>$pict<br>$p1</td></tr>";
    }
   
    echo "</tbody></table>";

}


Edit_Separator_Zeile('Änderungen');

Edit_Daten_Feld('vb_uid');
Edit_Daten_Feld('vb_aenddat');

Edit_Tabellen_Trailer();

if ($_SESSION[$module]['all_upd']) {
    echo "<p>Nach Eingabe aller Daten oder Änderungen  drücken Sie ";
    echo "<button type='submit' id='new_upload_btn' name='phase' value='1' class='green'>Daten abspeichern</button></p>";
}


echo "<p><a href='VF_BE_List.php?Act=" . $_SESSION[$module]['BERI']['Act'] . "'>Zurück zur Liste</a></p>";

?>
   <script>
// var bilder = {}; // globale Variable für die Bilderliste

const bildArr = {};
const pictList = []; // Liste der Bildauwahl nach Eingabe Page und Pos

const pictSelected = []; // Liste der ausgewählten Bilder => Page und Pos sind eingetragen

// const inputFieldsE = $('#inputFields');

// const analyse = $('#analyse');

// Funktion, um Bilder basierend auf der Auswahl zu laden
function startAjax(biNr) {
    // Beispiel: Daten sammeln
    var aufnDat = $('#vb_datum').val();

    var sammlg = $('#sammlung').val().trim();
    var eigner = $('#eigner').val();
    var aufnDat =$('#aufnDat').val();
    console.log('Sammlg ',sammlg);
    console.log('Eigner ',eigner);
    console.log('aufnDat ',aufnDat);
    
    const unterSeiten = $('#vb_unterseiten').val();
    const fzgBeschr  = $('#vb_fzg_beschr').val();
 
    console.log('unterseiten ',unterSeiten);
    const bilderAuswahl = $('#bilderAuswahl');
    const analyse = $('#analyse');

    // AJAX-Anfrage
    $.ajax({
        url: 'common/API/VF_SelPictLib.API.php',
        method: 'POST',
        data: {
            'aufnDat' : aufnDat
        },
        dataType: 'json',
        success: function(daten) {
            console.log('Success ',daten);
           // bilder[biNr] = daten;
            // Galerie im Dialog füllen
            // var dialog = $('#dialog-bilder_' + biNr);
            // var galerieHtml = '<div style="display:flex; flex-wrap:wrap; gap:10px;">';
            pictCountList = daten.length;
            for (let i=0; i<daten.length; i++) {
                let b = daten[i];
              
                 let bild =  `<div class="bild-item w3-half" data-index="${i}" style="cursor:pointer; border:1px solid #ccc; padding:5px;">
                        <img src="${b.pfad}" alt="${b.dateiname}" width="400"><br>${b.dateiname}<br>
                        
                        <div id='inField'></div>
                        <label>Seite: <input type="text" id="bPage_${i}" value="" size="3" ></label>
                        <label>Position: <input type="text" id="bPos_${i}" value="" size="3" ></label>
                        <label>Text: <textarea id="bText_${i}"  rows="3" cols="40" placeholder="Beschreibung"> </textarea></label>
                    </div>`;
                    
                   // inField.appendTo(bPagein,bPosIn);
                   
                    pictList[i] = { bPage: '', bPos: '' ,  dsn: b.dateiname, path: b.pfad , bText: 'no nix'};
                    
                    $('#bilderAuswahl').append(bild);
                    // console.log('Pict-List ',pictList);
            }
           
        }
        
    });
}



function startAnal() {
  

    let cntPict = pictList.length;
    console.log('pict List ',pictList); 
    console.log('pict List length ', cntPict);
   
    for (let i=0; i<pictList.length; i++) { 
        const bPageEl = $('#bPage_' + i); 
        const bPosEl = $('#bPos_' + i); 
        const bTextEl = $('#bText_' + i);
        const bPageVal = bPageEl.val(); 
        const bPosVal = bPosEl.val();
        const bTextVal = bTextEl.val().trim();
        
        console.log('bPageVal ',bPageVal);
        console.log('bPosVal ' , bPosVal);
        
        pictList[i].bPos = bPosVal; 
        pictList[i].bPage = bPageVal;
        pictList[i].bText = bTextVal;
     
        console.log('Auswertete Liste:', pictList);
   }
    let j = 0;
    for (let i=0;i<pictList.length;i++) {
        if (pictList[i].bPos !== "" || pictList[i].bPage !== "") { // Eintragung vorhanden
            if (pictList[i].bPage === "" ){
                pictList[i].bPage = "1";
            }
            pictSelected[j] =   pictList[i]; 
            j++; 
        }
        console.log('pict Selected ',pictSelected);
    }

    selCnt = pictSelected.length;
    console.log('Sel Cnt ',selCnt);

    $('.inputFields').empty(); // Vorher leeren!

    for (let i=0;i<pictSelected.length;i++) {
        const bildData = pictSelected[i].bPage +'|'+ pictSelected[i].bPos +'|'+pictSelected[i].dsn  +'|'+pictSelected[i].bText; 
        console.log('bildData ',bildData);
        const inputHtml = `<input type="text" name="bild_${i}" id="bild_${i}" value='${bildData}' size="3" />`;
        $('.inputFields').append(inputHtml);
    }
    var fields = $(':input'); // umfasst input, textarea, select, button (wir filtern unten ggf. aus)
    console.log('Fields ',fields); 
}

</script>
<?php 

# =========================================================================================================
if ($debug) {
    echo "<pre class=debug>VF_BE_edit.ph0.php beendet</pre>";
}

?>