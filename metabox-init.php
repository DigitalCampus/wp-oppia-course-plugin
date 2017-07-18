<?php
// aa_tables metabox _repeatable
$aa_tablesMB = new VP_Metabox(array(
	'id'          => 'course_version_mb',
	'types'       => array('oppia_course'),
	'title'       => __('Versions', 'Oppia_Course'),
	'priority'    => 'high',
	'template'    => array(
       			array(
					'name'      => 'table_group',
					'type'      => 'group',
					'sortable' => true,
					'repeating' => true,
					'title'     => __('Version', 'Oppia_Course'),
					'fields'    => array(
							    	array(
										'name'    => 'aa_tbl_prpty',
										'type'    => 'textbox',
										'mode'    => WPALCHEMY_MODE_EXTRACT,
										'label'   => __('Version', 'Oppia_Course'),
										'description' => __('Name to display for this version', 'Oppia_Course'),
										'default' => '',
								    ),
	
								    array(
										'name'    => 'aa_tbl_val',
										'type'    => 'textbox',
										'mode'    => WPALCHEMY_MODE_EXTRACT,
										'label'   => __('Url', 'Oppia_Course'),
										'description' => __('Full URL to the Moodle/Oppia course', 'Oppia_Course'),
										'default' => '',
								    ),
					),
				),
	),
));
?>