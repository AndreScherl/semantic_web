<?php
/**
 * Dieses Skript gibt nur die Session Vars aus, um sie in JavaSript zu importieren
 *
 * @package	DASIS->SemanticWeb
 * @author	Andre Scherl
 * @version	1.3 - 22.08.2011
 *
 * Copyright (C) 2012, Andre Scherl
 * You should have received a copy of the GNU General Public License
 * along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once("../../config.php");
require_once("semantic_web_lib.php");

global $SESSION;

/**
 * Einstellungen für Funktion und Erscheinungsbild der Semantic Web Blocks
 */

$sessionvars = array(
	"activityId" => $SESSION->dasis_activityId,
	"blockId" => $SESSION->dasis_blockId,
	"depth" => $SESSION->dasis_webprefs[$SESSION->dasis_blockId]->depth,
	"adaption" => $SESSION->dasis_webprefs[$SESSION->dasis_blockId]->adaption,
	"courseHasBundle" => $SESSION->dasis_courseHasBundle,
	"bundleHasPath" => $SESSION->dasis_bundleHasPath,
	"partOfWeb" => $SESSION->dasis_partOfWeb,
	"case_collection" => $SESSION->dasis_webprefs[$SESSION->dasis_blockId]->case_collection,
	"web_animation" => $SESSION->dasis_webprefs[$SESSION->dasis_blockId]->web_animation,
	"path" => $SESSION->dasis_selectedPath
); 

print(json_encode($sessionvars));

?>