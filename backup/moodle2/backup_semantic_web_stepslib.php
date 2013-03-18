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
 * Define all the backup steps that wll be used by the backup_semantic_web_block_task
 */

/**
 * Define the complete semantic web structure for backup, with file and id annotations
 */
class backup_semantic_web_block_structure_step extends backup_block_structure_step {

    protected function define_structure() {
         // To know if we are including userinfo
        //$userinfo = $this->get_setting_value('userinfo');
 
        $rootcontainer = new backup_nested_element("rootcontainer");
        
        // Define each element separated
        $bundles = new backup_nested_element("bundles");
        $bundle = new backup_nested_element("bundle", array("id"), array("name", "description"));
        $bundle_connections = new backup_nested_element("bundle_connections");
        $bundle_connection = new backup_nested_element("bundle_connection", array("id"), array("bundle_id", "course_id"));
        $learning_paths = new backup_nested_element("learning_paths");
        $learning_path = new backup_nested_element("learning_path", array("id"), array("name", "path", "color", "bundle_id"));
        
        $modmetas = new backup_nested_element("modmetas");
        $modmeta = new backup_nested_element("modmeta", array("id"), array("shortname", "linguistic_requirement", "social_requirement", "logical_requirement", "learningstyle_perception", "learningstyle_organization", "learningstyle_perspective", "learningstyle_input", "difficulty", "learningstyle_processing", "learning_time", "keywords", "learning_tasks", "taxonomy", "catalog", "coursemoduleid"));
        
        $relations = new backup_nested_element("relations");
        $relation = new backup_nested_element("relation", array("id"), array("source", "target", "type"));
        
        $semantic_web_pref = new backup_nested_element("semantic_web_pref", array("id"), array("block_id", "depth", "adaption", "case_collection", "web_animation"));
        
        $last_activities = new backup_nested_element("last_activities");
        $last_activity = new backup_nested_element("last_activity", array("id"), array("userid", "courseid", "course_module", "last_access"));
 
        // Build the tree
        $bundles->add_child($bundle);
        $bundle->add_child($bundle_connections);
        $bundle_connections->add_child($bundle_connection);
        $bundle->add_child($learning_paths);
        $learning_paths->add_child($learning_path);
        
        $modmetas->add_child($modmeta);
        
        $relations->add_child($relation);
        
        $last_activities->add_child($last_activity);
        
        $rootcontainer->add_child($bundles);
        $rootcontainer->add_child($modmetas);
        $rootcontainer->add_child($relations);
        $rootcontainer->add_child($semantic_web_pref);
        $rootcontainer->add_child($last_activities);
 
        // Define sources
        $bundle->set_source_sql("SELECT * FROM {dasis_bundles} b
        							LEFT JOIN {dasis_bundle_connections} bc ON b.id = bc.bundle_id
        							WHERE bc.course_id = ?", array(backup::VAR_COURSEID));
        
        $bundle_connection->set_source_table("dasis_bundle_connections", array("course_id" => backup::VAR_COURSEID));
        
        $learning_path->set_source_table("dasis_learning_paths", array("bundle_id" => "../../id"));
        
        $modmeta->set_source_sql("SELECT * FROM {dasis_modmeta} dm 
        							LEFT JOIN {course_modules} cm ON dm.coursemoduleid = cm.id 
        							WHERE cm.course = ?", array(backup::VAR_COURSEID));
        
        $relation->set_source_sql("SELECT DISTINCT r.* 
        							FROM {dasis_relations} r, {course_modules} cm 
        							WHERE ((r.source = cm.id) OR (r.target = cm.id)) AND cm.course = ?", array(backup::VAR_COURSEID));
        
        $semantic_web_pref->set_source_table("dasis_semantic_web_prefs", array("block_id" => backup::VAR_BLOCKID));
        
        $last_activity->set_source_table("dasis_last_activity", array("courseid" => backup::VAR_COURSEID));
 
        // Define id annotations
        $last_activity->annotate_ids("user", "userid");
        $modmeta->annotate_ids("coursemodule", "coursemoduleid");
 
        // Define file annotations
 
        // Return the root element (choice), wrapped into standard activity structure
        return $this->prepare_block_structure($rootcontainer);
    }
}
