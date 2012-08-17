<?php
/**
 * Dieses Skript schreibt die iLMS-Lösungen in die SESSION-Variable
 *
 * @package	DASIS->SemanticWeb
 * @author	Andre Scherl
 * @version	11.07.2012
 *
 * Copyright (C) 2012, Andre Scherl
 * You should have received a copy of the GNU General Public License
 * along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
 */
 
require_once("../../config.php");
global $SESSION;

$solutions = $_POST["solutions"];

if($SESSION->dasis_webprefs[$SESSION->dasis_blockId]->adaption){
 	
	$solutions_array = json_decode($solutions);
	$solutions = array();
	foreach($solutions_array as $sol_item) {
		/*
		$item["appliance"] = $sol_item->appliance;
		$item["count"] = $sol_item->count;
		$item["id"] = $sol_item->id;
		$item["maximum"] = $sol_item->maximum;
		*/	
		$solutions[$sol_item->id] = $sol_item;
	}
	$SESSION->dasis_iLMS_solutions = $solutions;
 	
 	print_r($SESSION->dasis_iLMS_solutions);
}
 
?>