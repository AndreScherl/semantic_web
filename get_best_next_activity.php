<?php

/**
 * Dieses Skript berechnet die beste nächste Lernaktivität und gibt den Wert zurück
 *
 * @package	DASIS->SemanticWeb
 * @author	Andre Scherl
 * @version	1.0 - 17.06.2011
 *
 * Copyright (C) 2012, Andre Scherl
 * You should have received a copy of the GNU General Public License
 * along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
 */
 
 error_reporting(E_ALL);
 
 require_once("../../config.php");

 global $DB, $CFG, $SESSION, $USER;
 
 // get last Node to be sure its not the next node
 $lastNode = $DB->get_field_sql("SELECT coursemoduleid FROM {ilms_history} WHERE userid={$USER->id} ORDER BY timemodified DESC LIMIT 1,1");
 
 $applianceArray = array();
 foreach($SESSION->dasis_iLMS_solutions as $solution){
 	$applianceArray[$solution->id] = $solution->appliance;
 }
 unset($applianceArray[$SESSION->dasis_activityId]);
 unset($applianceArray[$lastNode]);
 
 if(count($applianceArray) > 0){
 	echo "{$CFG->wwwroot}/blocks/case_repository/start.php?id=".array_search(max($applianceArray), $applianceArray)."&foreward=true";
 } else {
 	echo "#";
 }
  
?>