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

require_once($CFG->dirroot . '/blocks/semantic_web/backup/moodle2/backup_semantic_web_stepslib.php'); // We have structure steps

/**
 * Specialised backup task for the semantic_web block
 * (has own DB structures to backup)
 *
 * TODO: Finish phpdocs
 */
class backup_semantic_web_block_task extends backup_block_task {

    protected function define_my_settings() {
    }

    protected function define_my_steps() {
        // semantic_web has one structure step
        $this->add_step(new backup_semantic_web_block_structure_step('semantic_web_structure', 'semantic_web.xml'));
    }

    public function get_fileareas() {
        return array(); // No associated fileareas
    }

    public function get_configdata_encoded_attributes() {
        return array(); // No special handling of configdata
    }
    
    /**
     * Code the transformations to perform in the activity in
     * order to get transportable (encoded) links
     */
    static public function encode_content_links($content) {
        global $CFG;
 
        $base = preg_quote($CFG->wwwroot,"/");
 
        // Link to the list of choices
        $search="/(".$base."\/block\/semantic_web\/index.php\?id\=)([0-9]+)/";
        $content= preg_replace($search, '$@SEMANTICWEBINDEX*$2@$', $content);
 
        // Link to choice view by moduleid
        $search="/(".$base."\/block\/semantic\/view.php\?id\=)([0-9]+)/";
        $content= preg_replace($search, '$@SEMANTICWEBVIEWBYID*$2@$', $content);
 
        return $content;
    }
}

