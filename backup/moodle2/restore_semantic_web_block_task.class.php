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

require_once($CFG->dirroot . '/blocks/semantic_web/backup/moodle2/restore_semantic_web_stepslib.php'); // We have structure steps

/**
 * Specialised restore task for the semantic_web block
 * (has own DB structures to backup)
 *
 * TODO: Finish phpdocs
 */
class restore_semantic_web_block_task extends restore_block_task {

    protected function define_my_settings() {
    }

    protected function define_my_steps() {
        // semantic_web has one structure step
        $this->add_step(new restore_semantic_web_block_structure_step('semantic_web_structure', 'semantic_web.xml'));
    }

    public function get_fileareas() {
        return array(); // No associated fileareas
    }

    public function get_configdata_encoded_attributes() {
        return array(); // No special handling of configdata
    }

    static public function define_decode_contents() {
    	$contents = array();
    	
    	$contents[] = new restore_decode_content("dasis_bundles", array("name", "description"), "bundle");
    	$contents[] = new restore_decode_content("dasis_learning_paths", array("name"), "learning_path");
    	$contents[] = new restore_decode_content("dasis_modmeta", array("shortname", "keywords", "learning_tasks", "taxonomy", "catalog"), "modmeta");
        
        return $contents;
    }

    static public function define_decode_rules() {
    	$rules = array();
    	
    	$rules[] = new restore_decode_rule("SEMANTICWEBINDEX", "/block/semantic_web/index.php?id=$1", "course");
    	$rules[] = new restore_decode_rule("SEMANTICWEBVIEWBYID", "/block/semantic_web/view.php?id=$1", "course_module");
    	
        return $rules;
    }
    
    //! TODO implement restore log rules
}

