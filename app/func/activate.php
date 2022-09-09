<?php

namespace Doula_Course\App\Func;

if ( !defined( 'ABSPATH' ) ) { exit; }


function activate_doula_course(){
	
	global $wpdb; 
	$installed_version = get_option( 'doula_course_version' );
	
	if( $installed_version != DOULA_COURSE_VERSION ){

		$charset_collate = $wpdb->get_charset_collate();
	
		$sql = "CREATE TABLE nb_ipn_records (
		  id int(11) NOT NULL AUTO_INCREMENT,
		  created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		  ipn_id varchar(256) DEFAULT NULL,
		  info longtext,
		  PRIMARY KEY  (id)
		) $charset_collate;
		
		CREATE TABLE nb_messages (
		  message_id bigint(20) NOT NULL AUTO_INCREMENT,
		  message_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		  message_type varchar(20) NOT NULL COMMENT '//email, dashboard, ',
		  message_content text NOT NULL,
		  message_recipient bigint(20) NOT NULL COMMENT 'user_id',
		  message_status varchar(20) NOT NULL DEFAULT 'UNSENT',
		  message_active enum('y','n') NOT NULL,
		  PRIMARY KEY  (message_id)
		) $charset_collate;
		
		CREATE TABLE nb_transactions (
		  transaction_id int(9) unsigned zerofill NOT NULL AUTO_INCREMENT,
		  student_id int(6) unsigned zerofill NOT NULL COMMENT 'ID from wp_users table',
		  txn_id varchar(32) NOT NULL,
		  trans_amount decimal(6,2) NOT NULL,
		  trans_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		  trans_label varchar(100) NOT NULL,
		  trans_detail longtext NOT NULL,
		  trans_method varchar(20) NOT NULL,
		  trans_type varchar(20) NOT NULL,
		  PRIMARY KEY  (transaction_id),
		  KEY student_id (student_id,trans_method,trans_type),
		  KEY trans_label (trans_label)
		) $charset_collate COMMENT='A transaction table to associate with student accounts';";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		update_option( "doula_course_version", DOULA_COURSE_VERSION );
		
		wp_mail( 'brent@trainingdoulas.com', 'Plugin Activated', 'The doula course plugin has been actviated and the database has been updated!' );
		
	}
}

add_action( 'doula_course_activate', 'Doula_Course\App\Func\activate_doula_course' );

?>