<?php 
/**
 * Hilfsfunktionen für den Block semantic_web
 * 
 * @package Semantic Web
 * @autor Andre Scherl
 * @version 07.10.2011
 *
 * Copyright (C) 2012, Andre Scherl
 * You should have received a copy of the GNU General Public License
 * along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
 */
	
	
/**
 * Holen der Beschreibung aus der Datenbank, die je Lernaktivität in einer anders benannten Spalte gespeichert ist.
 *
 * @param string $instance	Die id der Instanz (des Lernobjektes innerhalb der jeweiligen Modul-Tabelle)
 * @param string $modname	Der Typ der Lernaktivität (Modul), wie z.B. Seite, Test, Glossar...
 * @return string $summary	Die Beschreibung der Lernaktivität
 */ 
function get_summary_of_mod($instance, $modname) {
	// Datenbankobjekt setzen
	global $DB;
	// Den aktuellen Eintrag aus der Datenbank holen und in ein Objekt speichern
    $record = $DB->get_record($modname, array("id" => $instance));
    // Prüfen, welche Version der Zusammenfassung in den Objekt-Keys enthalten ist und diese dementsprechend setzen
    if(array_key_exists("summary", $record)) {
    	$summary = $record->summary;
    }elseif(array_key_exists("intro", $record)){
   		$summary = $record->intro;
    }elseif(array_key_exists("description", $record)){
    	$summary = $record->description;
    }
    
    return $summary;
}


/**
 * Setzen der Beschreibung in der Datenbank, die je Lernaktivität in einer anders benannten Spalte gespeichert ist.
 *
 * @param string $instance	Die id der Instanz (des Lernobjektes innerhalb der jeweiligen Modul-Tabelle)
 * @param string $modname	Der Typ der Lernaktivität (Modul), wie z.B. Seite, Test, Glossar...
 * @param string $summary	Die Beschreibung der Lernaktivität
 */ 
function set_summary_of_mod($instance, $modname, $summary) {
	global $DB;
	$record = $DB->get_record($modname, array("id" => $instance));
	
    if(array_key_exists("summary", $record)) {
    	$record->summary = $summary;
    	$DB->update_record($modname, $record);
    }elseif(array_key_exists("intro", $record)){
    	$record->intro = $summary;
    	$DB->update_record($modname, $record);
    }elseif(array_key_exists("description", $record)){
    	$record->description = $summary;
    	$DB->update_record($modname, $record);
    }
}

/**
 * Prüfen, ob ein Knoten im Netz für den aktuellen Benutzer sichtbar ist.
 *
 * @param int $nodeid	Die id des zu prüfenden Knotens
 * @return bool 		Gibt wahr oder falsch zurück
 */

function node_visible_for_user($nodeid) {
	global $DB, $CFG;
    $cm = get_coursemodule_from_id($modname=0, $nodeid, $courseid=0, $sectionnum=false, $strictness=IGNORE_MISSING);
	if(coursemodule_visible_for_user($cm, $userid=0)) {
		return true;
	}
	return false;
}


/**
 * Finden der Verknüpfungen eines Knotens im Semantischen Netz, die auf den Knoten $target zeigen
 *
 * @param int $target		Die id der Instanz, auf welche die anderen, mit ihr vernetzten, Lernaktivitäten zeigen
 * @return array $sources	Die ids der vernetzten Lernaktivitäten, die auf target zeigen
 */ 
function getSourcesFromTarget($target){
	global $DB, $CFG, $SESSION;
	$sources = array();
    if($SESSION->dasis_selectedBundle > 0){
    	$sqlquery = "SELECT DISTINCT r.id, r.source, r.target, cm.visible
    				FROM {dasis_relations} r, {course_modules} cm, {dasis_bundle_connections} bc
    				WHERE $target=r.target AND r.source=cm.id AND cm.course=bc.course_id AND bc.bundle_id=".$SESSION->dasis_selectedBundle;
    }else{
    	$sqlquery = "SELECT DISTINCT r.id, r.source, r.target, cm.visible
    				FROM {dasis_relations} r, {course_modules} cm, {dasis_bundle_connections} bc
    				WHERE $target=r.target AND r.source=cm.id AND cm.course=bc.course_id";
    }
    $relations = $DB->get_records_sql($sqlquery);
    foreach($relations as $relation){
    	if(node_visible_for_user($relation->source)) {
    		$sources[] = $relation->source;
    	}
    }
    return $sources;
}

/**
 * Finden der Verknüpfungen eines Knotens im Semantischen Netz, auf welche der Knoten $source zeigt
 *
 * @param int $source		Die id der Instanz, welche auf die anderen, mit ihr vernetzten Lernaktivitäten zeigt
 * @return array $targets	Die ids der vernetzten Lernaktivitäten, die auf source zeigt
 */ 
function getTargetsFromSource($source){
	global $DB, $CFG, $SESSION;
	$targets = array();
    if($SESSION->dasis_selectedBundle > 0){
    	$sqlquery = "SELECT DISTINCT r.id, r.source, r.target, cm.visible
    				FROM {dasis_relations} r, {course_modules} cm, {dasis_bundle_connections} bc
    				WHERE $source=r.source AND r.target=cm.id AND cm.course=bc.course_id AND bc.bundle_id=".$SESSION->dasis_selectedBundle;
    }else{
    	$sqlquery = "SELECT DISTINCT r.id, r.source, r.target, cm.visible
    				FROM {dasis_relations} r, {course_modules} cm, {dasis_bundle_connections} bc
    				WHERE $source=r.source AND r.target=cm.id AND cm.course=bc.course_id";
    }
    $relations = $DB->get_records_sql($sqlquery);
    foreach($relations as $relation){
    	if(node_visible_for_user($relation->target)) {
    		$targets[] = $relation->target;
    	}
    }
    return $targets;
}

/**
 * Bilden eines Knotens im Semantischen Netz
 *
 * @param int $id		Die id der Instanz für die ein Knoten erstellt wird
 * @return array $chunk Der Knoten mit allen relevanten Informationen als assoziatives Array
 */ 
function buildChunk($id) {
    global $CFG, $DB, $SESSION, $USER;
    $chunk = new Object();
    $chunk->id = $id; // course module id
    $module = $DB->get_field("course_modules", "module", array("id" => $id)); // module id
    $instance = $DB->get_field("course_modules", "instance", array("id" => $id)); // instance id module table
    $modname = $DB->get_field("modules", "name", array("id" => $module)); // the name of the module
    
    if(!$SESSION->dasis_webprefs[$SESSION->dasis_blockId]->adaption){
    	//$chunk->color = "green";
    	$chunk->color = "lightgreen";
    }else{
    	$chunk->color = "gray";
    }
    if(!$SESSION->dasis_webprefs[$SESSION->dasis_blockId]->case_collection){
    	$chunk->url = "{$CFG->wwwroot}/mod/{$modname}/view.php?id={$id}&nav=web";
    } else {
    	$chunk->url = "{$CFG->wwwroot}/blocks/case_repository/start.php?id={$id}&nav=web";	
    }
    if($DB->record_exists("ilms_history", array("userid" => $USER->id, "coursemoduleid" => $id))){
    	$chunk->label = $DB->get_field($modname, "name", array("id" => $instance))." ✓";
    	if($shortname = $DB->get_field("dasis_modmeta", "shortname", array("coursemoduleid" => $id))){
    		$chunk->shortname = $shortname." ✓";
    	} else {
    		$chunk->shortname = null;
    	}
    }else{
    	$chunk->label = $DB->get_field($modname, "name", array("id" => $instance)); // the name of the chunk
    	$chunk->shortname = $DB->get_field("dasis_modmeta", "shortname", array("coursemoduleid" => $id)); //short name of the instance
    }
    
    $chunk->shape = "circle";
    switch($modname){ //! Es gibt noch die Formen "square", "triangle", "cross"
    	case "quiz":
    		$chunk->shape = "diamond";
    }
    
    return $chunk;
}

/**
 * Bilden eines Knotens für den Relationenüberblick
 *
 * @param int $id		Die id der Instanz für die ein Knoten erstellt wird
 * @return object $node	Der Knoten mit allen relevanten Informationen als Objekt
 */ 
function buildOverviewNode($id) {
    global $CFG, $DB, $SESSION;
    $node = new Object();
    $node->id = $id; // course module id
    $module = $DB->get_field("course_modules", "module", array("id" => $id)); // module id
    $instance = $DB->get_field("course_modules", "instance", array("id" => $id)); // instance id module table
    $modname = $DB->get_field("modules", "name", array("id" => $module)); // the name of the module
    $node->shortname = $DB->get_field("dasis_modmeta", "shortname", array("coursemoduleid" => $id)); //short name of the instance
    $node->label = $DB->get_field($modname, "name", array("id" => $instance)); // the name of the chunk
    
    return $node;
}

//! Hier könnte ich auch mit den php-Funktionen serialize und unserialize arbeiten, anstatt eigene zu verwenden.
/**
 * Kodieren des Lernpfades von Array zu String
 * 
 * @param array $pathArray		Ein Array, das die Knoten des Lernpfades in der richtigen Reihenfolge enthält
 * @return string $pathString	Formartierter String zu Speichern in der Datenbank
 */
function encode_path_string($pathArray){
	$i=0;
	foreach($pathArray as $node){
		if($i==0){
			$pathString = $i.":".$node;
		}else{
			$pathString .= ",".$i.":".$node;
		}
		$i++;
	}
	return $pathString;
}

/**
 * Dekodieren des Lernpfades von String zu Array
 *
 * @param string $pathString	Formatierter String aus der Datenbank
 * @return array $pathArray		Array mit den Knoten des Lernpfades in der richtigen Reihenfolge
 */
 function decode_path_string($pathString){
 	$pathArray = explode(",", $pathString);
 	for($i=0; $i<count($pathArray); $i++){
 		$tempStr = explode(":", $pathArray[$i]);
 		$pathArray[$i] = $tempStr[1];
 	}
 	return $pathArray;
 }
 
/**
 * Die Farbe einer Relation zwischen Lernaktivitäten durch suchen in Lernpfaden feststellen
 * 
 * @param int $ida			course module id of first learning activity
 * @param int $idb			course module id of second learning activity
 * @return string $color	color string of the relation
 */
 function get_relation_color($ida, $idb){
 	global $DB;
 	$color = "none";
 	$sql = "SELECT * FROM {dasis_learning_paths}
 			WHERE path LIKE '%$ida%$idb%' OR path LIKE '%$idb%$ida%'";
 	$paths = $DB->get_records_sql($sql);
 	foreach($paths as $path){
 		$patharray = unserialize($path->path);
 		for($i=0; $i<count($patharray)-1; $i++){
 			if(($patharray[$i]==$ida && $patharray[$i+1]==$idb) || ($patharray[$i]==$idb && $patharray[$i+1]==$ida)){
 				$color = $path->color;
 			}
 		}
 	}
 	return $color;
 }
 
/**
 * URL zu einem Kursmodul erstellen
 *
 * @param 	int $cmid		course module id
 * @return 	string $url		URL of module
 */
 function get_url_of_coursemodule($cmid){
 	global $CFG;
 	$moduleData = get_coursemodule_from_id(0, $cmid);
 	
 	return $CFG->wwwroot."/mod/".$moduleData->modname."/view.php?id=$cmid";
 }
 
/**
 * Den besten Fall der Lerneradaption aus der Datenbank feststellen
 *
 * @param int 	$id 			id of current course or bundle
 * @param bool	$bundle_flag	set flag to TRUE if $id is an array containing the course ids of all bundles, and FALSE if $id is a course id
 * @return array $solutions		array containing the course modules id and appliance
 */
 function get_iLMS_solutions($bundle_courses) {
 	$solutions = array();
 	$currentcase = current_case($bundle_courses); // build the current case for current bundle
 	$retrieved_solutions = null;
  	if(!is_null($currentcase)) {
  		$retrieved_solutions = retrieve_cases($currentcase)->solutions;
  	    $solutions = revise_cases($retrieved_solutions);
  	}
  	return($solutions);
 }
 
 /**
  * Die Farbe der Knoten wird in Abhänigkeit von der Relevanz des Falls gesetzt
  * 
  * @param array	$solutions
  * @param int		$id
  * @return string	$color
  */
 function set_chunk_color_by_appliance($solutions, $id) {
 	global $ILMS_YELLOW_MARKUP_LIMIT;
 	if(array_key_exists($id, $solutions)){
 		if($solutions[$id]->maximum){
 			$color = "green";
 		} else {
 			if($solutions[$id]->appliance >= $ILMS_YELLOW_MARKUP_LIMIT) {
 				$color = "yellow";
 			} else {
 				$color = "red";
 			}
 		}
 	} else {
 		$color = "gray";
 	}
 	return $color;
 }
 
 /**
  * Die Farbe der Knoten angezeigten wird in Abhänigkeit von der Relevanz des Falls gesetzt
  * 
  * @param array	$solutions
  * @param array	$chunks
  * @param int		$id
  * @return array	$chunksWithColor
  */
 function set_chunks_color_by_appliance($solutions, $chunks, $id) {
 	global $ILMS_YELLOW_MARKUP_LIMIT;
 	
 	$applianceArray = array();
 	foreach($chunks as $chunk) {
 		if(array_key_exists($chunk->id, $solutions) && $chunk->id != $id) {
 			$applianceArray[$chunk->id] = $solutions[$chunk->id]->appliance;
 		} else {
 			$applianceArray[$chunk->id] = null;
 		}
 	}
 	
 	foreach($chunks as $chunk) {
 		if($applianceArray[$chunk->id]) {
 			if($applianceArray[$chunk->id] == max($applianceArray)) {
 				$chunk->color = "green";
 			} else {
 				if($solutions[$chunk->id]->appliance >= $ILMS_YELLOW_MARKUP_LIMIT) {
 					$chunk->color = "yellow";
 				} else {
 					$chunk->color = "red";
 				}
 			}
 		} else {
 			$chunk->color = "gray";
 		}
 	}
 	
 	return $chunks;
 }
 
 /**
  * Abfrage ob der Kurs in einem Bündel enthalten ist
  *
  * @param int $courseid 	Id of course to check for bundles
  * @return bool $result	tells if course is contained by any bundle
  */ 
 function is_course_contained_by_any_bundle($courseid) {
 	global $DB;
 	if($DB->get_record_sql("SELECT COUNT(bundle_id) AS count FROM {dasis_bundle_connections} WHERE course_id = $courseid")->count){
 		$result = 1;
 	}else{
 		$result = 0;
 	}
 	return $result;
 }
 
 /**
  * Abfrage ob das Bündel einen Pfad besitzt
  *
  * @param int $bundleid 	Id of bundle to check for learning paths
  * @return bool $result	tells if course is contained by any bundle
  */ 
 function exists_any_path_for_bundle($bundleid) {
 	global $DB;
 	if($DB->get_record_sql("SELECT COUNT(id) AS count FROM {dasis_learning_paths} WHERE bundle_id = $bundleid")->count){
 		$result = 1;
 	}else{
 		$result = 0;
 	}
 	return $result;
 }
 
 /**
  * @param string $feature FEATURE_xx constant for requested feature
  * @return mixed True if module supports feature, null if doesn't know
  */
 function semantic_web_supports($feature) {
     switch($feature) {
         
         case FEATURE_BACKUP_MOODLE2:          return false;
 
         default: return null;
     }
 }

?>