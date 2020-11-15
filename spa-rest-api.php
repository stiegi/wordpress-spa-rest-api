<?php
/**
 * Plugin Name:     SPA Rest Api
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     REST API for Single Page Applications
 * Author:          Sascha Stieglitz
 * Author URI:      YOUR SITE HERE
 * Text Domain:     spa-rest-api
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Spa_Rest_Api
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( is_readable( __DIR__ . '/vendor/autoload.php' ) ) {
	require __DIR__ . '/vendor/autoload.php';
}
if(!class_exists('Spa')) {
	new Spa\Init();
}
