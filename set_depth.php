<?php 
	/**
	 * set web depth to moodles session object
	 * 
	 * @package	DASIS -> Semantic Web
	 * @author	Andre Scherl
	 * @version	1.0 - 14.10.2011
	 *
	 * Copyright (C) 2012, Andre Scherl
	 * You should have received a copy of the GNU General Public License
	 * along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
	 */
	
	error_reporting(E_ALL);

	require_once("../../config.php");
	
	$value = required_param("value", PARAM_TEXT);
	
	$SESSION->dasis_webprefs[$SESSION->dasis_blockId]->depth = $value;
	
	echo $SESSION->dasis_webprefs[$SESSION->dasis_blockId]->depth;
?>