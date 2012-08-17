<?php 
/**
* This file builds the semantic web of learning contents by working with metadata stored in database tables
* "dasis_modmeta" and "dasis_relations".
* The "protovis"-library developed by university of stanford is used to visualize the semantic web.
*
* @package	DASIS -> Semantic Web -> Semantic Web
* @author	Andre Scherl
* @version	23.9.2011
*
* Copyright (C) 2012, Andre Scherl
* You should have received a copy of the GNU General Public License
* along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
*/

require_once("../../../config.php");
require_once("../semantic_web_lib.php");


//JavaScript einlesen
$jsmodule = array(
				'name' => 'block_semantic_web',
				'fullpath' => '/blocks/semantic_web/semantic_web.js',
				'requires' => array('node', 'event', 'dd-drag'));
$PAGE->requires->js_init_call('M.block_semantic_web.init_web_actions', null, false, $jsmodule);

$PAGE->set_context(get_context_instance(CONTEXT_BLOCK, $SESSION->dasis_blockId));
$PAGE->set_pagelayout("print"); //!(AS) LMUdle Layout without header
//$PAGE->set_pagelayout("popup");  //! (AS) local Layout without header
echo $OUTPUT->header();

$target = $SESSION->dasis_activityId;
$course_id = $DB->get_field("course_modules", "course", array("id" => $target));
$blockid = $SESSION->dasis_blockId;


if(isset($SESSION->dasis_iLMS_solutions)){
	$solutions = $SESSION->dasis_iLMS_solutions;
}
 

/**
* Erzeugen eines Arrays mit den Chunks, die das Netz bilden.
*/
$chunks = array();
$chunks[0] = buildChunk($target);
$chunks[0]->level = 0;
$chunks[0]->sources = getSourcesFromTarget($target);
$chunks[0]->targets = getTargetsFromSource($target);

$tempSources = $chunks[0]->sources;
$tempTargets = $chunks[0]->targets;
$collectedIds = array(); // Diese Chunks wurden schon gesammelt
$collectedIds[] = $chunks[0]->id;
//for($i=1; $i<=$SESSION->dasis_webprefs[$SESSION->dasis_blockId]->depth; $i++){
for($i=1; $i<=$SESSION->userDepth; $i++){
	$freshTempTargets = array();
    foreach($tempSources as $source){
    	unset($tempSources[array_search($source, $tempSources)]);
    	$chunk = buildChunk($source);
    	$chunk->level = $i;
    	$chunk->sources = getSourcesFromTarget($source);
    	$chunk->targets = getTargetsFromSource($source);
    	if(!in_array($chunk->id, $collectedIds)) {
    		$chunks[] = $chunk;
    		$collectedIds[] = $chunk->id;
    	}
    	$tempSources = array_merge($tempSources, $chunk->sources);
    	$freshTempTargets = array_merge($freshTempTargets, $chunk->targets);
    }
    foreach($tempTargets as $tmpTarget){
    	unset($tempTargets[array_search($tmpTarget, $tempTargets)]);
    	$chunk = buildChunk($tmpTarget);
    	$chunk->level = $i;
    	$chunk->sources = getSourcesFromTarget($tmpTarget);
    	$chunk->targets = getTargetsFromSource($tmpTarget);
    	if(!in_array($chunk->id, $collectedIds)) {
    		 $chunks[] = $chunk;
    		 $collectedIds[] = $chunk->id;
    	}
    	$tempSources = array_merge($tempSources, $chunk->sources);
    	$tempTargets = array_merge($tempTargets, $chunk->targets);
    }
    $tempTargets = array_merge($tempTargets, $freshTempTargets);
}

if($SESSION->dasis_webprefs[$SESSION->dasis_blockId]->adaption && isset($solutions)) $chunks = set_chunks_color_by_appliance($solutions, $chunks, $target);

//print_r($chunks);
?>

	<link rel="stylesheet" type="text/css" href="semanticweb.css"/>
	<script type="text/javascript" src="./js/protovis.min_mybounds.js"></script>
	<script type="text/javascript">
	/*
	 * Get data into chunks object containing nodes and links
	 */
		var chunks = {
				nodes:[
					<?php foreach($chunks as $node){
							if(!$node->shortname){
								$node->shortname = $node->label;
							}
							echo "{label:\"$node->label\", shortname:\"$node->shortname\", id:$node->id, level:$node->level, url:\"$node->url\", color:\"$node->color\", shape:\"$node->shape\"},\n";} ?>
				],
				links:[
					<?php
						$tmpLinks = array();
						foreach($chunks as $link){
							foreach($link->sources as $lsource){
								if(array_search($lsource, $collectedIds) !== FALSE && array_search($lsource."_".$link->id, $tmpLinks) === FALSE){
									echo "{source:".array_search($lsource, $collectedIds).", target:".array_search($link->id, $collectedIds).", relcolor:\"".get_relation_color($lsource, $link->id)."\"},\n";
									$tmpLinks[] = $lsource."_".$link->id;
								}
							}
							
							
							foreach($link->targets as $ltarget){
								if(array_search($ltarget, $collectedIds) !== FALSE && array_search($link->id."_".$ltarget, $tmpLinks) === FALSE){
									echo "{source:".array_search($link->id, $collectedIds).", target:".array_search($ltarget, $collectedIds).", relcolor:\"".get_relation_color($link->id, $ltarget)."\"},\n";
									$tmpLinks[] = $link->id."_".$ltarget;
								}
							}
						}
					?>
				]
		};
		
		<?php
			if($SESSION->dasis_webprefs[$SESSION->dasis_blockId]->web_animation){
				echo "var webanimation = true";
			}else{
				echo "var webanimation = false";
			}
		 ?>
	</script>

	<div id="semanticweb"></div>
	<div id="caption">
		<?php
			$sql = "SELECT p.id as id, p.name as title, p.color as color, b.name as bundle, b.description as description FROM {dasis_learning_paths} p ".
					"LEFT JOIN {dasis_bundle_connections} bc ON p.bundle_id = bc.bundle_id ".
					"LEFT JOIN {dasis_bundles} b ON p.bundle_id = b.id ".
					"WHERE bc.course_id = $course_id";
			$pathInfo = $DB->get_records_sql($sql);
			echo "<p><a id=\"reload_web\" href=\"{$CFG->wwwroot}/blocks/semantic_web/SemanticWeb/semanticweb.php\">".get_string('reloadweb', 'block_semantic_web')."</a></p>";
			if(count($pathInfo)) {
				echo "<b><u>".get_string("caption", "block_semantic_web")."</u></b>";
				echo "<ul>";
				foreach($pathInfo as $pi){
					echo "<li class=\"captionPath\" id=\"{$pi->id}\"><span style=\"color:{$pi->color}\"><b>{$pi->title}</b></span><span class=\"description\">{$pi->description}</span> ({$pi->bundle})</li>";
				}
				echo "</ul>";
			}
		?>
	</div>
	<script type="text/javascript+protovis">
			
			// Kann man die Größe nicht auch ohne Rand hinbekommen?! (also 100% und keine Scrollbalken)
			var w = parent.document.documentElement.clientWidth,
			    h = 0.98*parent.document.documentElement.clientHeight;
			
			// fix the central node in the middle of the screen
			chunks.nodes[0].fix = new pv.Vector(w/2, h/2);
			
			var vis = new pv.Panel().canvas("semanticweb")
				.def("nodeIndex", -1)
			    .width(w)
			    .height(h)
			    .fillStyle("transparent")
			    .event("mousedown", pv.Behavior.pan())
			    .event("mousewheel", pv.Behavior.zoom());
						
			var force = vis.add(pv.Layout.Force)
			    .nodes(chunks.nodes)
			    .links(chunks.links)
			    .springLength(130)
			    //.springConstant(0.2)
			    .springDamping(0.5)
			    .chargeConstant(-5000)
			    //.dragConstant(0.3)
			    .iterations(function() webanimation==true ? null : 2000)
			    .bound(true);
			    			
			force.link.add(pv.Line)
				.strokeStyle(function(d, l) l.relcolor=="none" ? "gray" : l.relcolor);
			
			force.node.add(pv.Dot)
			    .radius(function(d) 40/(1+d.level/3))
			    .fillStyle(function(d) this.index==vis.nodeIndex() ? "purple" : pv.color(d.color).brighter(Math.log(1+d.level)))
			    .strokeStyle(function() this.fillStyle().darker())
			    .lineWidth(1)
			    .shape(function(d) d.shape)
			    .cursor("pointer")
    			.title(function(d) d.label)
			    .event("mousedown", function() vis.nodeIndex(this.index))
			    .event("mouseup", function(d){parent.location=d.url; vis.nodeIndex(-1)});
			    
			force.label.add(pv.Label)
				.font(function(d) 20/(1+d.level/3) + "px sans-serif")
			    .text(function(d) d.shortname)
			    .textStyle("darkblue");
						    
			vis.render();
    </script>
<?php
echo $OUTPUT->footer();