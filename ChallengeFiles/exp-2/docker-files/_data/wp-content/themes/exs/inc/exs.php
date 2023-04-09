<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define( 'EXS_DEV_MODE', is_dir( EXS_THEME_PATH . '/dev' ) );
define( 'EXS_EXTRA', is_dir( EXS_THEME_PATH . '/extra' ) );
define( 'EXS_FR', is_dir( EXS_THEME_PATH . '/freemius' ) );
define( 'EXS_WP', is_dir( EXS_THEME_PATH . '/inc/wp' ) );
define( 'EXS_TM', is_dir( EXS_THEME_PATH . '/inc/tm' ) );

if ( ! class_exists( 'ExS' ) ) :

	class ExS {
		public $state;
		/**
		 * Singleton
		 */
		public static function instance() {
			static $instance = null;
			if( null === $instance ) {
				$instance = new static();
			}

			return $instance;
		}

		private function __construct() {}
		private function __clone() {}
		public function __sleep() {}
		public function __wakeup() {}

		public function set( $key, $value ) {
			$this->state[ $key ] = $value;
		}

		public function get( $key ) {
			return ! empty( $this->state[ $key ] ) ? $this->state[ $key ] : false;
		}

	}

endif; //class exists
