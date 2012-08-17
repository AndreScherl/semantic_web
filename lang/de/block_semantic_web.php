<?php
	/*
 	 * Copyright (C) 2012, Andre Scherl
 	 * You should have received a copy of the GNU General Public License
  	 * along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
  	 */
  	 
	///// Blockeinstellungen
	$string['blockname'] = 'Netznavigation';
	$string['pluginname'] = 'Netznavigation';
	$string['configcontent'] = 'Inhalt bearbeiten';
	$string['connectedcourses'] = 'Vernetzte Kurse';
	$string['edit_metadata'] = 'Metadaten bearbeiten';
	$string['showweb'] = 'Netz anzeigen';
	$string['semanticweb_settings'] = 'Einstellungen des semantischen Netzes';
	$string['depth'] = 'Schrittweite im Netz';
	$string['case_collection'] = 'Fälle sammeln';
	$string['web_animation'] = 'Netz animieren';
	
	$string['bundle_settings'] = 'Einstellungen der Kurs-Bündel';
	$string['bundles_containing_the_course'] = 'Kurs enthalten in folgenden Bündeln';
	$string['add_course_to_bundle'] = 'Kurs einem Bündel hinzufügen';
	$string['newbundle'] = '-> Neues Bündel anlegen <-';
	$string['name_of_bundle'] = 'Name des Bündels';
	$string['create_bundle'] = 'Bündel anlegen';
	$string['contained_courses'] = 'Enthaltene Kurse';
	$string['add_course_to_bundle'] = 'Kurs dem folgenden Bündel hinzufügen';
	$string['add_course_to_this_bundle'] = 'Den folgenden Kurs diesem Bündel hinzufügen';
	$string['bundle_overview'] = 'Bündelüberblick';
	$string['bundle'] = 'Bündel';
	$string['click_enlarge'] = 'Klick für mehr!';
	$string['click_hide'] = 'Klick für weniger!';
	
	$string['learning_path_settings'] = 'Einstellungen für Lernpfade';
	$string['learning_pathname'] = 'Name des Lernpfades';
	$string['learning_path'] = 'Lernpfad';
	$string['path_name'] = 'Pfadname';
	$string['create_new_path'] = '-> Neuen Pfad erstellen <-';
	$string['new_pathnode'] = 'Neuen Knoten zum Pfad hinzufügen';
	
	///// Allgemeines
	$string['add'] = 'Hinzufügen';
	$string['cancel'] = 'Abbrechen';
	$string['submitclose'] = 'Speichern und Schließen';
	$string['submit'] = 'Speichern';
	$string['remove'] = 'Entfernen';
	$string['back'] = 'Zurück';
	$string['next'] = 'Weiter';
	$string['about'] = 'Info über...';
	$string['about_help'] = "<p>Diese Netznavigation ist ein Bestandteil der Moodle-Erweiterung <I>DASIS</I>.\nMithilfe der Navigationsunterstützung sollen Studierende beim Lernen fächerübergreifender Inhalte unterstützt werden.</p><p>Die Moodle-Erweiterung <I>DASIS</I> besteht aus den folgenden Teilen:<ul><li>Block: Netznavigation</li><li>Block: Lerneradaption (arbeitet im Hintergrund)</li><li>Lernaktivitäten: Lerntypentests</li><li>Block: Persönliche Lerneigenschaften</li></ul></p><p><I>DASIS</I> ist von Andre Scherl im Rahmen seiner Promotion entwickelt worden. Dabei wurde das Moodle-Plugin <I>iLMS</I> von Gert Sauertstein (2007) auf die Bedürfnisse von <I>DASIS</I> angepasst, sowie das Visualisierungs-Framework <I>protovis</I> der University of Stanford zur Generierung des Navigationsnetzes verwendet.</p>";
	
	$string['pleaseselect'] = 'Bitte wählen.';
	$string['activity_pleaseselect'] = 'Zu bearbeitende Lernaktivität wählen.';
	$string['module'] = 'Modul';
	
	///// Zugriffsberechtigungen
	$string['semantic_web:editmetadata'] = 'Metadaten der Lernaktivität ändern';
	$string['semantic_web:deletebundle'] = 'Bündel löschen';
	$string['semantic_web:managepaths'] = 'Lernpfade erstellen, bearbeiten, löschen';
	
	///// Semantische Relationen
	$string['relations'] = 'Semantische Relationen';
	$string['relation_pleaseselectsource'] = 'Bitte Lernaktivität wählen.';
	$string['relation_pleaseselect'] = 'Bitte Relation wählen.';
	$string['relation_pleaseselecttarget'] = 'Bitte Lernaktivität wählen.';
	$string['relation_vertieft']='vertieft';
	$string['relation_erlaeutert']='erläutert';
	$string['relation_beispiel']='ist Beispiel zu';
	$string['relation_anwendung']='ist Anwendung zu';
	$string['relation_illustriert']='illustriert';
	$string['relation_querverweis']='ist Querverweis zu';
	$string['relation_exkurs']='ist Exkurs zu';
	$string['relation_fasstzusammen']='ist Zusammenfassung zu';
	$string['relation_bautauf']='baut auf';
	$string['relation_wiederholt']='wiederholt';
	$string['relation_setztvoraus']='benötigt';
	$string['relation_prueft']='prüft den Lernstoff zu';
	$string['show_semantic_overview'] = 'Relationenüberblick';
	
	///// Eigenschaften der Lernaktivitäten
	$string['title'] = 'Titel';
	$string['shortname'] = 'Kurzer Titel';
	$string['description'] = 'Beschreibung';
	$string['keywords'] = 'Schlüsselbegriffe';
	$string['learning_tasks'] = 'Lernziele';
	$string['taxonomy'] = 'Taxonomie';
	$string['taxonomy_example'] = 'z.B. Physik:Optik:Instrumente:Lupe';
	$string['learning_time'] = 'Geschätzte Bearbeitungsdauer (in min.)';
	$string['catalog'] = 'Katalognr. (ISSN, ISBN,...)';
	
	// Schwierigkeitsgrad
	$string['difficulty'] = 'Schwierigkeitsgrad';
	$string['difficulty_verylow'] = 'sehr leicht';
	$string['difficulty_low'] = 'leicht';
	$string['difficulty_normal'] = 'durchschnittlich';
	$string['difficulty_high'] = 'anspruchsvoll';
	$string['difficulty_veryhigh'] = 'sehr anspruchsvoll';
	
	// Sprachlicher, matematisch-logischer Anspruch
	$string['linguistic_requirement'] = 'Sprachlicher Anspruch';
	$string['linguistic_requirement_verylow'] = 'sehr gering';
	$string['linguistic_requirement_low'] = 'gering';
	$string['linguistic_requirement_normal'] = 'durchschnittlich';
	$string['linguistic_requirement_high'] = 'hoch';
	$string['linguistic_requirement_veryhigh'] = 'sehr hoch';
	
	$string['logical_requirement'] = 'Mathematisch logischer Anspruch';
	$string['logical_requirement_verylow'] = 'sehr gering';
	$string['logical_requirement_low'] = 'gering';
	$string['logical_requirement_normal'] = 'durchschnittlich';
	$string['logical_requirement_high'] = 'hoch';
	$string['logical_requirement_veryhigh'] = 'sehr hoch';
	
	// Sozialer Anspruch (Bearbeitungsmodus)
	$string['social_requirement'] = 'Bearbeitungsmodus';
	$string['social_requirement_verylow'] = 'selbstständig';
	$string['social_requirement_low'] = 'eher selbstständig';
	$string['social_requirement_normal'] = 'gemischt';
	$string['social_requirement_high'] = 'eher kollaborativ';
	$string['social_requirement_veryhigh'] = 'kollaborativ';
	
	///// Lernstileigenschaften der Lernäktivität
	// Lerntypeigenschaft Wahrnehmung bzw. Lerninhaltseigenschaft Inhalt
	$string['learningstyle_perception'] = 'Inhalt';
	$string['learningstyle_perception_verylow'] = 'vorwiegend konkret';
	$string['learningstyle_perception_low'] = 'eher konkret';
	$string['learningstyle_perception_normal'] = 'gemischt';
	$string['learningstyle_perception_high'] = 'eher abstrakt';
	$string['learningstyle_perception_veryhigh'] = 'vorwiegend abstrakt';
	
	// Lerntypeigenschaft Vorgehensweise bzw. Lerninhaltseigenschaft Oragnisation
	$string['learningstyle_organization'] = 'Vorgehensweise';
	$string['learningstyle_organization_verylow'] = 'vorwiegend deduktiv';
	$string['learningstyle_organization_low'] = 'eher deduktiv';
	$string['learningstyle_organization_normal'] = 'gemischt';
	$string['learningstyle_organization_high'] = 'eher induktiv';
	$string['learningstyle_organization_veryhigh'] = 'vorwiegend induktiv';
	
	// Lerntypeigenschaft Sichtweise bzw. Lerninhaltseigenschaft Perspektive/Aufbau
	$string['learningstyle_perspective'] = 'Aufbau';
	$string['learningstyle_perspective_verylow'] = 'sequenziell';
	$string['learningstyle_perspective_low'] = 'eher sequenziell';
	$string['learningstyle_perspective_normal'] = 'gemischt';
	$string['learningstyle_perspective_high'] = 'eher global';
	$string['learningstyle_perspective_veryhigh'] = 'global';
	
	// Lerntypeigenschaft Aufnahme bzw. Lerninhaltseigenschaft Präsentationsform
	$string['learningstyle_input'] = 'Präsentation';
	$string['learningstyle_input_verylow'] = 'vorwiegend visuell';
	$string['learningstyle_input_low'] = 'eher visuell';
	$string['learningstyle_input_normal'] = 'gemischt';
	$string['learningstyle_input_high'] = 'eher verbal';
	$string['learningstyle_input_veryhigh'] = 'vorwiegend verbal';
	
	// Lerntypeigenschaft Interaktivitätstyp
	$string['learningstyle_processing'] = 'Interaktivitätstyp';
	$string['learningstyle_processing_verylow'] = 'aktiv';
	$string['learningstyle_processing_low'] = 'eher aktiv';
	$string['learningstyle_processing_normal'] = 'gemischt';
	$string['learningstyle_processing_high'] = 'eher expositiv';
	$string['learningstyle_processing_veryhigh'] = 'expositiv';
	
	///// Semantisches Netz
	$string['reloadweb'] = 'Netz neu zeichnen!';
	$string['choose_bundle'] = 'Themenkomplex wählen';
	$string['select_path'] = 'Lernpfad wählen';
	$string['caption'] = 'Legende';
	$string['adaptive_path'] = 'Adaptiver Pfad';
	$string['noweb'] = 'Keine Netznavigation für diesen Bereich';
	
	///// Farben
	$string['color'] = "Farbe";
	$string['red'] = "rot";
	$string['orange'] = "orange";
	$string['yellow'] = "gelb";
	$string['green'] = "grün";
	$string['blue'] = "blau";
	$string['purple'] = "lila";
	
	