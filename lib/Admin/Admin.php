<?php

if ( ! class_exists( 'PB_Newsletter_Gen_Admin' ) ) {

	class PB_Newsletter_Gen_Admin {

		public function __construct() {

			$this->init_hooks();
		}

		public function init_hooks() {
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );

			return $this;
		}

		public function admin_menu() {

			add_options_page(
				'PB Newsletter Generator',
				'PB Newsletter Generator',
				'manage_options',
				'pb-newsletter-gen-settings',
				array( $this, 'render_options' )
			);
		}

		private function register_scripts() {
			wp_register_script(
				'pb-datepickr',
				plugins_url() . '/pebblebed/media/js/datepickr.js',
				array(
					'jquery'
				),
				'1.0.0',
				true
			);

			wp_register_script(
				'pb-active-campaign',
				PB_NEWSLETTER_GEN_URL . 'assets/js/pb-active-campaign.js',
				array(),
				'1.0.0',
				true
			);

			wp_register_script(
				'pb-newsletter-gen-ui',
				PB_NEWSLETTER_GEN_URL . 'assets/js/pb-newsletter-gen-ui.js',
				array(
					'jquery',
					'pb-datepickr',
					'pb-active-campaign',
				),
				'1.0.0',
				true
			);

			return $this;
		}

		private function register_styles() {
			wp_register_style(
				'pb-newsletter-gen-css',
				PB_NEWSLETTER_GEN_URL . 'assets/css/dist/pb-newsletter-gen.min.css',
				array(),
				'1.0.0'
			);

			return $this;
		}

		private function enqueue_scripts() {
			wp_enqueue_script( 'pb-newsletter-gen-ui' );
			wp_enqueue_script( 'pb-datepickr' );

			return $this;
		}

		private function enqueue_styles() {
			wp_enqueue_style( 'pb-newsletter-gen-css' );

			return $this;
		}

		public function render_options() {
			$this->register_styles()
				->register_scripts()
				->enqueue_scripts()
				->enqueue_styles();

			include PB_NEWSLETTER_GEN_PATH . '/views/admin/settings.php';
		}

		public function install() {
			add_option( 'pb-newsletter-gen-settings', array() );
		}

		public function uninstall() { }

	}

}