<?php
/**
 * Patterns Class.
 *
 * @package aquila-features
 */

namespace AquilaFeatures;

/**
 * Class Patterns.
 */
class Patterns {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Initialize plugin
	 */
	private function init() {
		/**
		 * Actions.
		 */
		add_action( 'init', [ $this, 'register_block_patterns' ] );
		add_action( 'init', [ $this, 'register_block_pattern_categories' ] );
	}

	/**
	 * Register Block Patterns.
	 */
	public function register_block_patterns() {
		if ( function_exists( 'register_block_pattern' ) ) {

			// Get the two column pattern content.
			$two_columns_content = aquila_features_get_template( 'patterns/two-columns' );

			/**
			 * Register Two Column Pattern
			 */
			register_block_pattern(
				'aquila-features/two-columns',
				[
					'title'       => __( 'Aquila Features Two Column', 'aquila-features' ),
					'description' => __( 'Aquila Two Column Patterns', 'aquila-features' ),
					'categories'  => [ 'aquila-columns' ],
					'content'     => $two_columns_content,
				]
			);

			/**
			 * Two Columns Secondary Pattern
			 */
			$two_columns_secondary_content = aquila_features_get_template( 'patterns/two-columns-secondary' );

			register_block_pattern(
				'aquila-features/two-columns-secondary',
				[
					'title'       => __( 'Aquila Two Columns Secondary', 'aquila-features' ),
					'description' => __( 'Aquila Cover Block with image and text', 'aquila-features' ),
					'categories'  => [ 'aquila-columns' ],
					'content'     => $two_columns_secondary_content,
				]
			);
		}
	}

	/**
	 * Register Block Pattern Categories.
	 */
	public function register_block_pattern_categories() {

		$pattern_categories = [
			'aquila-columns' => __( 'Aquila Features Columns', 'aquila-features' ),
		];

		if ( ! empty( $pattern_categories ) ) {
			foreach ( $pattern_categories as $pattern_category => $pattern_category_label ) {
				register_block_pattern_category(
					$pattern_category,
					[ 'label' => $pattern_category_label ]
				);
			}
		}
	}
}
