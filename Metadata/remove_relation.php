<?php 
	/*
	 * Dieses Skript entfernt eine Relation aus der Tabelle dasis_relations
	 * 
	 * @autor	Andre Scherl
	 * @version	1.0 - 09.09.2010
	 *
	 * Copyright (C) 2012, Andre Scherl
	 * You should have received a copy of the GNU General Public License
	 * along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
	 * 
	 * @param $id		(id des Moduls für den redirect-Befehl)
	 * @param $relid	(id der zu löschenden Relation)
	 */

	require_once("../../../config.php");

	$id = optional_param('id', 0, PARAM_INT);
	$relid = optional_param('relid', 0, PARAM_INT);

	if($relid) {
		$DB->delete_records("dasis_relations", array("id" => $relid));
	}
	
	redirect("edit_metadata.php?id=$id");
?>