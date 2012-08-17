<?php 
	/**
	 * store the prefs of semantic web in table dasis_semantic_web_prefs
	 * 
	 * @package	DASIS -> Semantic Web
	 * @author	Andre Scherl
	 * @version	1.1 - 21.06.2011
	 *
 	 * Copyright (C) 2012, Andre Scherl
	 * You should have received a copy of the GNU General Public License
	 * along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
	 */

	error_reporting(E_ALL);
	
	require_once("../../config.php");
	
	$prefsItem->block_id = required_param("bui_editid", PARAM_INT);
	$prefsItem->depth = $_POST["depth"];
	if(!isset($_POST["adaption_check"]) || $_POST["adaption_check"] == 0){
		$prefsItem->adaption = 0;
	}else{
		$prefsItem->adaption = 1;
	}
	if(!isset($_POST["case_collection_check"]) || $_POST["case_collection_check"] == 0){
		$prefsItem->case_collection = 0;
	}else{
		$prefsItem->case_collection = 1;
	}
	if(!isset($_POST["web_animation_check"]) || $_POST["web_animation_check"] == 0){
		$prefsItem->web_animation = 0;
	}else{
		$prefsItem->web_animation = 1;
	}	
	
	// if connection doesn't exists, store it
	if(!$DB->record_exists("dasis_semantic_web_prefs", array("block_id" => $prefsItem->block_id))) {
		$DB->insert_record("dasis_semantic_web_prefs", $prefsItem);
	}else{
		$prefsItem->id = $DB->get_field("dasis_semantic_web_prefs", "id", array("block_id" => $prefsItem->block_id));
		$DB->update_record("dasis_semantic_web_prefs", $prefsItem);
	}
	
	redirect($_POST["currenturl"]);
?>