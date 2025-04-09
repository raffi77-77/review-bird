<?php
/**
 * Review Bird plugin
 *
 * @since             1.0.0
 * @package           Review_Bird
 *
 * @wordpress-plugin
 * Plugin Name:       Review bird
 * Plugin URI:        
 * Description:       
 * Version:           1.0.0
 * Author:            
 * Author URI:        
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sr-rb
 * Domain Path:       /languages
 */
// If this file is called directly, abort.

if ( ! defined( 'WPINC' ) ) {
	die;
}
use Review_Bird\Includes\Review_Bird;

define( 'SR_RB_VERSION', '1.0.0' );
define( 'SR_RB_FILE', __FILE__ );

require plugin_dir_path( __FILE__ ) . 'includes/class-rb.php';
function Review_Bird() {
	return Review_Bird::get_instance();
}

function run_review_bird() {
	Review_Bird()->run();
}

run_review_bird();
