<?php 
	/**
	 * store the course-to-bundle connection in table dasis_bundle_connections
	 * 
	 * @package	DASIS -> Semantic Web
	 * @author	Andre Scherl
	 * @version	1.0 - 19.11.2010
	 *
	 * Copyright (C) 2012, Andre Scherl
	 * You should have received a copy of the GNU General Public License
	 * along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
	 */

	require_once("../../config.php");
	
	$addBundleId = optional_param("addToBundle", 0, PARAM_INT);
	$courseId = optional_param("courseid", 0, PARAM_INT);
	$bundleId = optional_param("bundleId", 0, PARAM_INT);
	$newCourseId = optional_param("addCourse", 0, PARAM_INT);
	
	if($addBundleId && $courseId) {
		$connection->bundle_id = $addBundleId;
		$connection->course_id = $courseId;
		$DB->insert_record("dasis_bundle_connections", $connection);
	}
	
	if($bundleId && $newCourseId){
		$connection->bundle_id = $bundleId;
		$connection->course_id = $newCourseId;
		$DB->insert_record("dasis_bundle_connections", $connection);
	}
	
	redirect($_POST["currenturl"]);
?>