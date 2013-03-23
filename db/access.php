<?php 
	/**
	 * Definieren den Berechtigungen für den semantic_web-Block
	 * 
	 * @package	DASIS -> Semantic Web
	 * @author	Andre Scherl
	 * @version	1.2 - 21.08.2012
	 */

	$capabilities = array(
		'block/semantic_web:addinstance' => array(

            'captype' => 'read',
            'contextlevel' => CONTEXT_SYSTEM,
            'legacy' => array(
                'guest' => CAP_PREVENT,
                'student' => CAP_PREVENT,
                'teacher' => CAP_PREVENT,
                'editingteacher' => CAP_PREVENT,
                'admin' => CAP_ALLOW
            )
        ),
		
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
								'admin'					=> CAP_ALLOW
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
								'admin'					=> CAP_ALLOW
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
								'admin'					=> CAP_ALLOW
								)
		)
	);

?>