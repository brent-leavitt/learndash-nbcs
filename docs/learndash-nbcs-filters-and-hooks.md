WordPress Core Filters
File	Line	Hook Name	Callback
app\clss\tables\list_table.class.php	157	manage_{$this->screen->id}_columns	array( $this, 'get_columns' ), 0
app\func\admin_func.php	176	parent_file	Doula_Course\App\Func\filter_selected_asmt_submenu
app\func\email.php	14	wp_mail_from	function( $name ) { ... }
app\func\email.php	18	wp_mail_from_name	function( $name ) { ... }
app\func\helper.php	105	index_rel_link	\disable_stuff
app\func\helper.php	106	parent_post_rel_link	\disable_stuff
app\func\helper.php	107	start_post_rel_link	\disable_stuff
app\func\helper.php	108	previous_post_rel_link	\disable_stuff
app\func\helper.php	109	next_post_rel_link	\disable_stuff
app\func\login.php	15	login_headerurl	Doula_Course\App\Func\custom_login_header_url
app\func\post_status.php	135	display_post_states	Doula_Course\App\Func\cpt_overview_status
app\func\post_types.php	95	wp_revisions_to_keep	Doula_Course\App\Func\revision_limit, 10, 2
app\func\post_types.php	131	get_shortlink	Doula_Course\App\Func\cpt_shortlinks, 10, 4
app\func\post_types.php	178	wp_insert_post_data	Doula_Course\App\Func\nb_assignment_comments_on
app\func\post_types.php	265	views_edit-assignment	Doula_Course\App\Func\edit_assignments_views, 10, 1
app\func\query_vars.php	13	query_vars	Doula_Course\App\Func\queryVar
app\func\roles.php	234	ajax_query_attachments_args	Doula_Course\App\Func\nb_user_show_attachments
LearnDash Filters
File	Line	Hook Name	Callback
app\func\admin_metaboxes.php	795	learndash_course_grid_post_types	Doula_Course\App\Func\exclude_post_types_from_ld_course_grid
Restrict Content Pro (RCP) Filters
File	Line	Hook Name	Callback
app\func\admin_metaboxes.php	771	rcp_metabox_excluded_post_types	Doula_Course\App\Func\exclude_post_types_from_rcp
app\func\email.php	31	rcp_email_templates	Doula_Course\App\Func\nb_rcp_email_templates, 10, 1
app\func\register.php	99	rcp_registration_username_label	Doula_Course\App\Func\nb_reg_username_label, 10
app\func\register.php	109	rcp_registration_email_label	Doula_Course\App\Func\nb_reg_email_label, 10
app\func\register.php	120	rcp_registration_firstname_label	Doula_Course\App\Func\nb_reg_firstname_label, 10
app\func\register.php	129	rcp_registration_lastname_label	Doula_Course\App\Func\nb_reg_lastname_label, 10
app\func\register.php	368	rcp_can_renew_deactivated_membership_levels	__return_true
Other/Theme Filters
File	Line	Hook Name	Callback
app\func\admin_metaboxes.php	818	kadence_classic_meta_box_post_types	Doula_Course\App\Func\exclude_post_types_from_kadence

WordPress Core Hooks
File	Line	Hook Name	Callback
app\clss\course.php	34	init	array( $this, 'init' )
app\func\admin_course.php	49	save_post	Doula_Course\App\Func\update_assignments_map, 50
app\func\admin_dashboard.php	35	wp_dashboard_setup	Doula_Course\App\Func\add_admin_dashboard_widgets
app\func\admin_dashboard.php	182	admin_post_message_dismiss	Doula_Course\App\Func\dismiss_message
app\func\admin_func.php	151	admin_menu	Doula_Course\App\Func\build_admin_menus, 90
app\func\admin_func.php	247	admin_menu	Doula_Course\App\Func\admin_menu_spacers
app\func\admin_func.php	277	admin_enqueue_scripts	Doula_Course\App\Func\nb_add_admin_style
app\func\admin_metaboxes.php	100	add_meta_boxes	Doula_Course\App\Func\admin_meta_boxes
app\func\admin_metaboxes.php	446	save_post	Doula_Course\App\Func\save_asmt_meta_box_data
app\func\admin_metaboxes.php	498	save_post	Doula_Course\App\Func\save_asmt_meta_box_data
app\func\admin_metaboxes.php	539	post_submitbox_misc_actions	Doula_Course\App\Func\material_type_select
app\func\admin_metaboxes.php	566	save_post	Doula_Course\App\Func\save_material_type_select
app\func\admin_metaboxes.php	623	save_post	Doula_Course\App\Func\save_assessment_rubric_callback
app\func\admin_metaboxes.php	705	admin_enqueue_scripts	Doula_Course\App\Func\load_track_courses_script
app\func\admin_metaboxes.php	753	wp_ajax_track_courses_action	Doula_Course\App\Func\do_track_courses_action
app\func\admin_metaboxes.php	846	transition_post_status	Doula_Course\App\Func\nb_record_completed_in_learndash, 10, 3
app\clss\processors\assignments\delete_submission.class.php	74	delete_post	array( $this, 'remove_assignment_grade' ), 10, 2
app\clss\tables\list_table.class.php	170	admin_footer	array( $this, '_js_vars' )
app\func\login.php	13	login_enqueue_scripts	wp_print_styles, 11
app\func\login.php	28	login_head	Doula_Course\App\Func\custom_styles
app\func\pages.php	32	wp_enqueue_scripts	(closure for enqueuing doula-course-styles)
app\func\post_status.php	49	init	Doula_Course\App\Func\register_post_statuses
app\func\post_status.php	90	admin_init	Doula_Course\App\Func\enable_custom_status_comments
app\func\post_status.php	406	pre_get_posts	Doula_Course\App\Func\list_assignments_query_args, 10
app\func\post_status.php	435	restrict_manage_posts	Doula_Course\App\Func\nb_assignment_trainers_dropdown
app\func\post_types.php	73	init	Doula_Course\App\Func\register_post_types
app\func\query_vars.php	12	template_redirect	Doula_Course\App\Func\queryVarsListener
app\func\widget.php	11	widgets_init	(closure for registering Student_Progress widget)
app\func\widget.php	45	template_redirect	Doula_Course\App\Func\set_course_bookmark
Restrict Content Pro (RCP) Hooks
File	Line	Hook Name	Callback
app\func\register.php	198	rcp_after_password_registration_field	Doula_Course\App\Func\nb_reg_extra_user_fields
app\func\register.php	248	rcp_profile_editor_after	Doula_Course\App\Func\nb_profile_extra_user_fields
app\func\register.php	314	rcp_form_errors	Doula_Course\App\Func\nb_validate_user_fields_on_register, 10
app\func\register.php	349	rcp_form_processing	Doula_Course\App\Func\nb_save_user_fields_on_register, 10, 2
app\func\roles.php	196	rcp_transition_membership_status	Doula_Course\App\Func\nb_set_inactive_when_role_not_set, 20, 3
app\func\roles.php	231	rcp_transition_membership_status	Doula_Course\App\Func\nb_reactivated_status, 20, 3
app\func\student_meta.php	100	rcp_membership_post_activate	Doula_Course\App\Func\assign_student_trainer, 10, 2
Custom Plugin Hooks
File	Line	Hook Name	Callback
app\clss\course.php	33	doula_course_activate	array( $this, 'activate' )
app\func\activate.php	38	doula_course_activate	Doula_Course\App\Func\activate_doula_course
app\func\roles.php	150	doula_course_activate	Doula_Course\App\Func\add_roles
app\func\roles.php	152	doula_course_deactivate	Doula_Course\App\Func\remove_roles
app\func\crons.php	14	nb_test_cron_hook	\Doula_Course\App\Func\test_cron_function