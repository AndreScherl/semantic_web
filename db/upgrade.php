<?php

/**
 * This file keeps track of upgrades to the newmodule module
 *
 * Sometimes, changes between versions involve alterations to database
 * structures and other major things that may break installations. The upgrade
 * function in this file will attempt to perform all the necessary actions to
 * upgrade your older installation to the current version. If there's something
 * it cannot do itself, it will tell you what you need to do.  The commands in
 * here will all be database-neutral, using the functions defined in DLL libraries.
 *
 * @package   DASIS -> semantic_web
 * @copyright 2010 Andre Scherl
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * xmldb_newmodule_upgrade
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_block_semantic_web_upgrade($oldversion) {
    global $DB;
    $dbman = $DB->get_manager(); // loads ddl manager and xmldb classes

     if ($oldversion < 2011060712) {

        // Define field web_animation to be added to dasis_semantic_web_prefs
        $table = new xmldb_table('dasis_semantic_web_prefs');
        $field = new xmldb_field('web_animation', XMLDB_TYPE_INTEGER, '1', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', 'case_collection');

        // Conditionally launch add field web_animation
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // semantic_web savepoint reached
        upgrade_block_savepoint(true, 2011060712, 'semantic_web');
    }
    
    return true;
}
