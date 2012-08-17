<?php 
/**
 * DASIS - Formular zum Erstellen bzw. Bearbeiten der Metadaten einer Lernaktivität bzw. eines Materials
 * 
 * @package	DASIS -> Semantic Web -> Metadata
 * @autor	Andre Scherl
 * @version	23.09.2011
 *
 *
 * Copyright (C) 2012, Andre Scherl
 * You should have received a copy of the GNU General Public License
 * along with DASIS.  If not, see <http://www.gnu.org/licenses/>. 
 */
 
/** To Do
 *
 * The custom css file is linked in a dirty way at the body tag. It has to be included in the moodle way by css.php?id=...
 *
 */

require_once("../../../config.php");
require_once("../semantic_web_lib.php");


$BLOCK_NAME = "block_semantic_web";

//JavaScript einlesen
$jsmodule = array(
				'name' => 'block_semantic_web',
				'fullpath' => '/blocks/semantic_web/semantic_web.js',
				'requires' => array('node', 'event', 'connection', 'yahoo'));
$PAGE->requires->js_init_call('M.block_semantic_web.init_metadata_actions', null, false, $jsmodule);


$PAGE->set_pagelayout("print");
$PAGE->set_context(get_context_instance(CONTEXT_BLOCK, $SESSION->dasis_blockId));

// Header
$PAGE->set_title(get_string("edit_metadata", $BLOCK_NAME));
//$PAGE->requires->css("metadata.css");

echo $OUTPUT->header();


// Setzen der übergebenen id des Kurses bzw. Moduls
$id = required_param('id', PARAM_INT);
$courseview = optional_param('cv', FALSE, PARAM_BOOL);

// Feststellen, ob wir uns in der Kurs-Ansicht befinden
if($courseview){
    $courseid = $id;
    $metadata = null;
}else{
    $courseid = $DB->get_field("course_modules", "course", array("id" => $id));
    // In der Modul-Ansicht werden die zugehörigen Metadaten geladen.
    $metadata = $DB->get_record("dasis_modmeta", array("coursemoduleid" => $id));
}

// Ein Array mit den Kurs-Modulen als Objekte erstellen
$modules = get_course_mods($courseid);

// Zu diesem Array werden noch weitere Kurs-Module aus anderen Kursen im Bündel hinzugefügt
$bundleconnections = $DB->get_records("dasis_bundle_connections", array("course_id" => $courseid));
foreach($bundleconnections as $bundleconnection) {
	$bccourses = $DB->get_records("dasis_bundle_connections", array("bundle_id" => $bundleconnection->bundle_id));
    foreach($bccourses as $bccourse){
    	$newmods = get_course_mods($bccourse->course_id);
    	$modules = array_merge($modules, $newmods);
    }
}

// Ein Array erstellen mit den Informationen: course module id, name
$mods = array();
foreach($modules as $module) {
    $mods[$module->id] = get_coursemodule_from_id($module->modname, $module->id, $module->course);
    $mods[$module->id]->coursename = $DB->get_field("course", "fullname", array("id" => $module->course));
}

// Feststellen, ob man die Rechte besitzt das Kursmodul zu verändern (wichtig, damit nicht jeder die Titel ändern kann)
$context = get_context_instance(CONTEXT_COURSE, $mods[$id]->course);	
$editor = has_capability('moodle/course:manageactivities', $context);
	
	// Die CSS-Datei hier einzulesen ist sehr unsauber. Das kann mit dem Corporate Design dann sauberer gemacht werden ?>
	<link rel="stylesheet" type="text/css" href="metadata.css"/>
	
	<form id="id_metadataform" method="post" action="storeData.php" autocomplete="on">
	<input type="hidden" name="coursemoduleid" value="<?php echo $id ?>"/>
	<input type="hidden" name="id" value="<?php if(!is_null($metadata)) echo $metadata->id ?>"/>
	<input type="hidden" name="modname" value="<?php echo $mods[$id]->modname ?>"/>
	<input type="hidden" name="instance" value="<?php echo $mods[$id]->instance ?>"/>
		<table>
			<tr>
				<td class="leftColumn">
					<?php echo get_string('title', $BLOCK_NAME)?>
				</td>
				<td class="rightColumn">
					<?php 
						// Befindet man sich in der Kursansicht, wird ein DropDown-Menü gezeigt, sonst ein Textfeld mit dem aktuellen Titel.
						if($courseview) {
							echo "<select name=\"title\" id=\"id_title\">";
							echo "<option value=\"0\" selected=\"selected\">".get_string('activity_pleaseselect', $BLOCK_NAME)."</option>";
							foreach($mods as $mod) {
								echo "<option value=\"".$mod->id."\">".$mod->coursename.": ".$mod->name."</option>";
							}
							echo "</select>";
						}else{
							if($editor) {
								echo "<input type=\"text\" name=\"title\" id=\"id_title\" value=\"".$mods[$id]->name."\"/>";
							}else{
								echo "<input disabled=\"disabled\" type=\"text\" name=\"title\" id=\"id_title\" value=\"".$mods[$id]->name."\"/>";
							}
							
						}
					?>
				</td>
			</tr>
			<tr>
				<td class="leftColumn">
					<?php echo get_string('shortname', $BLOCK_NAME)?>
				</td>
				<td>
					<input type="text" name="shortname" id="id_shortname" placeholder="<?php echo get_string('shortname', $BLOCK_NAME)?>" value="<?php if(!is_null($metadata)) echo $metadata->shortname ?>"/>
				</td>
			</tr>
			<tr>
				<td class="leftColumn">
					<?php echo get_string('description', $BLOCK_NAME)?>
				</td>
				<td>
					<textarea name="description" id="id_description" <?php if(!$editor) echo "disabled=\"disabled\"" ?> rows="5" placeholder="<?php echo get_string('description', $BLOCK_NAME)?>"><?php if(!$courseview) echo get_summary_of_mod($mods[$id]->instance, $mods[$id]->modname)?></textarea>
				</td>
			</tr>
			<tr>
				<td class="leftColumn">
					<?php echo get_string('keywords', $BLOCK_NAME)?>
				</td>
				<td>
					<textarea name="keywords" id="id_keywords" rows="1" placeholder="<?php echo get_string('keywords', $BLOCK_NAME)?>"><?php if(!is_null($metadata)) echo $metadata->keywords ?></textarea>
				</td>
			</tr>
			<tr>
				<td class="leftColumn">
					<?php echo get_string('learning_tasks', $BLOCK_NAME)?>
				</td>
				<td>
					<textarea name="learning_tasks" id="id_learning_tasks" rows="1" placeholder="<?php echo get_string('learning_tasks', $BLOCK_NAME)?>"><?php echo get_string('learning_tasks', $BLOCK_NAME)?></textarea>
				</td>
			</tr>
			<tr>
				<td class="leftColumn">
					<?php echo get_string('taxonomy', $BLOCK_NAME)?>
				</td>
				<td>
					<input type="text" name="taxonomy" id="id_taxonomy" placeholder="<?php echo get_string('taxonomy_example', $BLOCK_NAME)?>" value="<?php if(!is_null($metadata)) echo $metadata->taxonomy ?>"/>
				</td>
			</tr>
			<tr>
				<td class="leftColumn">
					<?php echo get_string('relations', $BLOCK_NAME)?>
				</td>
				<td>
					<?php 
						if(!$courseview && $relitems = $DB->get_records_sql("SELECT * FROM {$CFG->prefix}dasis_relations WHERE source=$id OR target=$id")){
							echo "<ul id=\"id_relationslist\">";
							foreach($relitems as $relitem) {
								echo "<li>";
								echo $mods[$relitem->source]->name." ".get_string($relitem->type, $BLOCK_NAME)." ".$mods[$relitem->target]->name;
								echo " ";
								echo "<a href=\"remove_relation.php?id=$id&relid=$relitem->id\">[".get_string('remove', $BLOCK_NAME)."]</a>";
								echo "</li>";
							}
							echo "</ul>";
						}
					?>
					<select name="source" id="id_source">
					<?php 
						if($courseview) {
							echo "<option value=\"0\" selected=\"selected\">".get_string("relation_pleaseselectsource", $BLOCK_NAME)."</option>";
							foreach($mods as $mod) {
								echo "<option id=\"id_source_{$mod->id}\" class=\"source\" value=\"$mod->id\">$mod->coursename: $mod->name</option>";
							}
						}else{
							echo "<option value=\"0\">".get_string("relation_pleaseselectsource", $BLOCK_NAME)."</option>";
							foreach($mods as $mod) {
			
								if($mod->id == $id) {
									echo "<option id=\"id_source_{$mod->id}\" class=\"source\" value=\"$mod->id\" selected=\"selected\">$mod->coursename: $mod->name</option>";
								}else{
									echo "<option id=\"id_source_{$mod->id}\" class=\"source\" value=\"$mod->id\">$mod->coursename: $mod->name</option>";
								}
							}
						}
					?>
					</select>
					<select name="relations" id="id_relations">
						<option value="0" selected="selected"><?php echo get_string("relation_pleaseselect", $BLOCK_NAME)?></option>
						<option value="relation_vertieft"><?php echo get_string("relation_vertieft", $BLOCK_NAME)?></option>
            			<option value="relation_erlaeutert"><?php echo get_string("relation_erlaeutert", $BLOCK_NAME)?></option>
           				<option value="relation_beispiel"><?php echo get_string("relation_beispiel", $BLOCK_NAME)?></option>
            			<option value="relation_anwendung"><?php echo get_string("relation_anwendung", $BLOCK_NAME)?></option>
            			<option value="relation_illustriert"><?php echo get_string("relation_illustriert", $BLOCK_NAME)?></option>
           	 			<option value="relation_querverweis"><?php echo get_string("relation_querverweis", $BLOCK_NAME)?></option>
            			<option value="relation_exkurs"><?php echo get_string("relation_exkurs", $BLOCK_NAME)?></option>
            			<option value="relation_fasstzusammen"><?php echo get_string("relation_fasstzusammen", $BLOCK_NAME)?></option>
            			<option value="relation_bautauf"><?php echo get_string("relation_bautauf", $BLOCK_NAME)?></option>
            			<option value="relation_wiederholt"><?php echo get_string("relation_wiederholt", $BLOCK_NAME)?></option>
            			<option value="relation_setztvoraus"><?php echo get_string("relation_setztvoraus", $BLOCK_NAME)?></option>
            			<option value="relation_prueft"><?php echo get_string("relation_prueft", $BLOCK_NAME)?></option>
					</select>
					<select name="target" id="id_target">
						<?php 
							echo "<option value=\"0\" selected=\"selected\">".get_string("relation_pleaseselecttarget", $BLOCK_NAME)."</option>";
							foreach($mods as $mod) {
								// via javascript (siehe metadata.js) wird die Option entfernt, die als Quelle ausgewählt ist
								echo "<option id=\"id_target_{$mod->id}\" class=\"target\" value=\"$mod->id\">$mod->coursename: $mod->name</option>";
							}
						?>
					</select>
					<?php 
						echo "<input type=\"button\" name=\"add_rel\" id=\"id_add_rel\" value=\"".get_string("add", $BLOCK_NAME)."\"/>";
					?>
				</td>
			</tr>
			<tr>
				<td class="leftColumn">
					<?php echo get_string('difficulty', $BLOCK_NAME)?>
				</td>
				<td>
					<select name="difficulty" id="id_difficulty">
						<option><?php echo get_string("pleaseselect", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->difficulty == 0.1) echo "selected=\"selected\"" ?>value="0.1"><?php echo get_string("difficulty_verylow", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->difficulty == 0.3) echo "selected=\"selected\"" ?>value="0.3"><?php echo get_string("difficulty_low", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->difficulty == 0.5) echo "selected=\"selected\"" ?>value="0.5"><?php echo get_string("difficulty_normal", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->difficulty == 0.7) echo "selected=\"selected\"" ?>value="0.7"><?php echo get_string("difficulty_high", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->difficulty == 0.9) echo "selected=\"selected\"" ?>value="0.9"><?php echo get_string("difficulty_veryhigh", $BLOCK_NAME) ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="leftColumn">
					<?php echo get_string('linguistic_requirement', $BLOCK_NAME)?>
				</td>
				<td>
					<select name="linguistic_requirement" id="id_linguistic_requirement">
						<option><?php echo get_string("pleaseselect", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->linguistic_requirement == 0.1) echo "selected=\"selected\"" ?> value="0.1"><?php echo get_string("linguistic_requirement_verylow", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->linguistic_requirement == 0.3) echo "selected=\"selected\"" ?> value="0.3"><?php echo get_string("linguistic_requirement_low", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->linguistic_requirement == 0.5) echo "selected=\"selected\"" ?> value="0.5"><?php echo get_string("linguistic_requirement_normal", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->linguistic_requirement == 0.7) echo "selected=\"selected\"" ?> value="0.7"><?php echo get_string("linguistic_requirement_high", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->linguistic_requirement == 0.9) echo "selected=\"selected\"" ?> value="0.9"><?php echo get_string("linguistic_requirement_veryhigh", $BLOCK_NAME) ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="leftColumn">
					<?php echo get_string('logical_requirement', $BLOCK_NAME)?>
				</td>
				<td>
					<select name="logical_requirement" id="id_logical_requirement">
						<option><?php echo get_string("pleaseselect", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->logical_requirement == 0.1) echo "selected=\"selected\"" ?> value="0.1"><?php echo get_string("logical_requirement_verylow", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->logical_requirement == 0.3) echo "selected=\"selected\"" ?> value="0.3"><?php echo get_string("logical_requirement_low", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->logical_requirement == 0.5) echo "selected=\"selected\"" ?> value="0.5"><?php echo get_string("logical_requirement_normal", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->logical_requirement == 0.7) echo "selected=\"selected\"" ?> value="0.7"><?php echo get_string("logical_requirement_high", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->logical_requirement == 0.9) echo "selected=\"selected\"" ?> value="0.9"><?php echo get_string("logical_requirement_veryhigh", $BLOCK_NAME) ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="leftColumn">
					<?php echo get_string('social_requirement', $BLOCK_NAME)?>
				</td>
				<td>
					<select name="social_requirement" id="id_social_requirement">
						<option><?php echo get_string("pleaseselect", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->social_requirement == 0.1) echo "selected=\"selected\"" ?> value="0.1"><?php echo get_string("social_requirement_verylow", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->social_requirement == 0.3) echo "selected=\"selected\"" ?> value="0.3"><?php echo get_string("social_requirement_low", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->social_requirement == 0.5) echo "selected=\"selected\"" ?> value="0.5"><?php echo get_string("social_requirement_normal", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->social_requirement == 0.7) echo "selected=\"selected\"" ?> value="0.7"><?php echo get_string("social_requirement_high", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->social_requirement == 0.9) echo "selected=\"selected\"" ?> value="0.9"><?php echo get_string("social_requirement_veryhigh", $BLOCK_NAME) ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="leftColumn">
					<?php echo get_string('learningstyle_perception', $BLOCK_NAME)?>
				</td>
				<td>
					<select name="learningstyle_perception" id="id_learningstyle_perception">
						<option><?php echo get_string("pleaseselect", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->learningstyle_perception == 0.1) echo "selected=\"selected\"" ?> value="0.1"><?php echo get_string("learningstyle_perception_verylow", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->learningstyle_perception == 0.3) echo "selected=\"selected\"" ?> value="0.3"><?php echo get_string("learningstyle_perception_low", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->learningstyle_perception == 0.5) echo "selected=\"selected\"" ?> value="0.5"><?php echo get_string("learningstyle_perception_normal", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->learningstyle_perception == 0.7) echo "selected=\"selected\"" ?> value="0.7"><?php echo get_string("learningstyle_perception_high", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->learningstyle_perception == 0.9) echo "selected=\"selected\"" ?> value="0.9"><?php echo get_string("learningstyle_perception_veryhigh", $BLOCK_NAME) ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="leftColumn">
					<?php echo get_string('learningstyle_organization', $BLOCK_NAME)?>
				</td>
				<td>
					<select name="learningstyle_organization" id="id_learningstyle_organization">
						<option><?php echo get_string("pleaseselect", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->learningstyle_organization == 0.1) echo "selected=\"selected\"" ?> value="0.1"><?php echo get_string("learningstyle_organization_verylow", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->learningstyle_organization == 0.3) echo "selected=\"selected\"" ?> value="0.3"><?php echo get_string("learningstyle_organization_low", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->learningstyle_organization == 0.5) echo "selected=\"selected\"" ?> value="0.5"><?php echo get_string("learningstyle_organization_normal", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->learningstyle_organization == 0.7) echo "selected=\"selected\"" ?> value="0.7"><?php echo get_string("learningstyle_organization_high", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->learningstyle_organization == 0.9) echo "selected=\"selected\"" ?> value="0.9"><?php echo get_string("learningstyle_organization_veryhigh", $BLOCK_NAME) ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="leftColumn">
					<?php echo get_string('learningstyle_perspective', $BLOCK_NAME)?>
				</td>
				<td>
					<select name="learningstyle_perspective" id="id_learningstyle_perspective">
						<option><?php echo get_string("pleaseselect", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->learningstyle_perspective == 0.1) echo "selected=\"selected\"" ?> value="0.1"><?php echo get_string("learningstyle_perspective_verylow", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->learningstyle_perspective == 0.3) echo "selected=\"selected\"" ?> value="0.3"><?php echo get_string("learningstyle_perspective_low", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->learningstyle_perspective == 0.5) echo "selected=\"selected\"" ?> value="0.5"><?php echo get_string("learningstyle_perspective_normal", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->learningstyle_perspective == 0.7) echo "selected=\"selected\"" ?> value="0.7"><?php echo get_string("learningstyle_perspective_high", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->learningstyle_perspective == 0.9) echo "selected=\"selected\"" ?> value="0.9"><?php echo get_string("learningstyle_perspective_veryhigh", $BLOCK_NAME) ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="leftColumn">
					<?php echo get_string('learningstyle_input', $BLOCK_NAME)?>
				</td>
				<td>
					<select name="learningstyle_input" id="id_learningstyle_input">
						<option><?php echo get_string("pleaseselect", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->learningstyle_input == 0.1) echo "selected=\"selected\"" ?> value="0.1"><?php echo get_string("learningstyle_input_verylow", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->learningstyle_input == 0.3) echo "selected=\"selected\"" ?> value="0.3"><?php echo get_string("learningstyle_input_low", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->learningstyle_input == 0.5) echo "selected=\"selected\"" ?> value="0.5"><?php echo get_string("learningstyle_input_normal", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->learningstyle_input == 0.7) echo "selected=\"selected\"" ?> value="0.7"><?php echo get_string("learningstyle_input_high", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->learningstyle_input == 0.9) echo "selected=\"selected\"" ?> value="0.9"><?php echo get_string("learningstyle_input_veryhigh", $BLOCK_NAME) ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="leftColumn">
					<?php echo get_string('learningstyle_processing', $BLOCK_NAME)?>
				</td>
				<td>
					<select name="learningstyle_processing" id="id_learningstyle_processing">
						<option><?php echo get_string("pleaseselect", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->learningstyle_processing == 0.1) echo "selected=\"selected\"" ?> value="0.1"><?php echo get_string("learningstyle_processing_verylow", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->learningstyle_processing == 0.3) echo "selected=\"selected\"" ?> value="0.3"><?php echo get_string("learningstyle_processing_low", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->learningstyle_processing == 0.5) echo "selected=\"selected\"" ?> value="0.5"><?php echo get_string("learningstyle_processing_normal", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->learningstyle_processing == 0.7) echo "selected=\"selected\"" ?> value="0.7"><?php echo get_string("learningstyle_processing_high", $BLOCK_NAME) ?></option>
						<option <?php if(!is_null($metadata)) if($metadata->learningstyle_processing == 0.9) echo "selected=\"selected\"" ?> value="0.9"><?php echo get_string("learningstyle_processing_veryhigh", $BLOCK_NAME) ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="leftColumn">
					<?php echo get_string('learning_time', $BLOCK_NAME)?>
				</td>
				<td>
					<input type="number" name="learning_time" id="id_learning_time" placeholder="<?php echo get_string('learning_time', $BLOCK_NAME)?>" value="<?php if(!is_null($metadata)) if($metadata->learning_time > 0) echo $metadata->learning_time ?>"/>
				</td>
			</tr>
			<tr>
				<td class="leftColumn">
					<?php echo get_string('catalog', $BLOCK_NAME)?>
				</td>
				<td>
					<input type="text" name="catalog" id="id_catalog" placeholder="<?php echo get_string('catalog', $BLOCK_NAME)?>" value="<?php if(!is_null($metadata)) echo $metadata->catalog ?>"/>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="button" name="btn_cancel" id="id_cancel" value="<?php echo get_string('cancel', $BLOCK_NAME) ?>"/>
					<input type="button" name="btn_submitclose" id="id_submitclose" value="<?php echo get_string('submitclose', $BLOCK_NAME) ?>"/>
					<input type="submit" name="btn_submit" id="id_submit" value="<?php echo get_string('submit', $BLOCK_NAME) ?>"/>
				</td>
			</tr>
		</table>
	</form>
<?php

echo $OUTPUT->footer();