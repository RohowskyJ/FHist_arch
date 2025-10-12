<?php
/**
 * Speichern der Archivaliendaten  in den entsprechenden Tabellen
 * 
 */

if ($debug) {
    echo "<pre class=debug>VF_C_MassUp_AR_ph3.inc.php ist gestarted</pre>";
}

$ao_num = 0 ;

$neu = array();
$neu['ad_uidaend'] = $_SESSION['VF_Prim']['p_uid'];
$tabelle_a = 'ar_chivdat_';
foreach ($_POST as $name => $value ) {
    if ($name == "eigner") {
        $neu['ad_eignr'] = $eigner = $value;
        $tabelle_a .= $eigner;
        $return_a = Cr_n_ar_chivdat($tabelle_a);
    }
    if ($name == 'aOrd' ){
        $aOrd = $value;
        $ao_arr = explode('/',$aOrd);
        $neu['ad_sg'] = $ao_arr[0];
        $neu['ad_subsg'] = $ao_arr[1];
    }
    if ($name == 'aoText') {
        
        if (strpos($value,'|') > 2) {
            $t_arr = explode('|',$value);
            $cnt_ta = count($t_arr);
            var_dump($t_arr);
            $neu['ad_lcsg'] = $t_arr[0];
            $neu['ad_lcssg'] = $t_arr[1];
            $neu['ad_lcssg_s0'] = $t_arr[2];
            $neu['ad_lcssg_s1'] = $t_arr[3];
            $neu['ad_sammlg'] = $t_arr[4];
            $neu['ad_beschreibg'] = $t_arr[5];
        } else {
            $neu['ad_lcsg'] = $neu['ad_lcssg'] = $neu['ad_lcssg_s0'] = $neu['ad_lcssg_s1'] = '00';
            $neu['ad_sammlg'] = "";
            $neu['ad_beschreibg'] = substr($value,6);
        }
        
        $sql = "SELECT * FROM $tabelle_a WHERE  ad_sg='".$neu['ad_sg']."' AND ad_subsg='".$neu['ad_subsg']."' ";
        $res = SQL_QUERY($db,$sql);
        $ao_num = mysqli_num_rows($res);
        
    }
    if (substr($name,0,5) == 'name_' ){
        
        $d_arr = explode('_',$value) ;
        $d_cnt = count($d_arr);
        
        if ($d_cnt >=2 ){
            $neu['ad_doc_date'] = $d_arr[0];
        } else {
            $neu['ad_doc_date'] = 'mist';
        }
        $ao_num++;
        $neu['ad_ao_fortlnr'] = $ao_num ; // feststellen dur einlesen mit aord
        
        $neu['ad_doc_1'] = $value;
        
        $sql = "INSERT INTO $tabelle_a (
                ad_eignr,ad_sg,ad_subsg,ad_lcsg,ad_lcssg,ad_lcssg_s0,ad_lcssg_s1,ad_ao_fortlnr,ad_sammlg,
                ad_doc_date,ad_type,ad_format,
                ad_keywords,ad_beschreibg,ad_wert_orig,ad_orig_waehrung,ad_wert_kauf,ad_kauf_waehrung,
                ad_wert_besch,ad_besch_waehrung,ad_namen,ad_doc_1,
                ad_doc_2,ad_doc_3,ad_doc_4,ad_isbn,ad_lagerort,
                ad_l_raum,ad_l_kasten,ad_l_fach,
                ad_l_pos_x,ad_l_pos_y,ad_neueigner,ad_uidaend,ad_aenddat
              ) VALUE (
                '$neu[ad_eignr]','$neu[ad_sg]','$neu[ad_subsg]','$neu[ad_lcsg]','$neu[ad_lcssg]','$neu[ad_lcssg_s0]','$neu[ad_lcssg_s1]','$neu[ad_ao_fortlnr]','$neu[ad_sammlg]',
                '$neu[ad_doc_date]','DO','A4',
                '','$neu[ad_beschreibg]','0','','0','',
                '0','0','','$neu[ad_doc_1]',
                '','','','','',
                '','','',
                '','','','$neu[ad_uidaend]',now()
               )";
        
        if ($debug) {
            echo "<pre class=debug>";
            print_r($return_fi);
            echo "<br> \$sql $sql <br>";
            echo "</pre>";
        }
        
        $result = SQL_QUERY($db, $sql);
    }
    
}

if ($debug) {
    echo "<pre class=debug>VF_C_MassUp_AR_ph3.inc.php ist gestarted</pre>";
}