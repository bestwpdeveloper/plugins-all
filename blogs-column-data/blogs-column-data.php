<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              bestwpdeveloper.com/about
 * @since             1.0.0
 * @package           Blogs_Column_Data
 *
 * @wordpress-plugin
 * Plugin Name:       Blogs Column Data
 * Plugin URI:        bestwpdeveloper.com/plugins/blogs-column-data
 * Description:       A simple and nice plugin to see some blogs column. And also it'll be helpful for knowing every blogs ID's and you'll that how many words have in these blogs and also you can filter your blogs with a thumbnails base.
 * Version:           1.0.0
 * Author:            Best WP Developer
 * Author URI:        bestwpdeveloper.com/about
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       blogs-column-data
 * Domain Path:       /languages
 */

// Loaded Plugin text-domain
function bcd_load_textdomain(){
    load_plugin_textdomain('blogs-column-data', false, dirname(__FILE__). '/languages');
}
add_action('plugins_loaded', 'bcd_load_textdomain');

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BCD_PLUGIN_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-blogs-column-data-activator.php
 */
function activate_bcd() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-blogs-column-data-activator.php';
	Blogs_Column_Data_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-blogs-column-data-deactivator.php
 */
function deactivate_bcd() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-blogs-column-data-deactivator.php';
	Blogs_Column_Data_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_bcd' );
register_deactivation_hook( __FILE__, 'deactivate_bcd' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-blogs-column-data.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */

function blogs_column_data() {

	$plugin = new Blogs_Column_Data();
	$plugin->run();

	
function bcd_custom_columns_list($columns) {

	print_r($columns);
	unset($columns['date']);
	
	$columns['id'] = __('Post ID', 'blogs-column-data');
	$columns['wordcount'] = __('Wordcount', 'blogs-column-data');
	$columns['thumbnail'] = __('Thumbnail', 'blogs-column-data');
	$columns['date'] = __('Publish Date', 'blogs-column-data');
	return $columns;
}
add_filter( 'manage_posts_columns', 'bcd_custom_columns_list' );

function bcd_custom_columns_list_pages($columns) {

	print_r($columns);
	unset($columns['date']);
	
	$columns['id'] = __('Pages ID', 'blogs-column-data');
	$columns['wordcount'] = __('Wordcount', 'blogs-column-data');
	$columns['thumbnail'] = __('Thumbnail', 'blogs-column-data');
	$columns['date'] = __('Publish Date', 'blogs-column-data');
	return $columns;
}
add_filter( 'manage_pages_columns', 'bcd_custom_columns_list_pages' );


function bcd_data_showing($column, $post_id){
	if('id' == $column){
		echo esc_html($post_id);
	}elseif('thumbnail' == $column){
		$thumbnail = get_the_post_thumbnail($post_id, array(100, 100));
		echo esc_url($thumbnail);
	}elseif('wordcount' == $column){
		$_post = get_post($post_id);
		$content = $_post->post_content;
		$wordcnt = str_word_count(strip_tags($content));
		$wordn = get_post_meta( $post_id, 'wordn', true );
		echo esc_html($wordcnt);
	}
}
add_action( 'manage_posts_custom_column', 'bcd_data_showing', 10, 2 );
add_action( 'manage_pages_custom_column', 'bcd_data_showing', 10, 2 );



function bcd_sortable( $columns ) {
	$columns['wordcount'] = 'wordbcd';
	return $columns;
}
add_filter( 'manage_edit-post_sortable_columns', 'bcd_sortable' );

// Check just has thumbnail or no thumbnail
function bcd_thumbnail_filter(){
	if(isset($_GET['post_type']) && $_GET['post_type'] !='post'){
		return;
	}
	$filter_values = isset($_GET['bcd_thumbnail_filter']) ? $_GET['bcd_thumbnail_filter'] : '';
	$valus = array(
		'0' => __('Thumbnail Check', 'blogs-column-data'),
		'1' => __('Has Thumbnail', 'blogs-column-data'),
		'2' => __('No Thumbnail', 'blogs-column-data'),
	);
	?>
		<select name="bcd_thumbnail_filter">
			<?php 
				foreach($valus as $keys => $valu){
					printf("<option value='%s' %s>%s</option>", $keys,
				$keys == $filter_values ? "selected = 'selected'" : '',
				$valu
				);
				}
			?>
		</select>
	<?php
}
add_action('restrict_manage_posts', 'bcd_thumbnail_filter');
add_action('restrict_manage_pages', 'bcd_thumbnail_filter');

function bcd_thumbnail_column_filter($wpquery){
	if(! is_admin()){
		return;
	}
	$filter_value = isset($_GET['bcd_thumbnail_filter']) ? $_GET['bcd_thumbnail_filter'] : '';
	if('1'==$filter_value){
		$wpquery->set('meta_query', array(
			array(
				'key' => '_thumbnail_id',
				'compare' => 'EXISTS'
			)
		)
	);
	} else if('2'==$filter_value){
		$wpquery->set('meta_query', array(
			array(
				'key' => '_thumbnail_id',
				'compare' => 'NOT EXISTS'
			)
		)
	);
	}
}
add_action('pre_get_posts', 'bcd_thumbnail_column_filter');
add_action('pre_get_pages', 'bcd_thumbnail_column_filter');


}
blogs_column_data();


