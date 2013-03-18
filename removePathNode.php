<?php 
	/**
	 * remove a node from lerning path
	 * 
	 * @package	DASIS -> Semantic Web
	 * @author	Andre Scherl
	 * @version	1.0 - 03.12.2010
	 *
	 * Copyright (C) 2012, Andre Scherl
	 * You should have received a copy of the GNU General Public License
	 * along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
	 */

	require_once("../../config.php");
	require_once("semantic_web_lib.php");
	
	//$pathId = optional_param("pathId", 0, PARAM_INT);
	//$nodeToDel = optional_param("ntd");
	
	$pathId = $_POST["pathId"];
	$nodeToDel = $_POST["ntd"];
	
	if($pathId > 0){
		$path = $DB->get_record("dasis_learning_paths", array("id" => $pathId));
		$pathArray = unserialize($path->path);
		unset($pathArray[$nodeToDel]);
		$path->path = serialize($pathArray);
		
		$DB->update_record("dasis_learning_paths", $path);
	}
	
?>