<?php 
/**
* This file builds the semantic web of learning contents by working with metadata stored in database tables
* "dasis_modmeta" and "dasis_relations".
* The "protovis"-library developed by university of stanford is used to visualize the semantic web.
*
* @package	DASIS -> Semantic Web -> Semantic Web
* @author	Andre Scherl
* @version	1.3 - 23.07.2011
*
* Copyright (C) 2012, Andre Scherl
* You should have received a copy of the GNU General Public License
* along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
*/

require_once("../../../config.php");
require_once("../semantic_web_lib.php");

$target = $SESSION->dasis_activityId;


// Das Netz hat $RANGE Ebenen
$RANGE = $SESSION->userDepth;
	
// Wenn die akteulle Lernaktivität nicht im Netz enthalten ist, soll die Ausführung abgebrochen werden und es wird "Kein Netz für die aktuelle Station" angezeigt.
if(!$DB->record_exists_select("dasis_relations", "source = $target OR target = $target")) {
	p(get_string("noweb", "block_semantic_web"));
} else {

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
	for($i=1; $i<=$RANGE; $i++){
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
?>

<!DOCTYPE html>
<html lang="de">
<head>
	<meta charset="utf-8">
	<title>Semantische Netznavigation</title>
	
	<link src="semanticweb.css" style="text/css"/>
	
	<script type="text/javascript" src="./js/protovis.min.js"></script>
	<script type="text/javascript">
	/*
	 * Get data into chunks object containing nodes and links
	 */
		var chunks = {
				nodes:[
					<?php foreach($chunks as $node){
							echo "{label:\"".$node->label."\", id:".$node->id.", level:".$node->level.", url:\"".$node->url."\", color:\"".$node->color."\"},\n";} ?>
				],
				links:[
					<?php
						$tmpLinks = array();
						foreach($chunks as $link){
							foreach($link->sources as $lsource){
								if(array_search($lsource, $collectedIds) !== FALSE && array_search($lsource."_".$link->id, $tmpLinks) === FALSE){
									echo "{source:".array_search($lsource, $collectedIds).", target:".array_search($link->id, $collectedIds)."},\n";
									$tmpLinks[] = $lsource."_".$link->id;
								}
							}
							
							
							foreach($link->targets as $ltarget){
								if(array_search($ltarget, $collectedIds) !== FALSE && array_search($link->id."_".$ltarget, $tmpLinks) === FALSE){
									echo "{source:".array_search($link->id, $collectedIds).", target:".array_search($ltarget, $collectedIds)."},\n";
									$tmpLinks[] = $link->id."_".$ltarget;
								}
							}
						}
					?>
				]
		};
	</script>
</head>
<body>
	<script type="text/javascript+protovis">
			
			var w = 150,
			    h = 150;
			
			//var w = parent.document.getElementById("id_miniWeb").width,
			//	h = parent.document.getElementById("id_miniWeb").height;
			
			// fix the central node in the middle of the screen
			chunks.nodes[0].fix = new pv.Vector(w/2, h/2);
			
			var vis = new pv.Panel()
				.width(w)
			    .height(h);
			    //.fillStyle("red");
						
			var force = vis.add(pv.Layout.Force)
			    .nodes(chunks.nodes)
			    .links(chunks.links)
			    .springLength(30)
			    //.springConstant(0.9)
			    .springDamping(0.5)
			    .chargeConstant(-500)
			    //.dragConstant(0.3)
			    //.iterations(2000)
			    .bound(true);
			    			
			force.link.add(pv.Line)
				.strokeStyle("darkgreen");
			
			force.node.add(pv.Dot)
			    .radius(function(d) 8/(1+d.level/3))
			    .fillStyle(function(d) pv.color(d.color).brighter(d.level))
			    .strokeStyle(function() this.fillStyle().darker())
			    .title(function(d) d.label)
			    .lineWidth(1);
						    
			vis.render();
    </script>
</body>
</html>
<?php } //schließen der else-Schleife ?>