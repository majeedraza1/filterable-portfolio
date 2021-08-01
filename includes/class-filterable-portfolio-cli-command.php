<?php

defined( 'ABSPATH' ) || exit;

class Filterable_Portfolio_CLI_Command extends WP_CLI_Command {
	/**
	 * Display Carousel Slider Information
	 */
	public function info() {
		WP_CLI::success( 'Welcome to the Filterable Portfolio WP-CLI Extension!' );
		WP_CLI::line( '' );
		WP_CLI::line( '- Filterable Portfolio Version: ' . FILTERABLE_PORTFOLIO_VERSION );
		WP_CLI::line( '- Filterable Portfolio Directory: ' . FILTERABLE_PORTFOLIO_PATH );
		WP_CLI::line( '- Filterable Portfolio Public URL: ' . FILTERABLE_PORTFOLIO_URL );
		WP_CLI::line( '' );
	}

	/**
	 * Create dummy data
	 */
	public function create_dummy_data() {
		Filterable_Portfolio_Helper::create_dummy_categories();
		WP_CLI::line( 'Filterable Portfolio: Dummy categories have been generated.' );
		Filterable_Portfolio_Helper::create_dummy_skills();
		WP_CLI::line( 'Filterable Portfolio: Dummy skills have been generated.' );
		Filterable_Portfolio_Helper::create_dummy_portfolio( 5 );
		WP_CLI::line( 'Filterable Portfolio: Dummy portfolios have been generated.' );
		Filterable_Portfolio_Helper::create_test_page();
		WP_CLI::line( 'Filterable Portfolio: Test page have been generated.' );
	}
}
