<?php
// aa_tables metabox _repeatable
$aa_tablesMB = new VP_Metabox(array(
	'id'          => 'aa_tbl_mb',
	'types'       => array('oppia_course'),
	'title'       => __('Versions', 'Oppia_Course'),
	'priority'    => 'high',
	'template'    => array(
       			array(
					'name'      => 'table_group',
					'type'      => 'group',
					'repeating' => true,
					'title'     => __('New version', 'Oppia_Course'),
					'fields'    => array(
							    	array(
										'name'    => 'aa_tbl_prpty',
										'type'    => 'textbox',
										'mode'    => WPALCHEMY_MODE_EXTRACT,
										'label'   => __('Version', 'Oppia_Course'),
										'default' => 'ethiopian version',
								    ),
	
								    array(
										'name'    => 'aa_tbl_val',
										'type'    => 'textbox',
										'mode'    => WPALCHEMY_MODE_EXTRACT,
										'label'   => __('Url', 'Oppia_Course'),
										'default' => '',
								    ),
					),
				),
	),
));
?>