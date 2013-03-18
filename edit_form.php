<?php

/**
 * Form for editing Semantic Web block instances.
 *
 * @package	DASIS -> Semantic Web
 * @author	Andre Scherl
 * @version	1.2 - 07.06.2011
 *
 * Copyright (C) 2012, Andre Scherl
 * You should have received a copy of the GNU General Public License
 * along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once("semantic_web_lib.php");

class block_semantic_web_edit_form extends block_edit_form {
    protected function specific_definition($mform) {
        global $DB, $CFG, $PAGE;
		$BLOCKNAME = "block_semantic_web";
		$context = get_context_instance(CONTEXT_COURSE, $this->page->course->id);
		//$context = get_context_instance(CONTEXT_MODULE, $PAGE->course->id);
		$jsmodule = array(
     	'name' => 'block_semantic_web',
     	'fullpath' => '/blocks/semantic_web/semantic_web.js',
     	'requires' => array('node', 'event'));
		$PAGE->requires->js_init_call('M.block_semantic_web.init_configblock_actions', null, false, $jsmodule);

				
		// set the wwwroot in a hidden field to get it's value via javascript
		//$mform->addElement("hidden", "wwwroot", $CFG->wwwroot);
		
		//set the current url for redirect after database actions
		$mform->addElement("hidden", "currenturl", "");
		
		// set the bundle connection id
		$mform->addElement("hidden", "bcid", "");
		
		// set the courseida to current course
		$mform->addElement("hidden", "courseid", $this->page->course->id);
		
		// set header of specific configuration		
        $mform->addElement('header', 'config_semantic_web', get_string("semanticweb_settings", $BLOCKNAME));
                
        // set a select element to add a new course
        $allCourses = get_courses($categoryid="all", $sort="c.fullname ASC", $fields="c.id, c.fullname");
        
        // build a table for semantic web prefs
        $webPrefs = $DB->get_record("dasis_semantic_web_prefs", array("block_id" => required_param("bui_editid", PARAM_NUMBER)));
        $mform->addElement("html", "<table><tr><td>");
        // set the depth of semantic web (how many connections should be shown?)
        $mform->addElement("html", get_string('depth', $BLOCKNAME));
        $mform->addElement("html", "<select name=\"depth\" id=\"id_depth\">");
        for($i=1; $i<=5; $i++){
        	if($i == $webPrefs->depth){
        		$mform->addElement("html", "<option value=\"$i\" selected=\"selected\">$i</option>");
        	}else{
        		$mform->addElement("html", "<option value=\"$i\">$i</option>");
        	}
        }
        $mform->addElement("html", "</select>");
        $mform->addElement("html", "</td><td>");
        
     	// switch adaption on/off
     	$mform->addElement("html", get_string("pluginname", "block_case_repository"));
     	if(!$webPrefs->adaption){
     		$adaptionStatus="";
     	}else{
     		$adaptionStatus="checked";
     	}
     	$mform->addElement("html", " <input name=\"adaption_check\" value=\"1\"  type=\"checkbox\" id=\"id_adaption_checkbox\" $adaptionStatus/>");
     	$mform->addElement("html", "</td><td>");
     	
     	// switch case collection on/off
     	$mform->addElement("html", get_string("case_collection", "block_semantic_web"));
     	if(!$webPrefs->case_collection){
     		$collectStatus="";
     	}else{
     		$collectStatus="checked";
     	}
     	$mform->addElement("html", " <input name=\"case_collection_check\" value=\"1\"  type=\"checkbox\" id=\"id_case_collection_checkbox\" $collectStatus/>");
     	$mform->addElement("html", "</td><td>");
     	
     	// switch web animation on/off
     	$mform->addElement("html", get_string("web_animation", "block_semantic_web"));
     	if(!$webPrefs->web_animation){
     		$collectStatus="";
     	}else{
     		$collectStatus="checked";
     	}
     	$mform->addElement("html", " <input name=\"web_animation_check\" value=\"1\"  type=\"checkbox\" id=\"id_web_animation_checkbox\" $collectStatus/>");
     	$mform->addElement("html", "</td></tr></table>");
        
        /**
         * bundle configuration
         */		
        $mform->addElement('header', 'config_semantic_web', get_string("bundle_settings", $BLOCKNAME)); //header
        
        $mform->addElement("hidden", "enlarge_string", get_string("click_enlarge", $BLOCKNAME));
        $mform->addElement("hidden", "hide_string", get_string("click_hide", $BLOCKNAME));
        
        //get all bundles
        $allBundles = $DB->get_records("dasis_bundles");
        
        //get current bundle connections
        $bundleConnections = $DB->get_records("dasis_bundle_connections", array("course_id" => $this->page->course->id));
        
        //put an array of connections to bundleConnections
        foreach($allBundles as $oneBundle){
        	$cob = $DB->get_records("dasis_bundle_connections", array("bundle_id" => $oneBundle->id)); // connections of bundle
        	foreach($cob as $oneCob){
        		$allBundles[$oneBundle->id]->connections[] = $allCourses[$oneCob->course_id]->fullname;
        	}
        }
        
        $mform->addElement("html", "<table>"); //beginn table
        	
        	// list of bundles containing the course
        	$mform->addElement("html", "<tr>");	//begin row
        		$mform->addElement("html", "<td style=\"vertical-align:top;\">"); //begin cell
        			$mform->addElement("html", get_string("bundles_containing_the_course", $BLOCKNAME));
        		$mform->addElement("html", "</td>"); //close cell
        		$mform->addElement("html", "<td>"); //beginn cell
        			$mform->addElement("html", "<ul class=\"connectionlist\">"); //beginn unordered list
        				//list of all bundles containing the course
        				foreach($bundleConnections as $connection){
        					$mform->addElement("html", "<li style=\"cursor:s-resize\" id=\"".$connection->id."\" title=\"".get_string("click_enlarge", $BLOCKNAME)."\"><B>".$allBundles[$connection->bundle_id]->name."</B> ");
        					$mform->addElement("html", "<a href=# name=\"".$connection->id."\">[".get_string("remove", $BLOCKNAME)."]</a>");
        					$mform->addElement("html", "<div id=\"id_description_".$connection->id."\" style=\"display:none;\"><I>".get_string("description", $BLOCKNAME)."</I><br />".$allBundles[$connection->bundle_id]->description."</div>");
        					$mform->addElement("html", "<div id=\"id_contained_courses_".$connection->id."\" style=\"display:none;\"><I>".get_string("contained_courses", $BLOCKNAME)."</I><ul class=\"bundleconnections\">");
        					foreach($allBundles[$connection->bundle_id]->connections as $courseconnection){
        						$mform->addElement("html", "<li>".$courseconnection."</li>");
        					}
        					$mform->addElement("html", "</ul></div>");
        				}
       	 			$mform->addElement("html", "</ul>"); //close unordered list
        		$mform->addElement("html", "</td>"); //close cell
        	$mform->addElement("html", "</tr>"); //close row
        	
        	// select element to add course to bundle
        	$mform->addElement("html", "<tr>");	//begin row
        		$mform->addElement("html", "<td>"); //begin cell
        			$mform->addElement("html", get_string("add_course_to_bundle", $BLOCKNAME));
        		$mform->addElement("html", "</td>"); //close cell
        		$mform->addElement("html", "<td>"); //beginn cell
        			$mform->addElement("html", "<select id=\"id_addToBundle\" name=\"addToBundle\">"); //beginn select element
        				$mform->addElement("html", "<option value=\"0\" selected=\"selected\">".get_string("pleaseselect", $BLOCKNAME)."</option>"); //set default option
        				//list of all bundles
        				foreach($allBundles as $bundle){
        					if(!$DB->record_exists("dasis_bundle_connections", array("bundle_id" => $bundle->id, "course_id" => $this->page->course->id))){
        						$mform->addElement("html", "<option value=\"{$bundle->id}\">{$bundle->name}</option>"); //set bundles as options
        					}
        				}
        				$mform->addElement("html", "<option value=\"-1\">".get_string("newbundle", $BLOCKNAME)."</option>");
       	 			$mform->addElement("html", "</select>"); //close select element
        		$mform->addElement("html", "</td>"); //close cell
        	$mform->addElement("html", "</tr>"); //close row
        	
        $mform->addElement("html", "</table>"); //close table
        
        // create new bundle
        $bundleId = optional_param("bundleId", 0, PARAM_INT);
        $mform->addElement("hidden", "bundleId", $bundleId);
        if($bundleId) $currentBundle = $DB->get_record("dasis_bundles", array("id" => $bundleId));
        $mform->addElement("html", "<table id=\"id_new_bundle_table\">");
        	$mform->addElement("html", "<tr>");
        		$mform->addElement("html", "<td>");
        			$mform->addElement("html", get_string("name_of_bundle", $BLOCKNAME));
        		$mform->addElement("html", "</td>");
        		$mform->addElement("html", "<td>");
        			if(!$bundleId){
        				$mform->addElement("html", "<input type=\"text\" name=\"name_of_bundle\" id=\"id_name_of_bundle\" placeholder=\"".get_string("name_of_bundle", $BLOCKNAME)."\" size=\"50\"/>");
        			}else{
        				$mform->addElement("html", "<input type=\"text\" name=\"name_of_bundle\" id=\"id_name_of_bundle\" size=\"50\" value=\"".$currentBundle->name."\" placeholder=\"".get_string("name_of_bundle", $BLOCKNAME)."\"/>");
        			}
        		$mform->addElement("html", "</td>");
        	$mform->addElement("html", "</tr>");
        	$mform->addElement("html", "<tr>");
        		$mform->addElement("html", "<td style=\"vertical-align:top;\">");
        			$mform->addElement("html", get_string("description", $BLOCKNAME));
        		$mform->addElement("html", "</td>");
        		$mform->addElement("html", "<td>");
        			if(!$bundleId){
        				$mform->addElement("html", "<textarea name=\"description_of_bundle\" id=\"id_description_of_bundle\" placeholder=\"".get_string("description", $BLOCKNAME)."\" rows=\"5\" cols=\"50\">".$BLOCKNAME."</textarea>");
        			}else{
        				$mform->addElement("html", "<textarea name=\"description_of_bundle\" id=\"id_description_of_bundle\" rows=\"5\" cols=\"50\" placeholder=\"".get_string("description", $BLOCKNAME)."\">".$currentBundle->description."</textarea>");
        			}
        		$mform->addElement("html", "</td>");
        	$mform->addElement("html", "</tr>");
        	$mform->addElement("html", "<tr><td>");
        		$mform->addElement("html", "<input type=\"button\" id=\"id_bundle_submit\" value=\"".get_string("create_bundle", $BLOCKNAME)."\"/>");
        	$mform->addElement("html", "</td></tr>");
        	$mform->addElement("html", "<tr>");
        		$mform->addElement("html", "<td style=\"vertical-align:top;\">");
        			$mform->addElement("html", get_string("contained_courses", $BLOCKNAME));
        		$mform->addElement("html", "</td>");
        		$mform->addElement("html", "<td>");
        			$mform->addElement("html", "<ul class=\"connectionlist\">");
        				//list of contained courses
        				$connectionsOfBundle = $DB->get_records("dasis_bundle_connections", array("bundle_id" => $bundleId));
        				foreach($connectionsOfBundle as $connection){
        					$mform->addElement("html", "<li>".$allCourses[$connection->course_id]->fullname." ");
        					$mform->addElement('html', "<a href=# name=\"".$connection->id."\">[".get_string("remove", $BLOCKNAME)."]</a></li>");
        				}
       	 			$mform->addElement("html", "</ul>");
        		$mform->addElement("html", "</td>");
        	$mform->addElement("html", "</tr>");
        	$mform->addElement("html", "<tr>");
        		$mform->addElement("html", "<td>");
        			$mform->addElement("html", get_string("add_course_to_this_bundle", $BLOCKNAME));
        		$mform->addElement("html", "</td>");
        		$mform->addElement("html", "<td>");
        			$mform->addElement("html", "<select id=\"id_addCourse\" name=\"addCourse\">");
        				$mform->addElement("html", "<option value=\"0\" selected=\"selected\">".get_string("pleaseselect", $BLOCKNAME)."</option>");        										foreach($allCourses as $course){
        					if($course->id != 1 && $course->id != $this->page->course->id && !$DB->record_exists("dasis_bundle_connections", array("bundle_id" => $bundle->id, "course_id" => $course->id))){
        						$mform->addElement("html", "<option value=\"{$course->id}\">{$course->fullname}</option>");
        					}
        				}
       	 			$mform->addElement("html", "</select>");
        		$mform->addElement("html", "</td>");
        	$mform->addElement("html", "</tr>");
        $mform->addElement("html", "</table>");
        
        /**
         * bundle overview and delete
         */
         $mform->addElement("html", "<br /><B id=\"id_toggle_bundle_management\" style=\"cursor:s-resize\" title=\"".get_string("click_enlarge", $BLOCKNAME)."\">".get_string("bundle_overview", $BLOCKNAME)."</B>");
         $mform->addElement("html", "<table id=\"id_bundle_management\" style=\"display:none;\">");
         $mform->addElement("html", "<tr><th>".get_string("bundle", $BLOCKNAME)."</th><th>".get_string("description", $BLOCKNAME)."</th><th>".get_string("contained_courses", $BLOCKNAME)."</th>");
         if(has_capability("block/semantic_web:deletebundle", $context)) { // only people with permissions can remove bundles from database
         	$mform->addElement("html", "<th></th>");
         }
         $mform->addElement("html", "</tr>");
         foreach($allBundles as $oneBundle){
         	$mform->addElement("html", "<tr valign=\"top\"><td>".$oneBundle->name."</td><td>".$oneBundle->description."</td><td>");
         	$mform->addElement("html", "<ul>");
         	foreach($oneBundle->connections as $courseconnection){
        		$mform->addElement("html", "<li>".$courseconnection."</li>");
        	}
        	$mform->addElement("html", "</ul>");
			$mform->addElement("html", "</td>");
			if(has_capability("block/semantic_web:deletebundle", $context)) {
				$mform->addElement("html", "<td><a href=# name=\"".$oneBundle->id."\">[".get_string("remove", $BLOCKNAME)."]</a></td>");
			}
			$mform->addElement("html", "</tr>");
         }
         $mform->addElement("html", "</table>");
         
        
        /**
         * learning path configuration
         */		
        $mform->addElement('header', 'config_semantic_web', get_string("learning_path_settings", $BLOCKNAME));
        
        // needed parameters for this section
        $lpbid = optional_param("lpbid", 0, PARAM_INT);
        $mform->addElement("hidden", "lpbid", $lpbid);
        $pathId = optional_param("pathId", 0, PARAM_INT);
        $mform->addElement("hidden", "pathId", $pathId);
        
        // Ein Array mit den Kurs-Modulen als Objekte erstellen
		$modules = get_course_mods($this->page->course->id);
        
        // Zu diesem Array werden noch weitere Kurs-Module aus anderen Kursen im Bündel hinzugefügt
		$bundleconnections = $DB->get_records("dasis_bundle_connections", array("course_id" => $this->page->course->id));
		foreach($bundleconnections as $bundleconnection) {
			$bccourses = $DB->get_records("dasis_bundle_connections", array("bundle_id" => $bundleconnection->bundle_id));
    		foreach($bccourses as $bccourse){
    			$newmods = get_course_mods($bccourse->course_id);
    			$modules = array_merge($modules, $newmods);
    		}
		}
		// Ein Array erstellen mit den Informationen: course module id, name
		$mods = array();
		foreach($modules as $module) {
		    $mods[$module->id] = get_coursemodule_from_id($module->modname, $module->id, $module->course);
		    $mods[$module->id]->coursename = $DB->get_field("course", "fullname", array("id" => $module->course));
		}
        
        $mform->addElement("html", "<table>");
        // select a bundle to view or edit paths for
        $mform->addElement("html", "<tr>");
        	$mform->addElement("html", "<td>".get_string("bundle", $BLOCKNAME)."</td>");
        	$mform->addElement("html", "<td><select id=\"id_learning_path_bundle_select\">");
        		$mform->addElement("html", "<option value=\"0\">".get_string("pleaseselect", $BLOCKNAME)."</option>");
        		foreach($allBundles as $oneBundle){
        			if($DB->record_exists("dasis_bundle_connections", array("bundle_id" => $oneBundle->id, "course_id" => $this->page->course->id))){
        				if($lpbid == $oneBundle->id){
        					$mform->addElement("html", "<option selected=\"selected\" value=\"".$oneBundle->id."\">".$oneBundle->name."</option>");
        				}else{
        					$mform->addElement("html", "<option value=\"".$oneBundle->id."\">".$oneBundle->name."</option>");
        				}
        				
        			}
        		}
        	$mform->addElement("html", "</select></td>");
        $mform->addElement("html", "</tr>");
        
        // select a learning path or create a new one
        $mform->addElement("html", "<tr>");
        	$mform->addElement("html", "<td>".get_string("learning_pathname", $BLOCKNAME)."</td>");
        	$mform->addElement("html", "<td><div id=\"id_div_learning_path_select\"><select name=\"learning_path_select\" id=\"id_learning_path_select\">");
        		$mform->addElement("html", "<option value=\"0\">".get_string("pleaseselect", $BLOCKNAME)."</option>");
        		foreach($DB->get_records("dasis_learning_paths", array("bundle_id" => $lpbid)) as $path){
        			if($pathId == $path->id){
        				$mform->addElement("html", "<option selected=\"selected\" value=\"".$path->id."\">".$path->name."</option>");
        			}else{
        				$mform->addElement("html", "<option value=\"".$path->id."\">".$path->name."</option>");
        			}
        		}
        		if(has_capability("block/semantic_web:managepaths", $context)){
        			$mform->addElement("html", "<option value=\"-1\">".get_string("create_new_path", $BLOCKNAME)."</option>");
        		}
        	$mform->addElement("html", "</select>");
        	if(has_capability("block/semantic_web:managepaths", $context)){
        		$mform->addElement("html", "<a id=\"id_delete_path\" href=\"#\"> [".get_string("remove", $BLOCKNAME)."]</a>");
        	}
        	$mform->addElement("html", "</div>");
        	$newPathDiv = "<div id=\"id_new_path\" style=\"display:none;\"><input type=\"text\" name=\"path_name\" id=\"id_path_name\" placeholder=\"".get_string("path_name", $BLOCKNAME)."\"/>";
        	$colorPicker = "<select id=\"id_color_picker\" name=\"pathColor\">";
        	$colorPicker .= "<option value=\"none\">".get_string("color", $BLOCKNAME)."</option>";
        	$colorPicker .= "<option value=\"red\" style=\"background-color:red;\">".get_string("red", $BLOCKNAME)."</option>";
        	$colorPicker .= "<option value=\"orange\" style=\"background-color:orange;\">".get_string("orange", $BLOCKNAME)."</option>";
        	$colorPicker .= "<option value=\"yellow\" style=\"background-color:yellow;\">".get_string("yellow", $BLOCKNAME)."</option>";
        	$colorPicker .= "<option value=\"green\" style=\"background-color:green;\">".get_string("green", $BLOCKNAME)."</option>";
        	$colorPicker .= "<option value=\"blue\" style=\"background-color:blue;\">".get_string("blue", $BLOCKNAME)."</option>";
        	$colorPicker .= "<option value=\"purple\" style=\"background-color:purple;\">".get_string("purple", $BLOCKNAME)."</option>";
        	$colorPicker .= "</select>";
        	$newPathDiv .= $colorPicker;
        	$newPathDiv .= "<input type=\"button\" id=\"id_button_new_path\" name=\"button_new_path\" value=\"".get_string("create_new_path", $BLOCKNAME)."\"/></div>";
        	$mform->addElement("html", $newPathDiv);
        $mform->addElement("html", "</tr>");
        
        $mform->addElement("html", "<tr style=\"vertical-align:top;\">");
        	$mform->addElement("html", "<td>".get_string("learning_path", $BLOCKNAME)."</td>");	
        	$mform->addElement("html", "<td>");
        		$pathArray = unserialize($DB->get_field("dasis_learning_paths", "path", array("id" => $pathId)));
        		if(count($pathArray) >1 && is_array($pathArray)){
        			$mform->addElement("html", "<ol id=\"id_path_node_list\">");
        				while($pathNode = current($pathArray)){
        					$mform->addElement("html", "<li>".$mods[$pathNode]->coursename.": ".$mods[$pathNode]->name." ");
        					if(has_capability("block/semantic_web:managepaths", $context)){
        						$mform->addElement('html', "<a href=# name=\"".key($pathArray)."\">[".get_string("remove", $BLOCKNAME)."]</a>");
        					}
        					$mform->addElement("html", "</li>");
        					next($pathArray);
        				}
        			$mform->addElement("html", "</ol>");
        		}
        		if(has_capability("block/semantic_web:managepaths", $context)){
        			$mform->addElement("html", "<select id=\"id_select_add_pathnode\" name=\"new_path_node\" style=\"width:100%\">");
        				$mform->addElement("html", "<option value=-1>".get_string("new_pathnode", $BLOCKNAME)."</option>");
        				foreach($mods as $mod){
        					$mform->addElement("html", "<option value=\"".$mod->id."\">".$mod->coursename.": ".$mod->name."</option>");
        				}
        			$mform->addElement("html", "</select>");
        		}
        	$mform->addElement("html", "</td>");
        $mform->addElement("html", "</tr>");
        
        $mform->addElement("html", "</table>");
    }
}