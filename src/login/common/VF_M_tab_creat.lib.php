<?php

/**
 * Daten tabellen für Fahrzeuge und Geräte anlegen
 *
 */

/**
 * Tabellen für die Muskebewegten Fzg und Ger
 *
 */
function Cr_n_mu_fahrzeug ($tabelle)
{
    global $debug, $db;

   $sql = "CREATE TABLE IF NOT EXISTS $tabelle (
  `fm_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Fortl. Nr.',
  `fm_eignr` char(10) NOT NULL COMMENT 'Eigentümer Nr.',
  `fm_invnr` char(6) DEFAULT NULL COMMENT 'Inventar- Nr.',
  `fm_bezeich` char(60) NOT NULL COMMENT 'Bezeichnung',
  `fm_komment` text NOT NULL COMMENT 'Kommentar',
  `fm_type` char(60) NOT NULL COMMENT 'Type',
  `fm_leistung` int(11) NOT NULL COMMENT 'Leistung',
  `fm_lei_bed` char(60) NOT NULL COMMENT 'Leistungs- Bedingung',
  `fm_herst` char(60) NOT NULL COMMENT 'Hersteller',
  `fm_baujahr` char(10) NOT NULL COMMENT 'Baujahr',
  `fm_fgstnr` char(60) NOT NULL COMMENT 'Fahrgestell- Nr.',
  `fm_indienst` char(10) NOT NULL COMMENT 'Indienst Stellung',
  `fm_ausdienst` char(10) NOT NULL COMMENT 'Aussesienst- Stellung',
  `fm_zustand` varchar(2) NOT NULL COMMENT 'Zustand',
  `fm_gew` int(11) NOT NULL COMMENT 'Gewicht',
  `fm_zug` char(10) NOT NULL COMMENT 'Zugeinrichtung',
  `fm_foto_1` char(50) NOT NULL COMMENT 'Foto 1',
  `fm_komm_1` text NOT NULL COMMENT 'Kommentar 1',
  `fm_foto_2` char(50) NOT NULL COMMENT 'Foto 2',
  `fm_komm_2` text NOT NULL COMMENT 'Kommentar 2',
  `fm_foto_3` char(50) NOT NULL COMMENT 'Foto 3',
  `fm_komm_3` text NOT NULL COMMENT 'Kommentar 3',
  `fm_foto_4` char(50) NOT NULL COMMENT 'Foto 4',
  `fm_komm_4` text NOT NULL COMMENT 'Kommentar 4',
  `fm_sammlg` varchar(20) DEFAULT NULL COMMENT 'Sammlung',
  `fm_uidaend` char(10) NOT NULL COMMENT 'Letzter Änderer',
  `fm_aenddat` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'letzte Änderung',
  PRIMARY KEY (`fm_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_german2_ci; ";
    $return = SQL_QUERY($db,$sql);
    return $return;
} # Ende Funktion Cr_n_fz_muskel

function Cr_n_mu_geraet ($tabelle)
{
    global $debug, $db;

    $sql = "CREATE TABLE IF NOT EXISTS $tabelle (
  `mg_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Fortl. Nr.',
  `mg_eignr` char(10) NOT NULL COMMENT 'Eigner- Nr.',
  `mg_invnr` char(6) DEFAULT NULL COMMENT 'Inventar Nr.',
  `mg_bezeich` char(60) NOT NULL COMMENT 'Bezechnung',
  `mg_komment` text NOT NULL COMMENT 'Kommentar',
  `mg_type` char(60) NOT NULL COMMENT 'Type',
  `mg_herst` char(60) NOT NULL COMMENT 'Hersteller',
  `mg_baujahr` char(10) NOT NULL COMMENT 'Baujahr',
  `mg_fgstnr` char(60) NOT NULL COMMENT 'Serien- Nummer',
  `mg_indienst` char(10) NOT NULL COMMENT 'Indienst- Stellung',
  `mg_ausdienst` char(10) NOT NULL COMMENT 'Ausserdienst- Stellung',
  `mg_zustand` varchar(2) NOT NULL COMMENT 'Zustand',
  `mg_gew` int(11) NOT NULL COMMENT 'Gewicht',
  `mg_zug` char(10) NOT NULL,
  `mg_foto_1` char(50) NOT NULL COMMENT 'Foto 1',
  `mg_komm_1` text NOT NULL COMMENT 'Kommentar Foto 1',
  `mg_foto_2` char(50) NOT NULL COMMENT 'Foto 2',
  `mg_komm_2` text NOT NULL COMMENT 'Kommentar Foto 2',
  `mg_foto_3` char(50) NOT NULL COMMENT 'Foto 3',
  `mg_komm_3` text NOT NULL COMMENT 'Kommentar Foto 3',
  `mg_foto_4` char(50) NOT NULL COMMENT 'Foto 4',
  `mg_komm_4` text NOT NULL COMMENT 'Kommentar Foto 4',
  `mg_sammlg` varchar(20) DEFAULT NULL COMMENT 'Sammlungs. Abk.',
  `mg_fzg` varchar(15) NOT NULL COMMENT 'In Fahrzeug',
  `mg_raum` varchar(15) NOT NULL COMMENT 'in Raum',
  `mg_ort` varchar(15) NOT NULL COMMENT 'Wo?',
  `mg_pruef_id` varchar(15) NOT NULL COMMENT 'PrÃ¼fer',
  `mg_pruef_dat` int(11) NOT NULL,                 
  `mg_uidaend` char(10) NOT NULL COMMENT 'Letzter Änderer',
  `mg_aenddat` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'LetzteÄnderug',
  PRIMARY KEY (`mg_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_german2_ci;
    ";
    $return = SQL_QUERY($db,$sql);
    return $return;
    
} # end function cre_n_mu_geraet

function Cr_n_ma_geraet ($tabelle)
{
    global $debug, $db;

    $sql = "CREATE TABLE IF NOT EXISTS $tabelle (
  `ge_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Fortl. Nr.',
  `ge_eignr` char(10) NOT NULL COMMENT 'Eigentümer',
  `ge_invnr` char(6) DEFAULT NULL COMMENT 'Inventar- Nr.',
  `ge_bezeich` text NOT NULL COMMENT 'Bezeichnung',
  `ge_type` char(60) NOT NULL COMMENT 'Type',
  `ge_leistg` int(11) NOT NULL COMMENT 'Leistung',
  `ge_lei_bed` char(50) NOT NULL COMMENT 'Leistungs- Bedingung',
  `ge_leinh` char(50) NOT NULL COMMENT 'Leistungs- Einheit',
  `ge_herst` char(60) NOT NULL COMMENT 'Hersteller',
  `ge_baujahr` char(10) NOT NULL COMMENT 'Baujahr',
  `ge_indienst` char(10) NOT NULL COMMENT 'Indienst- Stellung',
  `ge_ausdienst` char(10) NOT NULL COMMENT 'Ausserdienst- Stellung',
  `ge_komment` text NOT NULL COMMENT 'Kommentar',
  `ge_gesgew` char(10) NOT NULL COMMENT 'Gesamt- Gewicht',
  `ge_mo_herst` char(60) NOT NULL COMMENT 'Motor Hersteller ',
  `ge_mo_typ` char(60) NOT NULL COMMENT 'Motor- Typ',
  `ge_mo_sernr` char(60) NOT NULL COMMENT 'Motor Seriennummer',
  `ge_mo_treibst` char(60) NOT NULL COMMENT 'Treibstoff',
  `ge_mo_leistung` char(10) NOT NULL COMMENT 'Leistung',
  `ge_mo_leibed` char(50) NOT NULL COMMENT 'Leistungs- Bedingung',
  `ge_mo_leinh` char(50) NOT NULL COMMENT 'Leistungs- Einheit',
  `ge_ag_herst` char(60) NOT NULL COMMENT 'Aggregat- Hersteller',
  `ge_ag_type` char(60) NOT NULL COMMENT 'Type',
  `ge_ag_sernr` char(60) NOT NULL COMMENT 'Serien- Nummer',
  `ge_ag_leistung` char(10) NOT NULL COMMENT 'Leistung',
  `ge_ag_leibed` char(50) NOT NULL COMMENT 'Leistungs- Bedingung',
  `ge_ag_leinh` char(60) NOT NULL COMMENT 'Leistungs- Einheit',
  `ge_foto_1` char(50) NOT NULL COMMENT 'Bild 1',
  `ge_komm_1` text NOT NULL COMMENT 'Kommentar 1',
  `ge_foto_2` char(50) NOT NULL COMMENT 'Bild 2',
  `ge_komm_2` text NOT NULL COMMENT 'Kommentar 2',
  `ge_foto_3` char(50) NOT NULL COMMENT 'Bild 3',
  `ge_komm_3` text NOT NULL COMMENT 'Kommentar 3',
  `ge_foto_4` char(50) NOT NULL COMMENT 'Bild 4',
  `ge_komm_4` text NOT NULL COMMENT 'Kommentar 4',
  `ge_sammlg` varchar(20) DEFAULT NULL COMMENT 'Sammlung',
  `ge_g1_name` char(60) NOT NULL COMMENT 'Name',
  `ge_g1_sernr` char(50) NOT NULL COMMENT 'Serien- Nummer',
  `ge_g1_beschr` text NOT NULL COMMENT 'Beschreibung',
  `ge_g1_foto` char(50) NOT NULL COMMENT 'Foto 1',
  `ge_g2_name` char(60) NOT NULL COMMENT 'Name',
   `ge_g2_sernr` char(50) NOT NULL COMMENT 'Serien- Nr.',
  `ge_g2_beschr` text NOT NULL COMMENT 'Beschreibung',
  `ge_g2_foto` char(50) NOT NULL COMMENT 'Foto',
  `ge_g3_name` char(60) NOT NULL COMMENT 'Name',
  `ge_g3_sernr` char(50) NOT NULL COMMENT 'Serien- Nr.',
  `ge_g3_beschr` text NOT NULL COMMENT 'Beschreibung',
  `ge_g3_foto` char(50) NOT NULL COMMENT 'Foto',
  `ge_g4_name` char(60) NOT NULL COMMENT 'Name',
  `ge_g4_sernr` char(50) NOT NULL COMMENT 'Serien- Nr.',
  `ge_g4_beschr` text NOT NULL COMMENT 'Beschreibung',
  `ge_g4_foto` char(50) NOT NULL COMMENT 'Foto',
  `ge_g5_name` char(60) NOT NULL COMMENT 'Name',
  `ge_g5_sernr` char(50) NOT NULL COMMENT 'Serien- Nr.',
  `ge_g5_beschr` text NOT NULL COMMENT 'Beschreibung',
  `ge_g5_foto` char(50) NOT NULL COMMENT 'Foto',
  `ge_g6_name` char(60) NOT NULL COMMENT 'Name',
  `ge_g6_sernr` char(50) NOT NULL COMMENT 'Serien- Nr.',
  `ge_g6_beschr` text NOT NULL COMMENT 'Beschreibung',
  `ge_g6_foto` char(50) NOT NULL COMMENT 'Foto',
  `ge_g7_name` char(60) NOT NULL COMMENT 'Name',
  `ge_g7_sernr` char(50) NOT NULL COMMENT 'Serien- Nr.',
  `ge_g7_beschr` text NOT NULL COMMENT 'Beschreibung',
  `ge_g7_foto` char(50) NOT NULL COMMENT 'Foto',
  `ge_g8_name` char(60) NOT NULL COMMENT 'Name',
  `ge_g8_sernr` char(50) NOT NULL COMMENT 'Serien- Nr.',
  `ge_g8_beschr` text NOT NULL COMMENT 'Beschreibung',
  `ge_g8_foto` char(50) NOT NULL COMMENT 'Foto',
  `ge_g9_name` char(60) NOT NULL COMMENT 'Name',
  `ge_g9_sernr` char(50) NOT NULL COMMENT 'Serien- Nr.',
  `ge_g9_beschr` text NOT NULL COMMENT 'Beschreibung',
  `ge_g9_foto` char(50) NOT NULL COMMENT 'Foto',
  `ge_g10_name` char(60) NOT NULL COMMENT 'Name',
  `ge_g10_sernr` char(50) NOT NULL COMMENT 'Serien- Nr.',
  `ge_g10_beschr` text NOT NULL COMMENT 'Beschreibung',
  `ge_g10_foto` char(50) NOT NULL COMMENT 'Foto',
  `ge_fzg` char(10) NOT NULL COMMENT 'in Fahrzeug',
  `ge_raum` char(60) NOT NULL COMMENT 'in Raum',
  `ge_ort` char(60) NOT NULL COMMENT 'Aufbewahrungs- Ort',
  `ge_zustand` char(2) NOT NULL COMMENT 'Zustand',
  `ge_pruef_id` char(10) NOT NULL COMMENT 'von wem gepr�ft',
  `ge_pruef_dat` char(10) NOT NULL COMMENT 'Pr�fung am',
  `ge_aenduid` char(10) NOT NULL COMMENT 'letzter �nderer',
  `ge_aenddat` timestamp NOT NULL  COMMENT 'letztr �nderung' DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`ge_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_german2_ci;
         ";
    $return = SQL_QUERY($db,$sql);
    return $return;
} # Ende Funktion Cr_n_ge_raet

/**
 * Create Tabellen for Motorbewegter Fzg und Geräte
 *
 */

function Cr_n_ma_arc_xref ($tabelle)
{
    global $debug, $db;
echo "l 0197 $tabelle <br>";
    $sql = "CREATE TABLE IF NOT EXISTS $tabelle (
  `fa_id` smallint(6) NOT NULL AUTO_INCREMENT COMMENT 'Fortlaufende Nummer',
  `fa_eignr` smallint(6) NOT NULL COMMENT 'EigentÃ¼mer- Nr.',
  `fa_fzgnr` smallint(6) NOT NULL COMMENT 'Fahrzeug- Nr.',
  `fa_sammlg` varchar(20) NOT NULL COMMENT 'Sammlungs- AbkÃ¼rzung',
  `fa_arcnr` smallint(6) NOT NULL COMMENT 'Archiv Nr.',
  `fa_uidaend` varchar(4) NOT NULL COMMENT 'Ã„nderer',
  `fa_aenddat` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Letzte Ã„nderung',
  PRIMARY KEY (`fa_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_german2_ci;
";

    $return = SQL_QUERY($db,$sql);
    return $return;
}   # Ende Funktion Cr_n_fz_arc_xref


function Cr_n_ma_fz_beschr ($tabelle)
{
    global $debug, $db;

    $sql = "CREATE TABLE IF NOT EXISTS $tabelle (
  `fz_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Fortlaufende Nummer',
  `fz_eignr` char(10) NOT NULL COMMENT 'Eigner- Nummer',
  `fz_invnr` int(11) DEFAULT NULL COMMENT 'Inventar- Nummer',
  `fz_sammlg` varchar(20) DEFAULT NULL COMMENT 'Sammlung',
  `fz_name` char(30) NOT NULL COMMENT 'FZG Bezeichnung',
  `fz_taktbez` char(100) NOT NULL COMMENT 'Taktische Bezeichnung',
  `fz_indienstst` char(10) NOT NULL COMMENT 'Indienst- Stellung',
  `fz_ausdienst` char(10) NOT NULL COMMENT 'Ausser Dienst Datum/Jahr',
  `fz_zeitraum` char(5) NOT NULL COMMENT 'Zeitraum',
  `fz_komment` text NOT NULL COMMENT 'Kommentar',
  `fz_herstell_fg` varchar(100) DEFAULT NULL COMMENT 'Fahrgestell Hersteller',
  `fz_baujahr` varchar(4) DEFAULT NULL COMMENT 'Baujahr',
  `fz_bild_1` char(50) DEFAULT NULL COMMENT 'Bild 1',
  `fz_b_1_komm` text DEFAULT NULL COMMENT 'Kommentar zu Bild 1',
  `fz_bild_2` char(50) DEFAULT NULL COMMENT 'Bild 2',
  `fz_b_2_komm` text DEFAULT NULL COMMENT 'Kommentar zu Bild 2',
  `fz_zustand` char(2) DEFAULT NULL COMMENT 'Gesamt Zustand',
  `fz_ctifklass` varchar(5) DEFAULT NULL COMMENT 'Klassifizierungs Stufe',
  `fz_ctifdate` char(10) DEFAULT NULL COMMENT 'Datum der Klassifizierung',
  `fz_beschreibg_det` text DEFAULT NULL COMMENT 'Detail- Beschreibung',
  `fz_eigent_freig` char(50) DEFAULT NULL COMMENT 'Eigentümer Freigabe',
  `fz_verfueg_freig` char(50) DEFAULT NULL COMMENT 'Besitzer Freigabe',
  `fz_pruefg_id` char(10) DEFAULT NULL COMMENT 'Prüfer ID',
  `fz_pruefg` datetime DEFAULT NULL COMMENT 'Prüfungs Datum',
  `fz_aenduid` char(10) DEFAULT NULL COMMENT 'Änderer',
  `fz_aenddat` timestamp NULL DEFAULT current_timestamp() COMMENT 'Letzte Änderung',
  PRIMARY KEY (`fz_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_german2_ci COMMENT='Zulassungsdaten'; ";


    $return = SQL_QUERY($db,$sql);
    return $return;
} # Ende Funktion Cr_n_fz_beschr

function Cr_n_fz_eigner ($tabelle)
{
    global $debug, $db;

    $sql = "CREATE TABLE IF NOT EXISTS $tabelle (
      `fz_eign_id` int(10) NOT NULL AUTO_INCREMENT  COMMENT 'Eigner- Nummer',
       `fz_id` int(10) NOT NULL  COMMENT 'Fahzeug- Nummer',
       `fz_docbez` char(100) COLLATE utf8_german2_ci NOT NULL COMMENT 'Dokument- Bezeichnung',
       `fz_zul_dat` char(10) COLLATE utf8_german2_ci NOT NULL COMMENT 'Zulassungs-Datum',
       `fz_zul_end_dat` char(10) COLLATE utf8_german2_ci NOT NULL COMMENT 'Ende der Zulassung',
       `fz_zuldaten` char(100) COLLATE utf8_german2_ci NOT NULL COMMENT 'Daten',
       `fz_uidaend` char(10) COLLATE utf8_german2_ci NOT NULL  COMMENT 'Aenderer',
       `fz_aenddat` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Letzte Aenderung',
        PRIMARY KEY (`fz_eign_id`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_german2_ci COMMENT='Eigentuemer'
         ";
    $return = SQL_QUERY($db,$sql);
    return $return;
} # Ende Funktion Cr_n_fz_eigner

function Cr_n_fz_fixeinb ($tabelle)
{
    global $debug, $db;

    $sql = "CREATE TABLE IF NOT EXISTS $tabelle (
     `fz_einb_id` int(20) NOT NULL AUTO_INCREMENT COMMENT 'Fortl.- Nummer',
     `fz_id` int(10) DEFAULT NULL COMMENT 'Fahrzeug Nummer',
     `fz_gername` char(50) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Geraete- Bezeichnung',
     `fz_ger_herst` char(50) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Hersteller',
     `fz_ger_sernr` char(50) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Serien- Nummer',
     `fz_ger_baujahr` char(4) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Baujahr',
     `fz_ger_typ` char(30) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Typ',
     `fz_ger_leistg` char(10) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Leistung',
     `fz_ger_l_einh` char(50) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Leistungseinheit',
     `fz_ger_foto_1` char(50) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Geraet: Foto 1',
     `fz_ger_f1_beschr` varchar(50) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Bescheibung zu Foto 1',
     `fz_ger_foto_2` char(50) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Geraet: Foto 2',
     `fz_ger_f2_beschr` char(255) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Beschreibung zu Foto 2 Geraet: Beschreibung',
     `fz_ger_foto_3` varchar(50) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Geraete- Teil 1: Foto 1',
     `fz_ger_f3_beschr` varchar(50) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Geraete Teil 2: Beschreibung',
     `fz_ger_foto_4` varchar(50) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Geraete Teil 2 Foto',
     `fz_ger_f4_beschr` varchar(50) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Kommentar zu Foto 4',
     `fz_einb_komm` text COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Kommentar',
     `fz_uidaend` char(10) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Aenderer',
     `fz_aenddat` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Letzte Änderung',
     PRIMARY KEY (`fz_einb_id`)
    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_german2_ci     COMMENT='Fixe Einbau- Geräteten'
        ";
    $return = SQL_QUERY($db,$sql);
    return $return;
} # Ende Funktion Cr_n_fz_fixeinb

function Cr_n_fz_type ($tabelle)
{
    global $debug, $db;

    $sql = "CREATE TABLE IF NOT EXISTS $tabelle (
      `ft_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Fortl. Nr',
      `fz_t_id` int(11) NOT NULL COMMENT 'Fahrzeug- Nr. des Eigentuemers',
      `fz_eignr` char(10) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Eigentümer Nr.',
      `fz_herstell_fg` char(100) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Fahrgestell Hersteller',
      `fz_fgtyp` char(100) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'FZG Typ',
      `fz_idnummer` char(50) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'ID- Nummer',
      `fz_fgnr` char(50) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Fahrgestell- Nr',
      `fz_baujahr` char(4) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Baujahr',
      `fz_eig_gew` int(10) DEFAULT NULL COMMENT 'Eigengewicht',
      `fz_zul_g_gew` int(10) DEFAULT NULL COMMENT 'Zulässiges Gesamt Gewicht',
      `fz_achsl_1` int(10) DEFAULT NULL COMMENT 'Zul. Achlast, 1, Achse',
      `fz_achsl_2` int(10) DEFAULT NULL COMMENT 'Zul. Achlast, 2, Achse',
      `fz_achsl_3` int(10) DEFAULT NULL COMMENT 'Zul. Achlast, 3, Achse',
      `fz_achsl_4` int(10) DEFAULT NULL COMMENT 'Zul. Achlast, 4, Achse',
      `fz_radstand` char(20) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Radstand',
      `fz_spurweite` char(20) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Spurweite Vorne, Hinten',
      `fz_antrachsen` int(4) DEFAULT NULL COMMENT 'Anzahl angetriebener Achsen',
      `fz_lenkachsen` int(4) DEFAULT NULL COMMENT 'Lenkachsen',
      `fz_lenkhilfe` char(100) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Lenkhilfe',
      `fz_allrad` char(30) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Allrad',
      `fz_bremsanl` char(100) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Bremsanlage',
      `fz_hilfbremsanl` char(100) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Hilfsbremsanlage',
      `fz_feststellbr` char(100) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Feststellbremse',
      `fz_verzoegerg` char(100) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Bremsverzoegerung',
      `fz_m_bauform` char(100) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Motor Bauform',
      `fz_herst_mot` char(100) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Motor Hersteller',
      `fz_motornr` char(50) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Motor- Nummer',
      `fz_hubraum` int(10) DEFAULT NULL COMMENT 'Hubraum',
      `fz_bohrung` decimal(10,0) DEFAULT NULL COMMENT 'Bohrung',
      `fz_hub` decimal(10,0) DEFAULT NULL COMMENT 'Hub',
      `fz_kraftst` char(30) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Kraftstoffart',
      `fz_gemischaufb` char(50) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Gemischaufbereitung',
      `fz_kuehlg` char(50) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Kühlung',
      `fz_leistung_kw` int(10) DEFAULT NULL COMMENT 'Leistung in kW',
      `fz_leistung_ps` int(10) DEFAULT NULL COMMENT 'Leistung in PS',
      `fz_leist_drehz` char(50) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Max. Leistung bei Drehzahl',
      `fz_verbrauch` char(50) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Verbrauch',
      `fz_antrieb` char(50) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Antrieb',
      `fz_bereifung_1` char(100) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Bereifung 1. Achse',
      `fz_bereifung_2` char(100) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Bereifung 2. Achse',
      `fz_bereifung_3` char(100) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Bereifung 3. Achse',
      `fz_bereifung_4` char(100) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Bereifung 4. Achse',
      `fz_getriebe` char(100) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Getriebe',
      `fz_herst_aufb` char(100) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Aufbau Hersteller',
      `fz_aufb_bauart` char(100) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Aufbau Bauart',
      `fz_aufbau` char(100) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Aufbau',
      `fz_anh_kuppl` char(100) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Anhaenger Kupplung',
      `fz_geschwind` varchar(10) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Maximale Geschwindigkeit',
      `fz_sitzpl_zul` int(3) DEFAULT NULL COMMENT 'Max. ZulÃ¤ss. Sitzplaetze',
      `fz_sitzpl_1` int(2) DEFAULT NULL COMMENT 'Sitzplätze 1. Reihe',
      `fz_sitzpl_2` int(2) DEFAULT NULL COMMENT 'Sitzplätze 2. Reihe',
      `fz_abmessg_mm` char(50) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Fahrzeug-Abmessungen',
      `fz_heizung` char(100) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Heizung',
      `fz_farbe` char(100) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Farbe',
      `fz_aenduid` char(10) COLLATE utf8_german2_ci DEFAULT NULL COMMENT 'Aenderer',
      `fz_aenddat` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Letzte Aenderung',
       PRIMARY KEY (`ft_id`)
    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_german2_ci COMMENT='Zulassungsdaten, Typenschein' ";
    $return = SQL_QUERY($db,$sql);
    return $return;
} # Ende Funktion Cr_n_fz_type

function Cr_n_fz_laderaum ($tabelle)
{
    global $debug, $db;

    $sql = "CREATE TABLE IF NOT EXISTS $tabelle (
      `lr_id` int(10) NOT NULL COMMENT 'Fortlaufende Nummer',
      `lr_eignr` char(10) COLLATE utf8_german2_ci NOT NULL COMMENT 'Eigentümer- Nummer',
      `lr_fzg` char(10) COLLATE utf8_german2_ci NOT NULL COMMENT 'Fahrzeug- Nummer',
      `lr_raum` char(60) COLLATE utf8_german2_ci NOT NULL COMMENT 'Laderaum',
      `lr_beschreibung` text COLLATE utf8_german2_ci NOT NULL COMMENT 'Beschreibung',
      `lr_foto_1` char(60) COLLATE utf8_german2_ci NOT NULL COMMENT 'Foto 1',
      `lr_komm_1` text COLLATE utf8_german2_ci NOT NULL COMMENT 'Beschreibung 1',
      `lr_foto_2` char(60) COLLATE utf8_german2_ci NOT NULL COMMENT 'Foto 2',
      `lr_komm_2` text COLLATE utf8_german2_ci NOT NULL COMMENT 'Beschreibung 2',
      `lr_foto_3` char(60) COLLATE utf8_german2_ci NOT NULL COMMENT 'Foto 2',
      `lr_komm_3` text COLLATE utf8_german2_ci NOT NULL COMMENT 'Beschreibung 3',
      `lr_foto_4` char(60) COLLATE utf8_german2_ci NOT NULL COMMENT 'Foto 4',
      `lr_komm_4` text COLLATE utf8_german2_ci NOT NULL COMMENT 'Beschreibung 4',
      `lr_uidaend` char(10) COLLATE utf8_german2_ci NOT NULL COMMENT 'Änderer',
      `lr_aenddate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Letzte Änderung',
      PRIMARY KEY (`lr_id`)
    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_german2_ci COMMENT 'Laderaum- Belegung' ";
    $return = SQL_QUERY($db,$sql);
    return $return;
} # Ende Funktion Cr_n_fz_laderaum


function Cr_n_fz_typis_aend ($tabelle)
{
    global $debug, $db;

    $sql = "CREATE TABLE IF NOT EXISTS $tabelle (
       `fz_typ_id` int(10) NOT NULL COMMENT 'Fortlaufende Nummer',
       `fz_id` int(10) NOT NULL COMMENT 'Fahrzeug- Nummer',
       `fz_t_aenddat` char(10) COLLATE utf8_german2_ci NOT NULL COMMENT 'Datum der Änderung',
       `fz_infotext` char(100) COLLATE utf8_german2_ci NOT NULL COMMENT 'Beschreibung',
       `fz_uidaend` char(10) COLLATE utf8_german2_ci NOT NULL COMMENT 'Änderer',
       `fz_aenddat` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Letzte Änderung',
        PRIMARY KEY (`fz_typ_id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_german2_ci  COMMENT 'Typisierte Änderungen'";
    $return = SQL_QUERY($db,$sql);
    return $return;
} # Ende Funktion Cr_n_fz_typis_aend

function Cr_n_fz_reparat ($tabelle)
{
    global $debug, $db;

    $sql = "CREATE TABLE IF NOT EXISTS $tabelle (
        `fz_rep_id` int(10) NOT NULL COMMENT 'Fortlaufende Nummer',
        `fz_id` int(10) NOT NULL COMMENT 'Fahrzeug- Nummer',
        `fz_repdat` char(10) COLLATE utf8_german2_ci NOT NULL COMMENT 'Datum der Rep.',
        `fz_reptext` text COLLATE utf8_german2_ci NOT NULL COMMENT 'Rpartur- Beschreibung',
        `fz_uidaend` char(10) COLLATE utf8_german2_ci NOT NULL COMMENT 'Änderer',
        `fz_aenddat` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Letzte Änderung',
        PRIMARY KEY (`fz_rep_id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_german2_ci COMMENT 'Reparaturen'";
    $return = SQL_QUERY($db,$sql);
    return $return;
} # Ende Funktion Cr_n_fz_reparat




?>
