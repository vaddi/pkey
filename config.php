<?php 

//
// Konfigurationsdatei (0 = aus, 1 = an)
//

// Seitendaten
define ("BASE", "http://spike/pkey/");   // Basisurl mit end /

define ("SITE_NAME", "product-keymanager");		// Der Name der Seite
define ("SITE_TITLE", "Produkt-Schl&uuml;sselverwaltung"); // Untertitel oder Slogan
define ("SITE_DESCRIPTION", "a small PHP based website to manage productkeys from your software."); // Kurzbeschreibung 
define ("SITE_KEYWORDS", ""); // Suchwörter 
define ("SITE_PUBLISHER", "TÜV-Nord Braunschweig"); // Veröffentlicher 

// Datenbank 
define ("SQL_PATH", "localhost");	// Der verwendete Pfad zur Datenbank, z.B. localhost oder http://db.de:3306/
define ("SQL_USER", "dbuser");											// Der verwendete Benutzername
define ("SQL_PASSWD", "unsicher");										// Das verwendete Passwort
define ("SQL_NAME", "pkey");			      			// Name der Haupttabelle

// Haupt Navigation
define ("HEADER_LOGIN", "1");		        // Anzeigen des Loginbuttons 
define ("MAINSITE", "1");		        		// Willkommensseite anzeigen 
define ("WAIT", "3");               		// Standardwartezeit zum weiterleiten nach Aktionen

// Anderes 
define ("VALIDATOR", "0");          	  // Anzeigen des w3c validators im Footer
define ("SITE_LOADTIME", "1");      	  // Anzeigen der Seitenladezeit im Footer
define ("DEBUG", "0");             		 	// Debuger 0 none, 1 only messages, 2 heavy
define ("SALT", "asgfuUuh9842hrop8sugfkle");	// Passwdsalt

?>
