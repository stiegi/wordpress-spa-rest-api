<?php
namespace Spa\Settings;

class SettingsMenu {
	/**
	 * Menu slug
	 *
	 * @var string
	 */
	protected $slug = 'spa-settings';
	/**
	 * URL for assets
	 *
	 * @var string
	 */
	protected $assets_url;
	/**
	 * Apex_Menu constructor.
	 *
	 * @param string $assets_url URL for assets
	 */
	public function __construct( $assets_url )
	{
		$this->assets_url = $assets_url;
		add_action( 'admin_menu', array( $this, 'add_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_assets' ) );
	}

	public static function add_settings_menu()
	{
		$assets_url = plugin_dir_url( __FILE__ );
		if( is_admin() ){
			new SettingsMenu( $assets_url );
		}
	}

	/**
	 * Add CF Popup submenu page
	 *
	 * @since 0.0.3
	 *
	 * @uses "admin_menu"
	 */
	public function add_page(){
		add_menu_page(
			__( 'SPA', 'text-domain' ),
			__( 'SPA', 'text-domain' ),
			'manage_options',
			$this->slug,
			array( $this, 'render_admin' ) );
	}
	/**
	 * Register CSS and JS for page
	 *
	 * @uses "admin_enqueue_scripts" action
	 */
	public function register_assets()
	{
		wp_register_script( $this->slug, $this->assets_url . 'admin-menu/public/build/bundle.js', [] );
		wp_register_style( $this->slug, $this->assets_url . 'admin-menu/public/build/bundle.css' );
		wp_localize_script( $this->slug, '_spa', array(
			'strings' => array(
				'saved' => __( 'Settings Saved', 'text-domain' ),
				'error' => __( 'Error', 'text-domain' )
			),
			'api'     => array(
				'url'   => esc_url_raw( rest_url( 'spa/v1/settings' ) ),
				'buildFileCache' => esc_url_raw( rest_url( 'spa/v1/cache/file/build' ) ),
				'nonce' => wp_create_nonce( 'wp_rest' )
			),
			'settings' => Settings::get_settings()
		) );
	}
	/**
	 * Enqueue CSS and JS for page
	 */
	public function enqueue_assets(){
		if( ! wp_script_is( $this->slug, 'registered' ) ){
			$this->register_assets();
		}
		wp_enqueue_script( $this->slug );
		wp_enqueue_style( $this->slug );
	}

	/**
	 * Render plugin admin page
	 */
	public function render_admin(){
		$this->enqueue_assets();
		?>
		<div id="spa-svelte-menu"></div>
		<?php
	}
}
