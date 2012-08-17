<?php 
	/**
	 * Remove a bundle connection
	 * 
	 * @package Semantic Web
	 * @autor Andre Scherl
	 * @version 1.0 - 19.11.2010
	 *
	 * Copyright (C) 2012, Andre Scherl
	 * You should have received a copy of the GNU General Public License
	 * along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
	 */

	require_once("../../config.php");
	
	global $DB;
	
	$bcid = optional_param("bcid");
	
	if($bcid) {
		$DB->delete_records("dasis_bundle_connections", array("id" => $bcid));
	}
	redirect($_POST["currenturl"]);
?>