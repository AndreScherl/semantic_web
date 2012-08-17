<?php 
	/**
	 * store the bundle and its description in table dasis_bundles
	 * 
	 * @package	DASIS -> Semantic Web
	 * @author	Andre Scherl
	 * @version	1.0 - 15.11.2010
	 *
	 * Copyright (C) 2012, Andre Scherl
	 * You should have received a copy of the GNU General Public License
	 * along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
	 */

	require_once("../../config.php");
	
	$bundle->id = optional_param("bundleId");
	$bundle->name = optional_param("name_of_bundle");
	$bundle->description = optional_param("description_of_bundle");
	
	$connection->course_id = optional_param("courseid");
	
	// if bundle doesn't exists, store it
	if(!$DB->record_exists("dasis_bundles", array("id" => $bundle->id))) {
		$bundle->id = $DB->insert_record("dasis_bundles", $bundle);
		// first connected course is the current one
		$connection->bundle_id = $bundle->id;
		$DB->insert_record("dasis_bundle_connections", $connection);
	}else{
		$DB->update_record("dasis_bundles", $bundle);
	}
	
	$array = explode("&bundle", $_POST["currenturl"]);
	redirect($array[0]."&bundleId=".$bundle->id);
?>