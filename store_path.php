<?php 
	/**
	 * store a lerning path
	 * 
	 * @package	DASIS -> Semantic Web
	 * @author	Andre Scherl
	 * @version	1.2 - 06.04.2011
	 *
	 * Copyright (C) 2012, Andre Scherl
	 * You should have received a copy of the GNU General Public License
	 * along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
	 */

	require_once("../../config.php");
	require_once("semantic_web_lib.php");
	
	$path->id = required_param("pathId", PARAM_NUMBER);
	$path->name = optional_param("path_name", "noname", PARAM_TEXT);
	$path->bundle_id = optional_param("lpbid", -1, PARAM_NUMBER);
	$path->color = optional_param("pathColor", "nocolor", PARAM_TEXT);
	$newPathNode = optional_param("new_path_node", -1, PARAM_NUMBER);
	
	// if learning path doesn't exists, store it
	if(!$DB->record_exists("dasis_learning_paths", array("id" => $path->id))) {
		$path->path = serialize(array());
		$path->id = $DB->insert_record("dasis_learning_paths", $path);
	}else{
		$path->name = $DB->get_field("dasis_learning_paths", "name", array("id" => $path->id));
		$path->color = $DB->get_field("dasis_learning_paths", "color", array("id" => $path->id));
		
		if($pathString = $DB->get_field("dasis_learning_paths", "path", array("id" => $path->id))){
			$pathArray = unserialize($pathString);
			$pathArray[] = $newPathNode;
			$path->path = serialize($pathArray);
		}else{
			$pathArray[0] = $newPathNode;
			$path->path = serialize($pathArray);
		}
		$DB->update_record("dasis_learning_paths", $path);
	}
	
	$array = explode("&pathId", $_POST["currenturl"]);
	redirect($array[0]."&pathId=".$path->id);
?>