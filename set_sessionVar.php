<?php 
	/**
	 * add variables to moodles session object
	 * 
	 * @package	DASIS -> Semantic Web
	 * @author	Andre Scherl
	 * @version	1.1 - 17.06.2011
	 *
	 * Copyright (C) 2012, Andre Scherl
	 * You should have received a copy of the GNU General Public License
	 * along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
	 */
	
	error_reporting(E_ALL);
	 
	require_once("../../config.php");
	
	$key = required_param("key", PARAM_TEXT);
	$value = required_param("value", PARAM_TEXT);
	
	$SESSION->$key = $value;
	echo $SESSION->$key;
?>