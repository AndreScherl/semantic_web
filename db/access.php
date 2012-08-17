<?php 
	/**
	 * Definieren den Berechtigungen für den semantic_web-Block
	 * 
	 * @package	DASIS -> Semantic Web
	 * @author	Andre Scherl
	 * @version	1.1 - 23.11.2010
	 */

	$capabilities = array(
		'block/semantic_web:editmetadata' => array(
			'captype'		=> 'write',
			'contextlevel'	=> CONTEXT_MODULE,
			'legacy'		=> array(
								// Standardrolle von Moodle
								'guest'					=> CAP_PREVENT,
								'student'				=> CAP_PREVENT,
								'teacher'				=> CAP_PREVENT,
								'editingteacher'		=> CAP_ALLOW,
								'coursecreator'			=> CAP_ALLOW,
								'admin'					=> CAP_ALLOW,
								// LMU-eigene Rollen
								'modulverantwortliche'	=> CAP_ALLOW,
								'moduldozent'			=> CAP_ALLOW,
								'modulsekretariat'		=> CAP_PREVENT,
								'dozent'				=> CAP_ALLOW,
								'gastteilnehmer'		=> CAP_PREVENT,
								'user'					=> CAP_PREVENT,
								'user_1'				=> CAP_PREVENT,
								'modulsekretariat_1'	=> CAP_PREVENT
								)
		),
		
		'block/semantic_web:deletebundle' => array(
			'captype'		=> 'write',
			'contextlevel'	=> CONTEXT_MODULE,
			'legacy'		=> array(
								// Standardrolle von Moodle
								'manager'				=> CAP_PROHIBIT,
								'guest'					=> CAP_PROHIBIT,
								'student'				=> CAP_PROHIBIT,
								'teacher'				=> CAP_PROHIBIT,
								'editingteacher'		=> CAP_PROHIBIT,
								'coursecreator'			=> CAP_PROHIBIT,
								'admin'					=> CAP_ALLOW,
								// LMU-eigene Rollen
								'modulverantwortliche'	=> CAP_PREVENT,
								'moduldozent'			=> CAP_PREVENT,
								'modulsekretariat'		=> CAP_PREVENT,
								'dozent'				=> CAP_PREVENT,
								'gastteilnehmer'		=> CAP_PREVENT,
								'user'					=> CAP_PREVENT,
								'user_1'				=> CAP_PREVENT,
								'modulsekretariat_1'	=> CAP_PREVENT
								)
		),
		
		'block/semantic_web:managepaths' => array(
			'captype'		=> 'write',
			'contextlevel'	=> CONTEXT_MODULE,
			'legacy'		=> array(
								// Standardrolle von Moodle
								'manager'				=> CAP_PROHIBIT,
								'guest'					=> CAP_PROHIBIT,
								'student'				=> CAP_PROHIBIT,
								'teacher'				=> CAP_PROHIBIT,
								'editingteacher'		=> CAP_PROHIBIT,
								'coursecreator'			=> CAP_PROHIBIT,
								'admin'					=> CAP_ALLOW,
								// LMU-eigene Rollen
								'modulverantwortliche'	=> CAP_PREVENT,
								'moduldozent'			=> CAP_PREVENT,
								'modulsekretariat'		=> CAP_PREVENT,
								'dozent'				=> CAP_PREVENT,
								'gastteilnehmer'		=> CAP_PREVENT,
								'user'					=> CAP_PREVENT,
								'user_1'				=> CAP_PREVENT,
								'modulsekretariat_1'	=> CAP_PREVENT
								)
		)
	);

?>