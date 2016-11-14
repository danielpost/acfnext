<?php

class ACF_PHP {

	protected $ACF_PHP;

	protected $version;

	public function __construct() {

		$this->ACF_PHP = 'acf-php';
		$this->version = '1.0.0';

		$this->load_dependencies();

		if ( is_admin() ) {
			new ACF_PHP_Admin( $this->ACF_PHP, $this->version );
		}

	}

	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-acf-php-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-acf-php-metabox.php';

	}

	public function get_acf_php() {
		return $this->ACF_PHP;
	}

	public function get_version() {
		return $this->version;
	}

}
