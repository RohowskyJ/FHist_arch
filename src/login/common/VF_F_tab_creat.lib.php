<?php

/**
 * Erstellung von Tabellen, wenn nötig.
 */

## Tabellen neu anlegen, falls noch nicht existent, Referat5
/*
 *
 */

function Cr_n_Medien_Daten($tabelle)
{
    global $debug, $db;
    mysqli_set_charset($db, "utf8mb4");

    $sql = "CREATE TABLE IF NOT EXISTS $tabelle (
        `md_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Fortl. Nr.',
        `md_eigner` int(11) NOT NULL COMMENT 'Eigentümer- Nr',
        `md_Urheber` varchar(50) NOT NULL COMMENT 'Urheber- Name',
        `md_dsn_1` varchar(60) DEFAULT NULL COMMENT 'Dateiname',
        `md_aufn_datum` varchar(15) DEFAULT NULL COMMENT 'Aufnahme Datum = Verzeichnis',
        `md_beschreibg` varchar(1024) DEFAULT NULL COMMENT 'Beschreibung',
        `md_namen` varchar(1024) DEFAULT NULL COMMENT 'Foto Name',
        `md_sammlg` varchar(40) DEFAULT NULL COMMENT 'Sammlung',
        `md_fw_id` varchar(10) DEFAULT NULL COMMENT 'Eigner- Nr.',
        `md_suchbegr` varchar(1024) DEFAULT NULL COMMENT 'Suchbegriffe',
        `md_media` set('Audio','Foto','Video') DEFAULT NULL COMMENT 'Medium',
        `md_Med_anz` int(11) DEFAULT NULL COMMENT 'Anzahl der Eintr.',
        `md_aenduid` varchar(4) DEFAULT NULL COMMENT 'Letzter Änderer',
        `md_aenddat` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Letzte Änderung',
        PRIMARY KEY (`md_id`),
        FULLTEXT KEY `md_sammlg` (`md_sammlg`),
        FULLTEXT KEY `md_feuerwehr` (`md_feuerwehr`) 
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Digitale- Medien- Archiv' ";

    $return = SQL_QUERY($db, $sql);
    return $return;
} # Ende Funktion Cr_n_fo_varchar(12)n

function Cr_n_ar_chivdt($tabelle)
{
    global $debug, $db;
    mysqli_set_charset($db, "utf8mb4");
    $sql = "CREATE TABLE IF NOT EXISTS $tabelle (
      `ad_id` int(11)  NULL  AUTO_INCREMENT COMMENT 'Archiv- ID',
      `ad_eignr` int(11)  NULL  COMMENT 'Eigentümernummer',
      `ad_sg` varchar(2)  NULL  COMMENT 'Sachgebiet',
      `ad_subsg` varchar(2)  NULL  COMMENT 'SubSachgeb.',
      `ad_lcsg` varchar(2)  NULL  COMMENT 'Lokales SG',
      `ad_lcssg` varchar(2)  NULL  COMMENT 'Lokales SubSG',
      `ad_ao_fortlnr` smallint(6) DEFAULT NULL COMMENT 'Fortl. Archivalien-Nr',
      `ad_sammlg` varchar(20)  NULL  COMMENT 'Sammlungs- Abkürzung',
      `ad_doc_date` varchar(12)  NULL  COMMENT 'Archvalien- Datum',
      `ad_type` varchar(50)  NULL  COMMENT 'Dokument- Typ',
      `ad_format` varchar(3)  NULL  COMMENT 'Format',
      `ad_keywords` varchar(512)  NULL  COMMENT 'Suchbegriffe (in Karteiformat, Trennung mit Beistrich)',
      `ad_beschreibg` varchar(1024)  NULL  COMMENT 'Beschreibung',
      `ad_wert_orig` decimal(10,2)  NULL  COMMENT 'Wert des Originals',
      `ad_orig_waehrung` varchar(50)  NULL  COMMENT 'Währung des Originalwertes',
      `ad_wert_kauf` decimal(10,2)  NULL  COMMENT 'Wert beim Ankauf',
      `ad_kauf_waehrung` varchar(50)  NULL  COMMENT 'Währung beim Kauf',
      `ad_wert_besch` decimal(10,2)  NULL  COMMENT 'Wiederbeschaffungs- Wert',
      `ad_besch_waehrung` varchar(50)  NULL  COMMENT 'Währung des Wiederbeschaffungswertes',
      `ad_namen` varchar(1024)  NULL  COMMENT 'Im Dokument vorkommende Namen, Trennung mit Beistrich',
      `ad_doc_1` varchar(100)  NULL  COMMENT 'Dokument 1',
      `ad_doc_2` varchar(100)  NULL  COMMENT 'Dokument 2',
      `ad_doc_3` varchar(100)  NULL  COMMENT 'Dokument 3',
      `ad_doc_4` varchar(100) CHARACTER SET utf32 COLLATE utf32_unicode_ci  NULL  COMMENT 'Dokument 4',
      `ad_isbn` varchar(15)  NULL  COMMENT 'ISBN- Nummer',
      `ad_lagerort` varchar(100)  NULL  COMMENT 'Lagerort',
      `ad_l_raum` varchar(50)  NULL  COMMENT 'Lagerraum',
      `ad_l_kasten` varchar(50)  NULL  COMMENT 'Kasten im Lager',
      `ad_l_fach` varchar(50)  NULL  COMMENT 'Fach im Kasten',
      `ad_l_pos_x` varchar(50)  NULL  COMMENT 'Position X',
      `ad_l_pos_y` varchar(50)  NULL  COMMENT 'Position Y',
      `ad_neueigner` varchar(100)  NULL  COMMENT 'Neuer Eigentuemer',
      `ad_uidaend` varchar(4)  NULL  COMMENT 'Aenderer',
      `ad_aenddat` timestamp NULL DEFAULT NULL COMMENT 'Letzte Aenderung',
       PRIMARY KEY (`ad_id`)
    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci  COMMENT='Archivalien- Verzeichnis' ";

    $return = SQL_QUERY($db, $sql);
    return $return;
} # Ende Funktion Cr_n_ar_chivdt

function Cr_n_ar_ch_verl($tabelle)
{
    global $debug, $db;
    mysqli_set_charset($db, "utf8mb4");
    $sql = "CREATE TABLE IF NOT EXISTS $tabelle (
      `al_id` int(11)  NULL  AUTO_INCREMENT COMMENT 'Fortl. Nr.',
      `ad_id` char(15)  NULL  COMMENT 'Archiv- ID',
      `al_eignr` int(15)  NULL  COMMENT 'Eigentümernummer',
      `al_verliehen_an` varchar(100) CHARACTER SET utf8 COLLATE latin1_german2_ci  NULL  COMMENT 'Leihnehmer' ,
      `al_verleihgrund` varchar(100) CHARACTER SET utf8 COLLATE latin1_german2_ci  NULL  COMMENT 'Verleihgrund',
      `al_verleih_beg` varchar(12)  NULL  COMMENT 'Verleih Beginn',
      `al_verleih_end` varchar(12)  NULL  COMMENT 'Verleih Ende',
      `al_zustand_aus` char(100)  NULL  COMMENT 'Zustand bei Ausgabe',
      `al_zustand_ret` char(100)  NULL  COMMENT 'Zustand bei Retourgabe',
      `al_ausg_bild` char(60)  NULL  COMMENT 'Bild bei Ausgabe',
      `al_ret_bild` char(60)  NULL  COMMENT 'Bild bei Retournierung',
      `al_verl_aus_dat` varchar(12)  NULL  COMMENT 'Datum Ausgabe',
      `al_verl_ret_dat` varchar(12)  NULL  COMMENT 'Datum Retourgabe',
      `al_uidaend` varchar(4) CHARACTER SET utf8 COLLATE latin1_german2_ci  NULL  COMMENT 'Änderer',
      `al_aenddat` timestamp  NULL  DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Letzte Änderung',
      PRIMARY KEY (`al_id`)
  ) ENGINE=MyISAM DEFAULT  CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci  COMMENT='Archivalien- Verleih' ";
    $return = SQL_QUERY($db, $sql);
    return $return;
} # Ende Funktion Cr_n_ar_ch_verl

function Cr_n_zt_inhalt($tabelle)
{
    global $debug, $db;
    mysqli_set_charset($db, "utf8mb4");
    $sql = "CREATE TABLE IF NOT EXISTS $tabelle (
      `ih_id` mediumint(20)  NULL  AUTO_INCREMENT COMMENT 'Fortl. Nr.',
      `ih_zt_id` tinyint(4)  NULL  DEFAULT 0 COMMENT 'Zeitungs-ID',
      `ih_jahrgang` char(4) CHARACTER SET latin1 COLLATE latin1_german1_ci  NULL  COMMENT 'Jahrgang',
      `ih_jahr` varchar(4) CHARACTER SET latin1 COLLATE latin1_german1_ci  NULL  COMMENT 'Jahr',
      `ih_nr` varchar(4) CHARACTER SET latin1 COLLATE latin1_german1_ci  NULL  COMMENT 'Nummer',
      `ih_kateg` char(1) CHARACTER SET latin1 COLLATE latin1_german1_ci  NULL  COMMENT 'Kategorie',
      `ih_sg` char(1) CHARACTER SET latin1 COLLATE latin1_german1_ci  NULL  COMMENT 'Sachgebiet',
      `ih_ssg` char(1) CHARACTER SET latin1 COLLATE latin1_german1_ci  NULL  COMMENT 'Unter- Gebiet',
      `ih_gruppe` varchar(30) CHARACTER SET latin1 COLLATE latin1_german1_ci  NULL  COMMENT 'Gruppe',
      `ih_titel` varchar(250) CHARACTER SET latin1 COLLATE latin1_german1_ci  NULL  COMMENT 'Titel/Firma',
      `ih_titelerw` varchar(250) CHARACTER SET latin1 COLLATE latin1_german1_ci  NULL  COMMENT 'Titelerweiterung',
      `ih_autor` varchar(120) CHARACTER SET latin1 COLLATE latin1_german2_ci  NULL  COMMENT 'Autor',
      `ih_email` varchar(60) CHARACTER SET latin1 COLLATE latin1_german1_ci  NULL  COMMENT 'E-Mail',
      `ih_tel` varchar(60) CHARACTER SET latin1 COLLATE latin1_german2_ci  NULL  COMMENT 'Telefon - Handy',
      `ih_fax` varchar(60) CHARACTER SET latin1 COLLATE latin1_german2_ci  NULL  COMMENT 'Fax',
      `ih_seite` varchar(4) CHARACTER SET latin1 COLLATE latin1_german1_ci  NULL  COMMENT 'Seite',
      `ih_spalte` varchar(4) CHARACTER SET latin1 COLLATE latin1_german1_ci  NULL  COMMENT 'Spalte',
      `ih_fwehr` varchar(250) CHARACTER SET latin1 COLLATE latin1_german1_ci  NULL  COMMENT 'Feuerwehr',
      `ih_uidaend` varchar(4) CHARACTER SET latin1 COLLATE latin1_german1_ci  NULL  COMMENT 'Änderer',
      `ih_aenddat` timestamp  NULL  DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Datum der letzten Änderung',
      PRIMARY KEY (`ih_id`)
   ) ENGINE=MyISAM AUTO_INCREMENT=1300 DEFAULT CHARSET=latin1 COMMENT='Zeitungen, Inhalte' ";


    $return = SQL_QUERY($db, $sql);
    return $return;
} # Ende Funktion Cr_n_ar_ch_verl



function Cr_n_in_ventar($tabelle)
{
    global $debug, $db;
    mysqli_set_charset($db, "utf8mb4");
    $sql = "CREATE TABLE IF NOT EXISTS $tabelle (
  `in_id` int(11)  NULL  AUTO_INCREMENT COMMENT 'Fortl. Nr.',
  `ei_id` int(11)  NULL  COMMENT 'Eigentümer- Nummer',
  `in_invjahr` varchar(4) DEFAULT NULL COMMENT 'Inventarisierungs- Jahr',
  `in_eingbuchnr` varchar(15)  NULL  COMMENT 'Eingangsbuch Nummer',
  `in_eingbuchdat` varchar(12)  NULL  COMMENT 'Eingangsbuch Datum',
  `in_altbestand` varchar(1)  NULL  COMMENT 'Altbestand',
  `in_invnr` varchar(6)  NULL  COMMENT 'Inventar- Nummer',
  `in_sammlg` varchar(20)  NULL  COMMENT 'Sammlung',
  `in_epoche` varchar(3)  NULL  COMMENT 'Epoche',
  `in_zustand` varchar(500)  NULL  COMMENT 'Zustand',
  `in_entstehungszeit` varchar(10)  NULL  COMMENT 'Entstehungszeit',
  `in_hersteller` char(100)  NULL  COMMENT 'Hersteller',
  `in_herstld` char(2)  NULL  COMMENT 'Herstellungs- Land',
  `in_aufbld_1` char(2)  NULL  COMMENT 'Aufbau- Land 1',
  `in_aufbld_2` char(2)  NULL  COMMENT 'Aufbau- Land 2',
  `in_aufbld_3` char(2)  NULL  COMMENT 'Aufbau- Land 3',
  `in_nutzld` char(2)  NULL  COMMENT 'Nutzungs- Land',
  `in_bezeichnung` varchar(100)  NULL  COMMENT 'Beszeichnung',
  `in_beschreibg` text  NULL  COMMENT 'Beschreibung',
  `in_det_beschrbg` varchar(10)  NULL  COMMENT 'Beschreibungen ?? Obsolete',
  `in_wert` decimal(15,2)  NULL  COMMENT 'Wert ?? Obsolete',
  `in_wert_neu` decimal(15,2)  NULL  COMMENT 'Neuwert',
  `in_neu_waehrg` varchar(50)  NULL  COMMENT 'Wahrung beim Akauf',
  `in_wert_kauf` decimal(15,2)  NULL  COMMENT 'Kaufwert',
  `in_kauf_waehrung` varchar(50)  NULL  COMMENT 'WÃ¤hrung beim Ankauf',
  `in_wert_besch` decimal(15,2)  NULL  COMMENT 'Wiederbeschaffungs- Wert',
  `in_besch_waehrung` varchar(50)  NULL  COMMENT 'Wiederbeschaffungswert',
  `in_abmess` varchar(50)  NULL  COMMENT 'Abmessungen lxbxh in mm',
  `in_gewicht` varchar(50)  NULL  COMMENT 'Gewicht',
  `in_linkerkl` varchar(45)  NULL  COMMENT 'Detailbeschreibung per Link',
  `in_kommentar` text  NULL  COMMENT 'Kommentar',
  `in_namen` varchar(1024)  NULL  COMMENT 'Name von Personen im Zusammenhang genannt',
  `in_vwlinks` varchar(45)  NULL  COMMENT 'Weitere Links ? ?? Obsolete',
  `in_beschreibung` varchar(60)  NULL  COMMENT 'Beschreibung ? Obsolete',
  `in_foto_1` char(50)  NULL  COMMENT 'Foto 1',
  `in_fbeschr_1` text  NULL  COMMENT 'Beschreibung Foto 1',
  `in_foto_2` char(50)  NULL  COMMENT 'Foto_2',
  `in_fbeschr_2` text  NULL  COMMENT 'Beschreibung Foto 2',
  `in_refindex` varchar(45)  NULL  COMMENT 'Ref-Index',
  `in_raum` varchar(40)  NULL  COMMENT 'Lagerraum',
  `in_platz` varchar(40)  NULL  COMMENT 'Lagerplatz',
  `in_erstdat` varchar(12)  NULL  COMMENT 'Erstehungsdatum',
  `in_ausgdat` varchar(12)  NULL  COMMENT 'Abgabe-Datum',
  `in_neueigner` varchar(100) DEFAULT NULL COMMENT 'Neuer Eigentümer',
  `in_uidaend` varchar(4) DEFAULT NULL COMMENT 'Änderer',
  `in_aenddat` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Letzte Änderung',
  PRIMARY KEY (`in_id`)
) ENGINE=MyISAM DEFAULT  CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci  ";


    $return = SQL_QUERY($db, $sql);
    return $return;
} # Ende Funktion Cr_n_in_vent_n

function Cr_n_in_vent_verleih($tabelle)
{
    global $debug, $db;
    mysqli_set_charset($db, "utf8mb4");
    $sql = "CREATE TABLE IF NOT EXISTS $tabelle (
   `vl_id` tinyint(4)  NULL  COMMENT 'Fortl. Nr.',
  `in_id` int(19)  NULL  COMMENT 'Invntar-Nummer',
  `ei_id` int(15)  NULL  COMMENT 'Eigentuemer- Nummer',
  `ei_invnr` varchar(6) COLLATE utf8mb4_german2_ci DEFAULT NULL COMMENT 'Inventar- Nummer',
  `ei_zustand_aus` char(200) COLLATE utf8mb4_german2_ci  NULL  COMMENT 'Zustand Ausgabe',
  `ei_zustand_ret` char(200) COLLATE utf8mb4_german2_ci  NULL  COMMENT 'Zustand retour',
  `ei_zust_aus_bild` char(60) COLLATE utf8mb4_german2_ci  NULL  COMMENT 'Zustand Ausgabe, Bild',
  `ei_zust_ret_bild` char(60) COLLATE utf8mb4_german2_ci  NULL  COMMENT 'Zustand retour, Bild',
  `ei_leiher` varchar(100) COLLATE utf8mb4_german2_ci DEFAULT NULL COMMENT 'Leiher',
  `ei_leihvertr` char(1) COLLATE utf8mb4_german2_ci DEFAULT NULL COMMENT 'Leihvertrag gedruckt',
  `ei_verlbeg` varchar(12)  NULL  COMMENT 'Verleih Beginn',
  `ei_verlend` varchar(12)  NULL  COMMENT 'Verleih Ende',
  `ei_verlgrund` varchar(250) COLLATE utf8mb4_german2_ci DEFAULT NULL COMMENT 'Verleih Grund',
  `ei_verlrueck` varchar(12)  NULL  COMMENT 'Rueckgabe- Datum',
  `ei_verluebn` varchar(15) COLLATE utf8mb4_german2_ci  NULL  COMMENT 'Übernehmer - ID',
  `ei_uidaend` varchar(4) COLLATE utf8mb4_german2_ci  NULL  DEFAULT '' COMMENT 'Änderer',
  `ei_aenddat` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Letzte Änderung',
  PRIMARY KEY (`vl_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci  COMMENT  'Inventarverleih'";

    $return = SQL_QUERY($db, $sql);
    return $return;
} # Ende Funktion Cr_n_in-vent_verleih
