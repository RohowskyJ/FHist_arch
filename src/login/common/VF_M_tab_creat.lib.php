<?php

/**
 * Daten tabellen für Fahrzeuge und Geräte anlegen
 *
 */

/**
 * Tabellen für die Muskebewegten Fzg und Ger
 *
 */
function Cr_n_mu_fahrzeug($tabelle)
{
    global $debug, $db;
    mysqli_set_charset($db, "utf8mb4");
    $sql = "CREATE TABLE IF NOT EXISTS $tabelle (
  `fm_id` int(11) NULL  AUTO_INCREMENT COMMENT 'Fortl. Nr.',
  `fm_eignr` varchar(10) NULL  COMMENT 'Eigentümer Nr.',
  `fm_invnr` varchar(6) DEFAULT NULL COMMENT 'Inventar- Nr.',
  `fm_bezeich` varchar(60) NULL  COMMENT 'Bezeichnung',
  `fm_komment` text NULL  COMMENT 'Kommentar',
  `fm_type` varchar(60) NULL  COMMENT 'Type',
  `fm_leistung` varchar(10) NULL  COMMENT 'Leistung',
  `fm_lei_bed` varchar(60) NULL  COMMENT 'Leistungs- Bedingung',
  `fm_herst` varchar(60) NULL  COMMENT 'Hersteller',
  `fm_baujahr` varchar(10) NULL  COMMENT 'Baujahr',
  `fm_fgstnr` varchar(60) NULL  COMMENT 'Fahrgestell- Nr.',
  `fm_indienst` varchar(10) NULL  COMMENT 'Indienst Stellung',
  `fm_ausdienst` varchar(10) NULL  COMMENT 'Ausserdienst- Stellung',
  `fm_zustand` varchar(2) NULL  COMMENT 'Zustand',
  `fm_gew` varchar(10) NULL  COMMENT 'Gewicht',
  `fm_zug` varchar(20) NULL  COMMENT 'Zugeinrichtung',
  `fm_foto_1` varchar(50) NULL  COMMENT 'Foto 1',
  `fm_komm_1` text NULL  COMMENT 'Kommentar 1',
  `fm_foto_2` varchar(50) NULL  COMMENT 'Foto 2',
  `fm_komm_2` text NULL  COMMENT 'Kommentar 2',
  `fm_foto_3` varchar(50) NULL  COMMENT 'Foto 3',
  `fm_komm_3` text NULL  COMMENT 'Kommentar 3',
  `fm_foto_4` varchar(50) NULL  COMMENT 'Foto 4',
  `fm_komm_4` text NULL  COMMENT 'Kommentar 4',
  `fm_sammlg` varchar(20) DEFAULT NULL COMMENT 'Sammlung',
  `fm_uidaend` varchar(10) NULL  COMMENT 'Letzter Änderer',
  `fm_aenddat` timestamp NULL  DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'letzte Änderung',
  PRIMARY KEY (`fm_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci  COMMENT='muskelgezogene Fahrzeuge ' ";
    $return = SQL_QUERY($db, $sql);
    return $return;
} # Ende Funktion Cr_n_fz_muskel

function Cr_n_mu_geraet($tabelle)
{
    global $debug, $db;
    mysqli_set_charset($db, "utf8mb4");
    $sql = "CREATE TABLE IF NOT EXISTS $tabelle (
  `mg_id` int(11) NULL  AUTO_INCREMENT COMMENT 'Fortl. Nr.',
  `mg_eignr` varchar(10) NULL  COMMENT 'Eigner- Nr.',
  `mg_invnr` varchar(6) DEFAULT NULL COMMENT 'Inventar Nr.',
  `mg_bezeich` varchar(60) NULL  COMMENT 'Bezeichnung',
  `mg_komment` text NULL  COMMENT 'Kommentar',
  `mg_type` varchar(60) NULL  COMMENT 'Type',
  `mg_herst` varchar(60) NULL  COMMENT 'Hersteller',
  `mg_baujahr` varchar(10) NULL  COMMENT 'Baujahr',
  `mg_fgstnr` varchar(60) NULL  COMMENT 'Serien- Nummer',
  `mg_indienst` varchar(10) NULL  COMMENT 'Indienst- Stellung',
  `mg_ausdienst` varchar(10) NULL  COMMENT 'Ausserdienst- Stellung',
  `mg_zustand` varchar(2) NULL  COMMENT 'Zustand',
  `mg_gew` varchar(10) NULL  COMMENT 'Gewicht',
  `mg_zug` varchar(20) NULL ,
  `mg_foto_1` varchar(50) NULL  COMMENT 'Foto 1',
  `mg_komm_1` text NULL  COMMENT 'Kommentar Foto 1',
  `mg_foto_2` varchar(50) NULL  COMMENT 'Foto 2',
  `mg_komm_2` text NULL  COMMENT 'Kommentar Foto 2',
  `mg_foto_3` varchar(50) NULL  COMMENT 'Foto 3',
  `mg_komm_3` text NULL  COMMENT 'Kommentar Foto 3',
  `mg_foto_4` varchar(50) NULL  COMMENT 'Foto 4',
  `mg_komm_4` text NULL  COMMENT 'Kommentar Foto 4',
  `mg_sammlg` varchar(20) DEFAULT NULL COMMENT 'Sammlungs. Abk.',
  `mg_fzg` varchar(15) NULL  COMMENT 'In Fahrzeug',
  `mg_raum` varchar(15) NULL  COMMENT 'in Raum',
  `mg_ort` varchar(15) NULL  COMMENT 'Wo?',
  `mg_pruef_id` varchar(15) NULL  COMMENT 'Prüfer',
  `mg_pruef_dat` varchar(12) NULL  COMMENT 'Prüfungs- Datum',                 
  `mg_uidaend` varchar(10) NULL  COMMENT 'Letzter Änderer',
  `mg_aenddat` timestamp NULL  DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Letzte Änderug',
  PRIMARY KEY (`mg_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='muskelgetriebene Geräte'
    ";
    $return = SQL_QUERY($db, $sql);
    return $return;

} # end function cre_n_mu_geraet

function Cr_n_ma_geraet($tabelle)
{
    global $debug, $db;
    mysqli_set_charset($db, "utf8mb4");
    $sql = "CREATE TABLE IF NOT EXISTS $tabelle (
  `ge_id` int(11) NULL  AUTO_INCREMENT COMMENT 'Fortl. Nr.',
  `ge_eignr` varchar(10) NULL  COMMENT 'Eigentümer',
  `ge_invnr` varchar(6) DEFAULT NULL COMMENT 'Inventar- Nr.',
  `ge_bezeich` text NULL  COMMENT 'Bezeichnung',
  `ge_type` varchar(60) NULL  COMMENT 'Type',
  `ge_leistg` varchar(10) NULL  COMMENT 'Leistung',
  `ge_lei_bed` varchar(50) NULL  COMMENT 'Leistungs- Bedingung',
  `ge_leinh` varchar(50) NULL  COMMENT 'Leistungs- Einheit',
  `ge_herst` varchar(60) NULL  COMMENT 'Hersteller',
  `ge_baujahr` varchar(10) NULL  COMMENT 'Baujahr',
  `ge_indienst` varchar(10) NULL  COMMENT 'Indienst- Stellung',
  `ge_ausdienst` varchar(10) NULL  COMMENT 'Ausserdienst- Stellung',
  `ge_komment` text NULL  COMMENT 'Kommentar',
  `ge_gesgew` varchar(10) NULL  COMMENT 'Gesamt- Gewicht',
  `ge_mo_herst` varchar(60) NULL  COMMENT 'Motor Hersteller ',
  `ge_mo_typ` varchar(60) NULL  COMMENT 'Motor- Typ',
  `ge_mo_sernr` varchar(60) NULL  COMMENT 'Motor Seriennummer',
  `ge_mo_treibst` varchar(60) NULL  COMMENT 'Treibstoff',
  `ge_mo_leistung` varchar(10) NULL  COMMENT 'Leistung',
  `ge_mo_leibed` varchar(50) NULL  COMMENT 'Leistungs- Bedingung',
  `ge_mo_leinh` varchar(50) NULL  COMMENT 'Leistungs- Einheit',
  `ge_ag_herst` varchar(60) NULL  COMMENT 'Aggregat- Hersteller',
  `ge_ag_type` varchar(60) NULL  COMMENT 'Type',
  `ge_ag_sernr` varchar(60) NULL  COMMENT 'Serien- Nummer',
  `ge_ag_leistung` varchar(10) NULL  COMMENT 'Leistung',
  `ge_ag_leibed` varchar(50) NULL  COMMENT 'Leistungs- Bedingung',
  `ge_ag_leinh` varchar(60) NULL  COMMENT 'Leistungs- Einheit',
  `ge_foto_1` varchar(50) NULL  COMMENT 'Bild 1',
  `ge_komm_1` text NULL  COMMENT 'Kommentar 1',
  `ge_foto_2` varchar(50) NULL  COMMENT 'Bild 2',
  `ge_komm_2` text NULL  COMMENT 'Kommentar 2',
  `ge_foto_3` varchar(50) NULL  COMMENT 'Bild 3',
  `ge_komm_3` text NULL  COMMENT 'Kommentar 3',
  `ge_foto_4` varchar(50) NULL  COMMENT 'Bild 4',
  `ge_komm_4` text NULL  COMMENT 'Kommentar 4',
  `ge_sammlg` varchar(20) DEFAULT NULL COMMENT 'Sammlung',
  `ge_g1_name` varchar(60) NULL  COMMENT 'Name',
  `ge_g1_sernr` varchar(50) NULL  COMMENT 'Serien- Nummer',
  `ge_g1_beschr` text NULL  COMMENT 'Beschreibung',
  `ge_g1_foto` varchar(50) NULL  COMMENT 'Foto 1',
  `ge_g2_name` varchar(60) NULL  COMMENT 'Name',
   `ge_g2_sernr` varchar(50) NULL  COMMENT 'Serien- Nr.',
  `ge_g2_beschr` text NULL  COMMENT 'Beschreibung',
  `ge_g2_foto` varchar(50) NULL  COMMENT 'Foto',
  `ge_g3_name` varchar(60) NULL  COMMENT 'Name',
  `ge_g3_sernr` varchar(50) NULL  COMMENT 'Serien- Nr.',
  `ge_g3_beschr` text NULL  COMMENT 'Beschreibung',
  `ge_g3_foto` varchar(50) NULL  COMMENT 'Foto',
  `ge_g4_name` varchar(60) NULL  COMMENT 'Name',
  `ge_g4_sernr` varchar(50) NULL  COMMENT 'Serien- Nr.',
  `ge_g4_beschr` text NULL  COMMENT 'Beschreibung',
  `ge_g4_foto` varchar(50) NULL  COMMENT 'Foto',
  `ge_g5_name` varchar(60) NULL  COMMENT 'Name',
  `ge_g5_sernr` varchar(50) NULL  COMMENT 'Serien- Nr.',
  `ge_g5_beschr` text NULL  COMMENT 'Beschreibung',
  `ge_g5_foto` varchar(50) NULL  COMMENT 'Foto',
  `ge_g6_name` varchar(60) NULL  COMMENT 'Name',
  `ge_g6_sernr` varchar(50) NULL  COMMENT 'Serien- Nr.',
  `ge_g6_beschr` text NULL  COMMENT 'Beschreibung',
  `ge_g6_foto` varchar(50) NULL  COMMENT 'Foto',
  `ge_g7_name` varchar(60) NULL  COMMENT 'Name',
  `ge_g7_sernr` varchar(50) NULL  COMMENT 'Serien- Nr.',
  `ge_g7_beschr` text NULL  COMMENT 'Beschreibung',
  `ge_g7_foto` varchar(50) NULL  COMMENT 'Foto',
  `ge_g8_name` varchar(60) NULL  COMMENT 'Name',
  `ge_g8_sernr` varchar(50) NULL  COMMENT 'Serien- Nr.',
  `ge_g8_beschr` text NULL  COMMENT 'Beschreibung',
  `ge_g8_foto` varchar(50) NULL  COMMENT 'Foto',
  `ge_g9_name` varchar(60) NULL  COMMENT 'Name',
  `ge_g9_sernr` varchar(50) NULL  COMMENT 'Serien- Nr.',
  `ge_g9_beschr` text NULL  COMMENT 'Beschreibung',
  `ge_g9_foto` varchar(50) NULL  COMMENT 'Foto',
  `ge_g10_name` varchar(60) NULL  COMMENT 'Name',
  `ge_g10_sernr` varchar(50) NULL  COMMENT 'Serien- Nr.',
  `ge_g10_beschr` text NULL  COMMENT 'Beschreibung',
  `ge_g10_foto` varchar(50) NULL  COMMENT 'Foto',
  `ge_fzg` varchar(10) NULL  COMMENT 'in Fahrzeug',
  `ge_raum` varchar(60) NULL  COMMENT 'in Raum',
  `ge_ort` varchar(60) NULL  COMMENT 'Aufbewahrungs- Ort',
  `ge_zustand` varchar(2) NULL  COMMENT 'Zustand',
  `ge_pruef_id` varchar(10) NULL  COMMENT 'von wem geprüft',
  `ge_pruef_dat` varchar(10) NULL  COMMENT 'Prüfung am',
  `ge_aenduid` varchar(10) NULL  COMMENT 'letzter Änderer',
  `ge_aenddat` timestamp NULL   COMMENT 'letzte Änderung' DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`ge_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='motorbetriebene Gräte'
         ";
    $return = SQL_QUERY($db, $sql);
    return $return;
} # Ende Funktion Cr_n_ge_raet

function Cr_n_ma_fahrzeug($tabelle)
{
    global $debug, $db;
    mysqli_set_charset($db, "utf8mb4");
    $sql = "CREATE TABLE IF NOT EXISTS `$tabelle` (
  `fz_id` int NOT NULL AUTO_INCREMENT COMMENT 'Fortlaufende Nummer',
  `fz_eignr` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Eigner- Nummer',
  `fz_invnr` int DEFAULT NULL COMMENT 'Inventar- Nummer',
  `fz_sammlg` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Sammlung',
  `fz_name` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Rufname',
  `fz_taktbez` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Taktische Bezeichnung',
  `fz_hist_bezeichng` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Historische Bezeichnung',
  `fz_baujahr` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Baujahr',
  `fz_indienstst` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Indienst- Stellung',
  `fz_ausdienst` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ausser Dienst Datum/Jahr',
  `fz_zeitraum` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Zeitraum',
  `fz_allg_beschr` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Allgem. Beschreibung',
  `fz_herstell_fg` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Fahrgestell Hersteller',
  `fz_motor` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Motor',
  `fz_typ` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Typ',
  `fz_modell` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Modell',
  `fz_aufbauer` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Aufbauer',
  `fz_aufb_typ` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Aufbau Typ',
  `fz_bild_1` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Bild 1',
  `fz_b_1_komm` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Kommentar zu Bild 1',
  `fz_bild_2` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Bild 2',
  `fz_b_2_komm` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Kommentar zu Bild 2',
  `fz_bild_3` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Bild 3',
  `fz_b_3_komm` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Kommentar zu Bild 3',
  `fz_bild_4` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Bild 4',
  `fz_b_4_komm` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Kommentar zu Bild 4',
  `fz_zustand` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Gesamt Zustand',
  `fz_ctif_klass` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Klassifizierungs Stufe',
  `fz_ctif_date` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Juroren',
  `fz_ctif_juroren` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Datum der Klassifizierung',
  `fz_ctif_darst_jahr` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Darstellungsjahr',
  `fz_beschreibg_det` text COLLATE utf8mb4_unicode_ci COMMENT 'Detail- Beschreibung',
  `fz_eigent_freig` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Eigentümer Freigabe',
  `fz_verfueg_freig` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Besitzer Freigabe',
  `fz_pruefg_id` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Prüfer ID',
  `fz_l_tank` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tank',
  `fz_pruefg` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Prüfungs Datum',
  `fz_l_monitor` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Monitor',
  `fz_antrieb` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Antrieb',
  `fz_l_pumpe` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Pumpe',
  `fz_t_kran` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Kran',
  `fz_t_winde` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Seilwinde',
  `fz_t_leiter` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Leiter',
  `fz_t_abschlepp` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Abschleppvorrichtung',
  `fz_t_geraet` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Geräte',
  `fz_geschwindigkeit` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Fahrgeschwindigkeit',
  `fz_besatzung` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Besatzung',
  `fz_g_atemsch` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Atemschutz',
  `fz_t_beleuchtg` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Beleuchtung',
  `fz_t_strom` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Generator',
  `fz_aenduid` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Änderer',
  `fz_aenddat` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Letzte Änderung',
  PRIMARY KEY (`fz_id`),
  KEY `fz_ctif_date` (`fz_ctif_date`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Fahrzeugdaten';          
         ";
    $return = SQL_QUERY($db, $sql);
    return $return;
} # Ende Funktion Cr_n_ma_fahrzeug

function Cr_n_ma_eigner($tabelle)
{
    global $debug, $db;
    mysqli_set_charset($db, "utf8mb4");
    $sql = "CREATE TABLE IF NOT EXISTS $tabelle (
        `fz_eign_id` int(11) NULL  AUTO_INCREMENT COMMENT 'Fortl. Nr.',
        `fz_id` int(11) NULL  COMMENT 'Fzg. Nr.',
        `fz_docbez` varchar(100) NULL  COMMENT 'Dokument- Bezeichnung',
        `fz_zul_dat` varchar(10) NULL  COMMENT 'Zulassungs- Datum',
        `fz_zul_end_dat` varchar(10) NULL  COMMENT 'Zul. Ende Datum',
        `fz_zuldaten` varchar(100) NULL  COMMENT 'zugel. auf Name',
        `fz_uidaend` varchar(10) NULL  COMMENT 'letzer Änderer',
        `fz_aenddat` timestamp NULL  DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'zuletzt geändert',
       PRIMARY KEY (`fz_eign_id`)
       ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci   COMMENT 'Fahrzeug  Eigner' ";
    $return = SQL_QUERY($db, $sql);
    return $return;
} # Ende Funktion Cr_n_ma_eigner


