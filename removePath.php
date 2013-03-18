<?php 
	/**
	 * remove learning path from database
	 * 
	 * @package	DASIS -> Semantic Web
	 * @author	Andre Scherl
	 * @version	1.0 - 03.12.2010
	 *
	 * Copyright (C) 2012, Andre Scherl
	 * You should have received a copy of the GNU General Public License
	 * along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
	 */

	require_once("../../config.php");
	
	$pathId = optional_param("pathId", 0, PARAM_INT);
	
	if($pathId > 0){
		$DB->delete_records("dasis_learning_paths", array("id" => $pathId));
	}
	
?>