<?php 
	/**
	 * remove the bundle and its description from table dasis_bundles
	 * 
	 * @package	DASIS -> Semantic Web
	 * @author	Andre Scherl
	 * @version	1.0 - 22.11.2010
	 *
	 * Copyright (C) 2012, Andre Scherl
	 * You should have received a copy of the GNU General Public License
	 * along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
	 */

	require_once("../../config.php");
	
	$bundleId = optional_param("bundleId");
	
	// if bundle doesn't exists, store it
	if($DB->record_exists("dasis_bundles", array("id" => $bundleId))) {
		$DB->delete_records("dasis_bundles", array("id" => $bundleId));
		$DB->delete_records("dasis_bundle_connections", array("bundle_id" => $bundleId));
	}

	redirect($_POST["currenturl"]);
?>