<?php
/*
 * Copyright (C) 2012, Andre Scherl
 * You should have received a copy of the GNU General Public License
 * along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
 */
	///// Blockeinstellungen
	$string['blockname'] = 'Navigation Web';
	$string['pluginname'] = 'Navigation Web';
	$string['configcontent'] = 'Edit Content';
	$string['connectedcourses'] = 'Connected Courses';
	$string['edit_metadata'] = 'Edit Metadata';
	$string['showweb'] = 'Show Web';
	$string['semanticweb_settings'] = 'Settings of the Navigation Web';
	$string['depth'] = 'Linkage Depth';
	$string['case_collection'] = 'Collect Cases';
	$string['web_animation'] = 'Animate Web';
	
	$string['bundle_settings'] = 'Settings of Course Bundles';
	$string['bundles_containing_the_course'] = 'Course contained in the following bundles';
	$string['add_course_to_bundle'] = 'Add course to bundle';
	$string['newbundle'] = 'Create new bundle';
	$string['name_of_bundle'] = 'Name of bundle';
	$string['create_bundle'] = 'Create bundle';
	$string['contained_courses'] = 'Contained courses';
	$string['add_course_to_bundle'] = 'Add the following course to bundle';
	$string['add_course_to_this_bundle'] = 'Add the following course to this bundle';
	$string['bundle_overview'] = 'Bundle Overview';
	$string['bundle'] = 'Bundle';
	$string['click_enlarge'] = 'Click to enlarge!';
	$string['click_hide'] = 'Click to hide!';
	
	$string['learning_path_settings'] = 'Learning Path Settings';
	$string['learning_pathname'] = 'Name of learning path';
	$string['learning_path'] = 'learning path';
	$string['path_name'] = 'path name';
	$string['create_new_path'] = 'Create new path';
	$string['new_pathnode'] = 'Add node to path';
	
	///// Allgemeines
	$string['add'] = 'Add';
	$string['cancel'] = 'Cancel';
	$string['submitclose'] = 'Submit and close';
	$string['submit'] = 'Submit';
	$string['remove'] = 'Remove';
	$string['back'] = 'Back';
	$string['next'] = 'Next';
	$string['about'] = 'About...';
	$string['about_help'] = "<p>This navigation web is part of the moodle extension DASIS.\nThis navigation support should guide you through cross curricular learning.</p><p>The moodle-extension DASIS consists of the following parts:<ul><li>block: Navigation Web</li><li>block: Learner adaptation (works in background)</li><li>activities: tests of learning style</li><li>block: User Preferences</li></ul></p><p>DASIS is developed by Andre Scherl for his PhD-thesis. The moodle plugin 'iLMS' by Gert Sauertstein (2007)was adapted to the needs of DASIS and also the visualization framework 'protovis' made by Univertity of Stanford was used to generate the navigation web.</p>";
	
	$string['pleaseselect'] = 'Please select';
	$string['activity_pleaseselect'] = 'Select activity to edit.';
	$string['module'] = 'Module';
	
	///// Zugriffsberechtigungen
	$string['semantic_web:editmetadata'] = 'Edit metadata of activity';
	$string['semantic_web:deletebundle'] = 'Delete bundle';
	$string['semantic_web:managepaths'] = 'Manage learning paths';
	
	///// Semantische Relationen
	$string['relations'] = 'Semantic Relations';
	$string['relation_pleaseselectsource'] = 'Please select activity.';
	$string['relation_pleaseselect'] = 'Please select relation.';
	$string['relation_pleaseselecttarget'] = 'Please select activity.';
	$string['relation_vertieft']='vertieft';
	$string['relation_vertieft']='goes into detail for';
	$string['relation_erlaeutert']='explains';
	$string['relation_beispiel']='is an example for';
	$string['relation_anwendung']='is an application for';
	$string['relation_illustriert']='is an illustration for';
	$string['relation_querverweis']='is linked with';
	$string['relation_exkurs']='is additional to';
	$string['relation_fasstzusammen']='summarizes';
	$string['relation_bautauf']='extends';
	$string['relation_wiederholt']='repeats';
	$string['relation_setztvoraus']='requires';
	$string['relation_prueft']='is a test for';
	$string['show_semantic_overview'] = 'Relations Overview';
	
	///// Eigenschaften der Lernaktivitäten
	$string['title'] = 'Title';
	$string['shortname'] = 'Short Name';
	$string['description'] = 'Description';
	$string['keywords'] = 'Keywords';
	$string['learning_tasks'] = 'Learning Tasks';
	$string['taxonomy'] = 'Taxonomy';
	$string['taxonomy_example'] = 'e.g. physics:optics:instruments:magnifier';
	$string['learning_time'] = 'Expected Learning Time (in min.)';
	$string['catalog'] = 'Catalog No. (ISSN, ISBN,...)';
	
	// Schwierigkeitsgrad
	$string['difficulty'] = 'Difficulty';
	$string['difficulty_verylow'] = 'very easy';
	$string['difficulty_low'] = 'easy';
	$string['difficulty_normal'] = 'average';
	$string['difficulty_high'] = 'difficult';
	$string['difficulty_veryhigh'] = 'very difficult';
	
	// Sprachlicher, matematisch-logischer Anspruch
	$string['linguistic_requirement'] = 'Linguistic Requirements';
	$string['linguistic_requirement_verylow'] = 'very low';
	$string['linguistic_requirement_low'] = 'low';
	$string['linguistic_requirement_normal'] = 'average';
	$string['linguistic_requirement_high'] = 'high';
	$string['linguistic_requirement_veryhigh'] = 'very high';
	
	$string['logical_requirement'] = 'Mathematic-Logical Requirements';
	$string['logical_requirement_verylow'] = 'very low';
	$string['logical_requirement_low'] = 'low';
	$string['logical_requirement_normal'] = 'average';
	$string['logical_requirement_high'] = 'high';
	$string['logical_requirement_veryhigh'] = 'very high';
	
	// Sozialer Anspruch (Bearbeitungsmodus)
	$string['social_requirement'] = 'Learning Mode';
	$string['social_requirement_verylow'] = 'independent';
	$string['social_requirement_low'] = 'rather independent than collaborative';
	$string['social_requirement_normal'] = 'mixed';
	$string['social_requirement_high'] = 'collaborative';
	$string['social_requirement_veryhigh'] = 'group work';
	
	///// Lernstileigenschaften der Lernäktivität
	// Lerntypeigenschaft Wahrnehmung bzw. Lerninhaltseigenschaft Inhalt
	$string['learningstyle_perception'] = 'Content';
	$string['learningstyle_perception_verylow'] = 'concrete';
	$string['learningstyle_perception_low'] = 'rather concrete tha abstract';
	$string['learningstyle_perception_normal'] = 'both equal';
	$string['learningstyle_perception_high'] = 'rather abstract than concrete';
	$string['learningstyle_perception_veryhigh'] = 'abstract';
	
	// Lerntypeigenschaft Vorgehensweise bzw. Lerninhaltseigenschaft Oragnisation
	$string['learningstyle_organization'] = 'Organization';
	$string['learningstyle_organization_verylow'] = 'mostly deductive';
	$string['learningstyle_organization_low'] = 'rather deductive than inductive';
	$string['learningstyle_organization_normal'] = 'mixed equally';
	$string['learningstyle_organization_high'] = 'rather inductive than deductive';
	$string['learningstyle_organization_veryhigh'] = 'mostly inductive';
	
	// Lerntypeigenschaft Sichtweise bzw. Lerninhaltseigenschaft Perspektive/Aufbau
	$string['learningstyle_perspective'] = 'Perspective';
	$string['learningstyle_perspective_verylow'] = 'mostly sequential';
	$string['learningstyle_perspective_low'] = 'rather sequential than global';
	$string['learningstyle_perspective_normal'] = 'mixed equally';
	$string['learningstyle_perspective_high'] = 'rather global than sequential';
	$string['learningstyle_perspective_veryhigh'] = 'mostly global';
	
	// Lerntypeigenschaft Aufnahme bzw. Lerninhaltseigenschaft Präsentationsform
	$string['learningstyle_input'] = 'Presentation';
	$string['learningstyle_input_verylow'] = 'mostly visual';
	$string['learningstyle_input_low'] = 'rather visual than verbal';
	$string['learningstyle_input_normal'] = 'mixed equally';
	$string['learningstyle_input_high'] = 'rather verbal than visual';
	$string['learningstyle_input_veryhigh'] = 'mostly verbal';
	
	// Lerntypeigenschaft Interaktivitätstyp
	$string['learningstyle_processing'] = 'Interactivity Type';
	$string['learningstyle_processing_verylow'] = 'active';
	$string['learningstyle_processing_low'] = 'rather active than expositive';
	$string['learningstyle_processing_normal'] = 'both equal';
	$string['learningstyle_processing_high'] = 'rather expositive than active';
	$string['learningstyle_processing_veryhigh'] = 'expositive';
	
	///// Semantisches Netz
	$string['reloadweb'] = 'Reload web!';
	$string['choose_bundle'] = "Choose topic bundle";
	$string['select_path'] = 'Select learning path';
	$string['caption'] = 'Caption';
	$string['adaptive_path'] = 'Adaptive path';
	$string['noweb'] = 'no navigation web for this section';
	
	///// Farben
	$string['color'] = "Color";
	$string['red'] = "red";
	$string['orange'] = "orange";
	$string['yellow'] = "yellow";
	$string['green'] = "green";
	$string['blue'] = "blue";
	$string['purple'] = "purple";
	
	