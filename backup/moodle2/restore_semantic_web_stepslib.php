<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package DASIS - Semantic Web
 * @author 	Andre Scherl
 * @version 1.0 - 26.08.2011
 */

/**
 * Define all the restore steps that will be used by the restore_semantic_web_block_task
 */

/**
 * Define the complete semantic_web  structure for restore
 */
class restore_semantic_web_block_structure_step extends restore_structure_step {

    protected function define_structure() {

        $paths = array();
		
		//$paths[] = new restore_path_element('block', '/block', true);
		//$paths[] = new restore_path_element('rootcontainer', '/block/rootcontainer');
        $paths[] = new restore_path_element('bundle', '/block/rootcontainer/bundles/bundle');
        $paths[] = new restore_path_element('bundle_connection', '/block/rootcontainer/bundles/bundle/bundle_connections/bundle_connection');
        $paths[] = new restore_path_element('learning_path', '/block/rootcontainer/bundles/bundle/learning_paths/learning_path');
        $paths[] = new restore_path_element('modmeta', '/block/rootcontainer/modmetas/modmeta');
        $paths[] = new restore_path_element('relation', '/block/rootcontainer/relations/relation');
        $paths[] = new restore_path_element('semantic_web_pref', '/block/rootcontainer/semantic_web_pref');
        $paths[] = new restore_path_element('last_activity', '/block/rootcontainer/last_activities/last_activity');

        return $paths;
    }
	
    
    public function process_bundle($data) {
    	global $DB;
    	
    	$data = (object)$data;
    	$oldid = $data->id;
    	
    	$newitemid = $DB->insert_record("dasis_bundles", $data);
    	$this->set_mapping("bundle", $oldid, $newitemid);
    }
    
    public function process_bundle_connection($data) {
    	global $DB;
    	
    	$data = (object)$data;
    	$oldid = $data->id;
    	
    	$data->bundle_id = $this->get_mappingid("bundle", $oldid);
    	$data->course_id = $this->get_courseid();
    	
    	$newitemid = $DB->insert_record("dasis_bundle_connections", $data);
    }
    
    public function process_learning_path($data) {
    	global $DB;
    	
    	$data = (object)$data;
    	$oldid = $data->id;
    	
    	$data->bundle_id = $this->get_mappingid("bundle", $oldid);
    	
    	$newitemid = $DB->insert_record("dasis_learning_paths", $data);
    }
    
    public function process_modmeta($data) {
    	global $DB;
    	
    	$data = (object)$data;
    	$oldid = $data->id; //! Die Restore-Reihenfolge ist falsch: erst dieser Block, dann die Kurs Aktivitäten. Daher kann man hier noch nicht auf die neuen ids zugreifen.
    	//$data->coursemoduleid = $this->get_mappingid("coursemodule", $data->coursemoduleid);
    	$data->coursemoduleid = $DB->get_field("backup_ids_temp", "newitemid", array("itemid" => $oldid));
    	
    	$newitemid = $DB->insert_record("dasis_modmeta", $data);
    }
    
    public function process_relation($data) {
    	global $DB;
    	
    	$data = (object)$data;
    	$oldid = $data->id;
    	$oldsourceid = $data->source;
    	$oldtargetid = $data->target;
    	
    	$data->source = $DB->get_field("backup_ids_temp", "newitemid", array("itemid" => $oldsourceid));
    	$data->target = $DB->get_field("backup_ids_temp", "newitemid", array("itemid" => $oldtargetid));
    	
    	$newitemid = $DB->insert_record("dasis_relations", $data);
    }
    
    public function process_semantic_web_pref($data) {
    	global $DB;
    	
    	$data = (object)$data;
    	$oldid = $data->id;
    	
    	$data->block_id = $this->get_mappingid("block_id", $oldid);
    	
    	$newitemid = $DB->insert_record("dasis_semantic_web_prefs", $data);
    }
    
    public function process_last_activity($data) {
    	global $DB;
    	
    	$data = (object)$data;
    	$oldid = $data->id;
    	$olduserid = $data->userid;
    	$oldcmid = $data->course_module;
    	
    	$data->courseid = $this->get_courseid();
    	$data->userid = $this->get_mappingid("user", $olduserid);
    	$data->course_module = $DB->get_field("backup_ids_temp", "newitemid", array("itemid" => $oldcmid));
    	
    	$newitemid = $DB->insert_record("dasis_last_activity", $data);
    }
    
    protected function after_execute() {
    	/*
    	print("SEMANTIC WEB SEMANTIC WEB");
    	global $DB;
    	print_r($DB->get_records("backup_ids_temp"));
    	*/
    }
}
