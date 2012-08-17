<?php
/**
 * This file contains the block semantic_web of the package DASIS.
 * It's used for the following tasks:
 * - show the semantic web navigation popup window
 * - show the form to edit metadata of learning contents via popup window
 * - show the block edit form to set linked courses
 * - set the last activity the user has chosen in course
 *
 * @package	DASIS -> Semantic Web
 * @author	Andre Scherl
 * @version	09.11.2011
 *
 * Copyright (C) 2012, Andre Scherl
 * You should have received a copy of the GNU General Public License
 * along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once("semantic_web_lib.php");
//require_once("../user_preferences/lib.php");

class block_semantic_web extends block_list {	
	function init() {
		$this->title = get_string('blockname', 'block_semantic_web');
		$this->setLastActivity();
	}
  	
	function get_content() {
		global $CFG, $OUTPUT, $SESSION, $PAGE;
		global $USER, $COURSE, $DB;
	
		$BLOCKNAME = "block_semantic_web";
        if($this->content !== NULL) {
            return $this->content;
        }
        $this->content = new stdClass;
        if (empty($this->instance)) {
            $this->content->text = '';
            return $this->content;
        }
		
		// set global variables of DASIS in cookie
		$SESSION->dasis_blockId = $this->instance->id;
		$SESSION->dasis_webprefs[$SESSION->dasis_blockId] = $DB->get_record("dasis_semantic_web_prefs", array("block_id" => $this->instance->id));
		$SESSION->dasis_courseHasBundle = is_course_contained_by_any_bundle($COURSE->id);
		if(!property_exists($SESSION, "dasis_selectedBundle")) {
			$SESSION->dasis_selectedBundle = null;
		}
		if(!is_null($SESSION->dasis_selectedBundle)) {
			$SESSION->dasis_bundleHasPath = exists_any_path_for_bundle($SESSION->dasis_selectedBundle);
		}else{
			$SESSION->dasis_bundleHasPath = null;
		}
		
		// set default session vars, so object's attributes are defined
		if(!property_exists($SESSION, "dasis_selectedPath")) {
			$SESSION->dasis_selectedPath = null;
		}
		
		
		/// Variablen deklarieren
		$id = optional_param('id', 0, PARAM_INT);
		
		// Prüfen, ob wir uns im courseview befinden
		if(strpos($this->page->url, "/course/") === false){
			$SESSION->courseview = 0;
			$SESSION->dasis_activityId = $id;
		}else{
			$SESSION->courseview = 1;
			if(!$SESSION->dasis_activityId = $DB->get_field("dasis_last_activity", "course_module", array("userid" => $USER->id, "courseid" => $COURSE->id))){
		$SESSION->dasis_activityId = $DB->get_field_sql("SELECT id FROM {course_modules} WHERE course = $id ORDER BY added LIMIT 0,1");
			}
		}			
		
		// set flag, if current activity is contained by relations semantic web
		if(!$SESSION->dasis_partOfWeb = $DB->record_exists_select("dasis_relations", "source = {$SESSION->dasis_activityId} OR target = {$SESSION->dasis_activityId}")) {
			$SESSION->dasis_partOfWeb = 0;
		}
		
		//if semantic web pref is not set yet, do it right here
		if(!$SESSION->dasis_webprefs[$SESSION->dasis_blockId]){
			$prefs->block_id = $this->instance->id;
			$prefs->depth = 2;
			$prefs->adaption = 0;
			$prefs->case_collection = 0;
			$prefs->web_animation = 1;
			$DB->insert_record("dasis_semantic_web_prefs", $prefs);
		}
		
		// Wird für das Listen-Layout des Blocks benötigt
		$this->content->items = array();
		$this->content->icons = array();
		
		/**
		 * context needed for role management
		 */
		$context = get_context_instance(CONTEXT_COURSE, $COURSE->id);
		
			
		/**
		 * Build the link list of the block
		 */
		
		// select element to choose a bundle for navigation, if course is contained by any bundle
		//if(is_course_contained_by_any_bundle($COURSE->id)) {
			$bundleSelectElement = "<select id=\"id_bundle_selection\" name=\"dasis_selectedBundle\">";
			$bundleSelectElement .= "<option value=\"0\">".get_string("choose_bundle", $BLOCKNAME)."</option>";
			$bundlesOfCourse = $DB->get_records("dasis_bundle_connections", array("course_id" => $COURSE->id));
			foreach($bundlesOfCourse as $bundleOfCourse){
				$bundle = $DB->get_record("dasis_bundles", array("id" => $bundleOfCourse->bundle_id));
				if($SESSION->dasis_selectedBundle==$bundle->id){
					$bundleSelectElement .= "<option selected=\"selected\" value=\"".$bundle->id."\">".$bundle->name."</option>";
				}else{
					$bundleSelectElement .= "<option value=\"".$bundle->id."\">".$bundle->name."</option>";
				}
			}
			$bundleSelectElement .= "</select>";
			$this->content->items[] = $bundleSelectElement;
		//}
		
		// container for the miniweb
		$this->content->items[] = "<div id=\"id_miniWebContainer\"><iframe id=\"id_miniWeb\" src=\"{$CFG->wwwroot}/blocks/semantic_web/SemanticWeb/miniweb.php?id=$id&bid=".$this->instance->id."\"></iframe><div id=\"id_showWeb\"></div></div>";
		
		// if a bundle is chosen select the path to walk through the web
		if($SESSION->dasis_bundleHasPath or $SESSION->dasis_webprefs[$SESSION->dasis_blockId]->adaption){
			if($SESSION->dasis_selectedPath !== "adapt" && $SESSION->dasis_selectedPath > 0){
				$currentLearningPathArray = unserialize($DB->get_field("dasis_learning_paths", "path", array("id"=>$SESSION->dasis_selectedPath)));
				
				// support groups in learning paths
				foreach($currentLearningPathArray as $node) {
					if(!node_visible_for_user($node)) {
						unset($currentLearningPathArray[array_search($node, $currentLearningPathArray)]);
						$currentLearningPathArray = array_values($currentLearningPathArray);
					}
				}
				
				$currentIndex=array_search($id, $currentLearningPathArray);
				if($currentIndex==0){
					$prevIndex=0;
					$nextIndex=1;
				}elseif($currentIndex==count($currentLearningPathArray)-1){
					$prevIndex=$currentIndex-1;
					$nextIndex=$currentIndex;
				}else{
					$prevIndex=$currentIndex-1;
					$nextIndex=$currentIndex+1;
				}
				//$prevNodeLink=get_url_of_coursemodule($currentLearningPathArray[$prevIndex]);
				//$nextNodeLink=get_url_of_coursemodule($currentLearningPathArray[$nextIndex]);
				$prevNodeLink = "{$CFG->wwwroot}/blocks/case_repository/start.php?id={$currentLearningPathArray[$prevIndex]}&backward=true&nav=path";
				$nextNodeLink = "{$CFG->wwwroot}/blocks/case_repository/start.php?id={$currentLearningPathArray[$nextIndex]}&foreward=true&nav=path";
			}else{
				$prevNodeLink="";
				$nextNodeLink="";
			}
			
			$pathSelection = "<div id=\"id_path_control\" style=\"text-align:center;min-width:180px;\">";
			
			$pathSelection .= "<select id=\"id_path_select\" name=\"dasis_selectedPath\">";
			$pathSelection .= "<option value=\"0\">".get_string("select_path", $BLOCKNAME)."</option>";
			$paths = $DB->get_records("dasis_learning_paths", array("bundle_id" => $SESSION->dasis_selectedBundle));
			
			// add option for adaptive path to select element if adaptation is switched on
			if($SESSION->dasis_webprefs[$SESSION->dasis_blockId]->adaption) {
				$adaptPathOption->id = "adapt";
				$adaptPathOption->name = "Adaptiver Pfad";
				$adaptPathOption->bundle_id = $SESSION->dasis_selectedBundle;
				$paths[$adaptPathOption->id] = $adaptPathOption;
			}
			
			foreach($paths as $path){
				if($SESSION->dasis_selectedPath === $path->id){
					$pathSelection .= "<option selected=\"selected\" value=\"".$path->id."\">".$path->name."</option>";
				}else{
					$pathSelection .= "<option value=\"".$path->id."\">".$path->name."</option>";
				}
			}
			$pathSelection .= "</select><br />";
			
			// if adapt path is chosen, get last visited node, else the previous node of selected path
			if($SESSION->dasis_selectedPath === "adapt" && $SESSION->dasis_webprefs[$SESSION->dasis_blockId]->adaption) {
				if(!isset($SESSION->dasis_historyPosition)) $SESSION->dasis_historyPosition = 0;
				$sql = "SELECT coursemoduleid FROM {ilms_history} WHERE ";
		    	
		    	if($SESSION->dasis_selectedBundle > 0){
			 		$coursesSql = "SELECT DISTINCT course_id FROM {dasis_bundle_connections} WHERE bundle_id =".$SESSION->dasis_selectedBundle;
			 	}else{
			 		$coursesSql = "SELECT DISTINCT course_id FROM {dasis_bundle_connections}";
			 	}
			 	
		    	if(!$bundleCourses = $DB->get_records_sql($coursesSql)){
					$bundleCourse->course_id = $COURSE->id;
				    $bundleCourses = array("{$COURSE->id}" => $bundleCourse);
				}
				
				$sql .= " ( ";
				foreach($bundleCourses as $bundleCourse){
				    if($bundleCourse != end($bundleCourses)) {
				    	$sql .= " courseid = {$bundleCourse->course_id} OR";
				    } else {
				    	$sql .= " courseid = {$bundleCourse->course_id}";
				    }
				}
				$sql .= " ) ";
				$sql .= "AND userid = {$USER->id} ORDER BY timemodified DESC LIMIT ".($SESSION->dasis_historyPosition+1).",1";
				$prevNodeLink = "{$CFG->wwwroot}/blocks/case_repository/start.php?id=".$DB->get_field_sql($sql)."&backward=true&nav=path";
			}
			$pathSelection .= "<button id=\"id_button_lastNode\" name=\"lastNode\" value=\"$prevNodeLink\">".get_string("back", $BLOCKNAME)."</button>";
			$pathSelection .= "        <button id=\"id_button_nextNode\" name=\"nextNode\" value=\"$nextNodeLink\">".get_string("next", $BLOCKNAME)."</button>";
			$pathSelection .= "</div>";
			$this->content->items[] = $pathSelection;
		}
		
		// user preferences of numbers of steps in semantic web
		$depthChoice = "<p><br/>".get_string("depth", $BLOCKNAME).": <select id=\"id_select_depth\" name=\"userDepth\">";
		if(!property_exists($SESSION, "userDepth")){
			$SESSION->userDepth = $SESSION->dasis_webprefs[$SESSION->dasis_blockId]->depth;
		}
		for($i=1; $i<=5; $i++) {
			if($i==$SESSION->userDepth) {
				$selected = "selected=\"selected\"";
			} else {
				$selected = "";
			}
			$depthChoice .= "<option $selected value=\"$i\">$i</option>";
		}
		$depthChoice .= "</select></p>";
		$this->content->items[] = $depthChoice;
		
		// open the edit metadata popup div
		if(has_capability("block/semantic_web:editmetadata", $context)) {
			$linkEditMetaData = "blocks/semantic_web/Metadata/edit_metadata.php?id=$id&cv={$SESSION->courseview}";
			$this->content->items[] = "<input id=\"id_linkEditMetaData\" type=\"hidden\" value=\"".$CFG->wwwroot."/".$linkEditMetaData."\"/>";
			$this->content->items[] = "<a id=\"id_editMetaData\" href=\"#\">".get_string("edit_metadata", "block_semantic_web")."</a>";
			$this->content->items[] = "<a id=\"id_show_semantic_overview\" href=\"$CFG->wwwroot/blocks/semantic_web/SemanticWeb/semantic_overview.php\">".get_string("show_semantic_overview", "block_semantic_web")."</a>";
		}
		
		// about button
		$this->content->footer = $OUTPUT->help_icon("about", $BLOCKNAME);
		
		// load javascript
		$jsmodule = array(
     	'name' => 'block_semantic_web',
     	'fullpath' => '/blocks/semantic_web/semantic_web.js',
     	'requires' => array('node', 'event', 'dd-drag'));
		$PAGE->requires->js_init_call('M.block_semantic_web.init_popup_actions', null, false, $jsmodule);
		//$PAGE->requires->js_init_call('M.block_semantic_web.init_adaption_actions', null, false, $jsmodule);
		
		// load js of adaption module "case repository" if adaptation is turned on
		if($SESSION->dasis_webprefs[$SESSION->dasis_blockId]->adaption){
			$js_case_repository = array(
     			'name' => 'block_cae_repository',
     			'fullpath' => '/blocks/case_repository/case_repository.js',
     			'requires' => array('array-extras'));
        	$PAGE->requires->js_init_call('M.block_case_repository.init', null, false, $js_case_repository);
		}
		
		return $this->content;
	}

	function instance_allow_config() {
	  return true;
	}
	
	function applicable_formats() {
        return array('course' => true);   // Not needed on site
    }
    
    /**
     * set the current activity visited by user to the database table dasis_last_activity
     */ 
    function setLastActivity() {
    	global $USER, $PAGE, $DB;
    	if($PAGE->cm) {
    		if($DB->record_exists_select("dasis_relations", "source = ".$PAGE->cm->id." OR target = ".$PAGE->cm->id)) {
    			$lastActivity = new object();
    			$lastActivity->userid = $USER->id;
    			$lastActivity->courseid = $PAGE->cm->course;
    			$lastActivity->course_module = $PAGE->cm->id;
    			
    			if($rec = $DB->get_record("dasis_last_activity", array("userid" => $lastActivity->userid, "courseid" => $lastActivity->courseid))) {
    				$lastActivity->id = $rec->id;
    				$lastActivity->last_access = date("Y-m-d H:i:s", time());
    				$DB->update_record("dasis_last_activity", $lastActivity);
    			}else{
    				$DB->insert_record("dasis_last_activity", $lastActivity);
    			}
    		}
    	}
    }
    
    /**
     * clean up things of dead learning activities
     */
    function cron() {
    	global $DB;
    	mtrace("\n----------------------- cron job - ".get_string('blockname', 'block_semantic_web')." -------------------------\n");
    	
    	// check relations
    	$relations = $DB->get_records("dasis_relations");
    	foreach($relations as $relation) {
    		if(!$DB->record_exists("course_modules", array("id" => $relation->target)) || !$DB->record_exists("course_modules", array("id" => $relation->source))) {
    			mtrace("removed activity {$relation->target} or {$relation->source} from course modules, so remove relation '".get_string($relation->type, 'block_case_repository')."' of activities too.");
    			if($DB->delete_records("dasis_relations", array("id" => $relation->id))) mtrace("removed.");
    		}
    	}
    	
    	// check metadata
    	$metadata = $DB->get_records("dasis_modmeta");
    	foreach($metadata as $data) {
    		if(!$DB->record_exists("course_modules", array("id" => $data->coursemoduleid))) {
    			mtrace("remove metadata of removed activity ".$data->coursemoduleid);
    			if($DB->delete_records("dasis_modmeta", array("id" => $data->id))) mtrace("removed.");
    		}
    	}
    	
    	// check learning paths
    	$learningPaths = $DB->get_records("dasis_learning_paths");
    	foreach($learningPaths as $learningPath) {
    		$pathArray = unserialize($learningPath->path);
    		foreach($pathArray as $pathItem) {
    			if(!$DB->record_exists("course_modules", array("id" => $pathItem))) {
    				mtrace("remove activity $pathItem from path {$learningPath->name}.");
    				unset($pathArray[array_search($pathItem, $pathArray)]);
    				mtrace("-> removed.");
    			}
    		}
    		$learningPathObject->id = $learningPath->id;
    		$learningPathObject->path = serialize($pathArray);
    		$DB->update_record("dasis_learning_paths", $learningPathObject);
    	}
    	
    	// check web prefs
    	$webprefs = $DB->get_records("dasis_semantic_web_prefs");
    	foreach($webprefs as $webpref) {
    		if(!$DB->record_exists("block_instances", array("id" => $webpref->block_id))) {
    			mtrace("remove webprefs of removed block ".$webpref->block_id);
    			if($DB->delete_records("dasis_semantic_web_prefs", array("id" => $webpref->id))) mtrace("removed.");
    		}
    	}
    	
    	mtrace("--------------- end of cron job of block ".get_string('blockname', 'block_semantic_web')." -------------------\n");
    }
}