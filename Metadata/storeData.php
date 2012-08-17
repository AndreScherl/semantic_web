<?php
	/**
	 * Speichern/Aktualisieren der eingebenen Metadaten in der Tabelle dasis_modmeta
	 * 
	 * @author	Andre Scherl
	 * @version	1.0 - 09.09.2010
	 *
	 * Copyright (C) 2012, Andre Scherl
	 * You should have received a copy of the GNU General Public License
 	 * along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
	 */
	
	require_once("../../../config.php");
	require_once("../semantic_web_lib.php");

	// Das zu speichernde Datenobjekt erstellen
	$data = new object();

	$data->modname = optional_param("modname", "NULL", PARAM_TEXT);
	$data->instance = optional_param("instance", 0, PARAM_INT);
	$data->coursemoduleid = optional_param("coursemoduleid", "NULL", PARAM_INT);
	
	$data->id = optional_param("id", "NULL", PARAM_INT);
	
	if(!$data->title = optional_param("title", "NULL", PARAM_TEXT)){
		$data->title = $DB->get_field($data->modname, "name", array("id"=>$data->instance));
	}
	
	if(!$data->description = optional_param("description", "NULL", PARAM_TEXT)){
		$data->description = get_summary_of_mod($data->instance, $data->modname);
	}
	
	$data->shortname = optional_param("shortname", "NULL", PARAM_TEXT);
	$data->linguistic_requirement = optional_param("linguistic_requirement", "NULL", PARAM_NUMBER);
	$data->social_requirement = optional_param("social_requirement", "NULL", PARAM_NUMBER);
	$data->logical_requirement = optional_param("logical_requirement", "NULL", PARAM_NUMBER);
	$data->learningstyle_perception = optional_param("learningstyle_perception", "NULL", PARAM_NUMBER);
	$data->learningstyle_organisation = optional_param("learningstyle_organisation", "NULL", PARAM_NUMBER);
	$data->learningstyle_perspective = optional_param("learningstyle_perspective", null, PARAM_NUMBER);
	$data->learningstyle_input = optional_param("learningstyle_input", "NULL", PARAM_NUMBER);
	$data->learningstyle_processing = optional_param("learningstyle_processing", "NULL", PARAM_NUMBER);
	$data->difficulty = optional_param("difficulty", "NULL", PARAM_NUMBER);
	$data->learning_time = optional_param("learning_time", "NULL", PARAM_INT);
	$data->keywords = optional_param("keywords", "NULL", PARAM_TEXT);
	$data->learning_tasks = optional_param("learning_tasks", "NULL", PARAM_TEXT);
	$data->taxonomy = optional_param("taxonomy", "NULL", PARAM_TEXT);
	$data->catalog = optional_param("catalog", "NULL", PARAM_TEXT);
	
	// Überprüfen, ob die Lernaktivität schon vorhanden ist. Ja=>aktualisieren, Nein=>einfügen
	if($DB->record_exists("dasis_modmeta", array("coursemoduleid" => $_POST["coursemoduleid"]))) {
		$DB->update_record("dasis_modmeta", $data);
	}else{
		$DB->insert_record("dasis_modmeta", $data, $returnid=true, $primarykey='id');
	}
	
	// Der Titel der Lernaktivität wird in der entsprechenden Tabelle gespeichert
	$moduleData = new object();
	$moduleData->name = $data->title;
	$moduleData->id = $data->instance;
	$DB->update_record($data->modname, $moduleData);
	
	// Die Zusammenfassung der Lernaktivität wird in der entsprechenden Tabelle gespeichert
	set_summary_of_mod($data->instance, $data->modname, $data->description);
	
	redirect("edit_metadata.php?id=$data->coursemoduleid");

?>