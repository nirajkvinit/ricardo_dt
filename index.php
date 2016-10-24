<?php
/*
Plugin Name: Ricardo Juliano DataTable Integration
Plugin URI: http://www.pedagoge.com/
Description: Ricardo Juliano DataTable Integration
Version: 1.0
Author: nirajkvinit@yahoo.co.in
Author URI: http://www.pedagoge.com/
*/



/**
 * Ricardo Plugin URL Constant
 */
define("RICARDO_PLUGIN_URL", WP_PLUGIN_URL ."/ricardo_dt");

/**
 * Ricardo Plugin Directory Path Constant
 */
define( "RECARDO_PLUGIN_DIR", plugin_dir_path( __FILE__ ) );


//Require once the Datatable php class file
require_once(RECARDO_PLUGIN_DIR.'includes/RicardoDataTables.php');


add_action('wp_enqueue_scripts', 'fn_ricardo_dt_scripts_loader');

/**
 * Enqueue all the required CSS/JS
 */
function fn_ricardo_dt_scripts_loader() {
	
	//Register CSS/JS
	fn_ricardo_register_scripts();
	
	//Enqueue CSS/JS
	wp_enqueue_style('ricardo_dt_css');		
	wp_enqueue_script('ricardo_dt_js');
	wp_enqueue_script('ricardo_js');
	
	//Localize script
	wp_localize_script('ricardo_js', 'RICARDOAJAX', array('ajaxurl' => admin_url('admin-ajax.php')));
}

/**
 * Register all the required CSS/JS
 */
function fn_ricardo_register_scripts() {
	wp_register_style('ricardo_dt_css', 'https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css');
	
	wp_register_script('ricardo_dt_js', 'https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js', array('jquery'));
	wp_register_script('ricardo_js', RICARDO_PLUGIN_URL.'/assets/js/ricardo.js', array('ricardo_dt_js'), '0.1', true);
}

// Create a shortcode - ricardo_dt
add_shortcode('ricardo_dt', 'fn_ricardo_dt');

function fn_ricardo_dt() {
	$str_return = '
		<table id="ricardo_dt" class="display" cellspacing="0" width="100%">
	        <thead>
	            <tr>
	            	<th>ID</th>
	                <th>First name</th>
	                <th>Last name</th>
	                <th>Contact No</th>
	                <th>Email</th>
	                <th>Address</th>
	                <th>City</th>
	            </tr>
	        </thead>
	        <tbody>
	        	<tr>
	        		<td colspan="7">Loading Data...</td>
	        	</tr>
	        </tbody>
	    </table>
	';
	return $str_return;
}

add_action( 'wp_ajax_fn_process_ricardo_dt_ajax', 'fn_process_ricardo_dt_ajax');
add_action( 'wp_ajax_nopriv_fn_process_ricardo_dt_ajax', 'fn_process_ricardo_dt_ajax');

function fn_process_ricardo_dt_ajax() {
	global $wpdb;
	
	$table = 'ricardo_contacts';
	
	// Table's primary key
	$primaryKey = 'id';
	
	// Array of database columns which should be read and sent back to DataTables.
	// The `db` parameter represents the column name in the database, while the `dt`
	// parameter represents the DataTables column identifier. In this case simple
	// indexes	
	$columns = array(
		array( 'db' => 'id', 'dt' => 0 ),
		array( 'db' => 'first_name',  'dt' => 1 ),
		array( 'db' => 'last_name',   'dt' => 2 ),
		array( 'db' => 'contact_number', 'dt' => 3 ),
		array( 'db' => 'email_address',  'dt' => 4 ),
		array( 'db' => 'address',   'dt' => 5 ),
		array( 'db' => 'city', 'dt' => 6 ),		
	);
	
	// SQL server connection information
	$sql_details = array(
		'user' => DB_USER,
		'pass' => DB_PASSWORD,
		'db'   => DB_NAME,
		'host' => DB_HOST
	);
	
	echo json_encode(
		RicardoDataTables::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
	);
	die();
}


