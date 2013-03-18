<?php 
/**
* This file builds an overview of the semantic web activity relations
* The "protovis"-library developed by university of stanford is used to visualize the overview matrix.
*
* @package	DASIS -> Semantic Web -> Semantic Web
* @author	Andre Scherl
* @version	1.2 - 05.09.2011
*
* Copyright (C) 2012, Andre Scherl
* You should have received a copy of the GNU General Public License
* along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
*/

require_once("../../../config.php");
require_once("../semantic_web_lib.php");

global $SESSION;

$PAGE->set_pagelayout("print");
$PAGE->set_context(get_context_instance(CONTEXT_BLOCK, $SESSION->dasis_blockId));
echo $OUTPUT->header();

$id = $SESSION->dasis_activityId;
//$blockid = $SESSION->dasis_blockId;
$course_id = $DB->get_field("course_modules", "course", array("id" => $id));

//// Die Knoten und Verbindungen werden abgefragt

// Zuerst werden alle Module der vernetzten Kurse abgefragt
$modules_sql = "SELECT DISTINCT cm.id as id, bc.course_id AS course, m.name as module, cm.instance as instance FROM {course_modules} cm ".
					"LEFT JOIN {dasis_bundle_connections} bc ON bc.course_id = cm.course ".
					"LEFT JOIN {modules} m ON cm.module = m.id ".
				"WHERE bc.bundle_id = ANY (SELECT bundle_id FROM {dasis_bundle_connections} WHERE course_id = ?) ".
				"ORDER BY bc.course_id, cm.id";
$modules = $DB->get_records_sql($modules_sql, array($course_id));
$groups = array();
foreach($modules as $module){
	$module->title = $DB->get_field($module->module, "name", array("id" => $module->instance));
	$groups[] = $module->course;
}

// Um mit dem Protovis-Toolkit zeichnen zu können, müssen die Relations von 0 bis x durchnummeriert sein. Der Keys-Array ordnet diese einer module id zu.
$keys = array_keys($modules);

// Nun werden dir Relationen geladen
$relations_sql = "SELECT DISTINCT r.id as id, r.source as source, r.target as target, r.type as type FROM {dasis_relations} r ".
						"LEFT JOIN {course_modules} cm ON cm.id = r.source ".
					    "LEFT JOIN {dasis_bundle_connections} bc ON bc.course_id = cm.course ".
					"WHERE bc.bundle_id = ANY (SELECT bundle_id FROM {dasis_bundle_connections} WHERE course_id = ?)";
$relations = $DB->get_records_sql($relations_sql, array($course_id));

// Festlegen der Farben für die Relationstypen
$colorArray = array("darkred", "red", "orange", "gold", "green", "darkgreen", "blue", "darkblue", "purple");
$sql = "SELECT DISTINCT type FROM {dasis_relations}";
$relationtypes = array_keys($DB->get_records_sql($sql));
//$colorArray = array_combine($relationtypes, $colorArray); geht nur wenn beide Arrays immer gleich lang sind. also for-loop.
$colorArray2 = array();
for($i=0; $i<count($relationtypes); $i++) {
	$colorArray2[$relationtypes[$i]] = $colorArray[$i];
}

// Die Relationen und Lernaktivitäten werden in JavaScript-Variable überführt
?>
	<link rel="stylesheet" type="text/css" href="semantic_overview.css"/>
	<script type="text/javascript" src="./js/protovis.min.js"></script>
	<script type="text/javascript">
		var relColors = [<?php
							foreach($colorArray2 as $color){
								echo "\"$color\", ";
				  			}
						?>];
	
		var chunks = {
			nodes:[
				<?php foreach($modules as $module){
							echo "{nodeName:\"{$module->title}\", group:".array_search($module->course, $groups)."},\n";
						}?>
			],
			links:[
				<?php foreach($relations as $relation){
							echo "{source:".array_search($relation->source, $keys).", target:".array_search($relation->target, $keys).", value:".(array_search($relation->type, $relationtypes)+1)."},\n";
						}?>
			]
		};
	</script>
	<div id="center">
		<div id="caption">
			<?php
			   echo "<p><a id=\"back\" href=\"javascript:history.back()\">".get_string('back', 'block_semantic_web')."</a></p>";
			   echo "<b><u>".get_string("caption", "block_semantic_web")."</u></b>";
			   echo "<ul>";
			   foreach($relationtypes as $rt){
			   	echo "<li class=\"relation_type\" id=\"$rt\" style=\"color:".$colorArray2[$rt]."\">".get_string($rt, 'block_semantic_web')."</li>";
			   }
			   echo "</ul>";
			?>
		</div>
		<div id="fig">
			<script type="text/javascript+protovis">			
				var color = pv.colors("#666600", "#999933", "#cccc66", "#666633", "#999966", "#cccc99").by(function(d) d.group);
				
				var vis = new pv.Panel()
				    .width(0.7*parent.document.documentElement.clientWidth)
				    .height(0.7*parent.document.documentElement.clientWidth)
				    .top(0.3*parent.document.documentElement.clientWidth)
				    .left(0.25*parent.document.documentElement.clientWidth);
				
				var layout = vis.add(pv.Layout.Matrix)
				    .nodes(chunks.nodes)
				    .links(chunks.links)
				    .sort(function(a, b) b.group - a.group)
				    .directed(true);
				
				layout.link.add(pv.Bar)
				    .fillStyle(function(l) l.linkValue ? relColors[l.linkValue-1] : "#eee")
				    .antialias(false)
				    .lineWidth(1);
				
				layout.label.add(pv.Label)
					.font("14px sans-serif")
				    .textStyle(color);
				
				vis.render();
    		</script>
  		</div>
	</div>
<?php
	echo $OUTPUT->footer();