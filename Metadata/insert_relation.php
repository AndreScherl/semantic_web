<?php 
	/**
	 * Dieses Skript fügt der Tabelle dasis_relations eine Relation hinzu
	 * 
	 * @autor Andre Scherl
	 * @date 02.07.2010
	 *
 	 * Copyright (C) 2012, Andre Scherl
 	 * You should have received a copy of the GNU General Public License
 	 * along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
	 * 
	 * @param $id	id des Moduls für den redirect-Befehl
	 * @param $sid	source id
	 * @param $tid	target id
	 * @param $rel	Art der Relation
	 */

	require_once("../../../config.php");
	
	global $DB;

	$id = optional_param('id', 0, PARAM_INT);
	$sid = optional_param('sid', 0, PARAM_INT);
	$tid = optional_param('tid', 0, PARAM_INT);
	$rel = optional_param('rel', 'relation_bautauf', PARAM_TEXT);
	
	if($sid && $tid) {
		$data = new object();
		$data->source = $sid;
		$data->target = $tid;
		$data->type = $rel;
		if(!$DB->record_exists("dasis_relations", array("source" => $sid, "target" => $tid, "type" => $rel))){
			$DB->insert_record("dasis_relations", $data);
		}
	}
	
	redirect("edit_metadata.php?id=$id");
?>