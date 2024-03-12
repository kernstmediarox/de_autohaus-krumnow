<?php
if( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly

/*
 * Rank Math SEO Integration
 * =========================
 *
 * @since 5.0
 */

if( ! defined( 'RANK_MATH_VERSION' ) && ! class_exists( 'RankMath', false ) )
{
	return;
}

if( ! function_exists( 'avia_rank_math_register_assets' ) )
{
	function avia_rank_math_register_assets()
	{
		$screen = get_current_screen();
		$vn = avia_get_theme_version();
		$min_js = avia_minify_extension( 'js' );

		if( is_null( $screen ) || $screen->post_type == '' )
		{
			return;
		}

		wp_enqueue_script(
			'avia_analytics_js',
			AVIA_BASE_URL . "config-templatebuilder/avia-template-builder/assets/js/avia-analytics{$min_js}.js",
			[ 'avia_builder_js' ],
			$vn,
			true
		);

		wp_enqueue_script(
			'avia_rank_math_js',
			AVIA_BASE_URL . "config-rank-math/rank-math-mod{$min_js}.js",
			[ 'wp-hooks', 'wp-shortcode', 'rank-math-analyzer', 'avia_analytics_js' ],
			$vn,
			true
		);
	}

	if( is_admin() )
	{
		add_action( 'admin_enqueue_scripts', 'avia_rank_math_register_assets' );
	}
}

if( ! function_exists( 'avia_rank_math_register_toc_widget' ) )
{
	/**
	 * Notifies Rank Math that the theme contains a TOC widget or element.
	 * https://rankmath.com/kb/table-of-contents-not-detected/
	 *
	 * @since 5.0
	 * @param array $toc_plugins
	 * @return array
	 */
	function avia_rank_math_register_toc_widget( $toc_plugins )
	{
		$toc_plugins['seo-by-rank-math/rank-math.php'] = 'Rank Math';

		return $toc_plugins;
	}

	add_filter( 'rank_math/researches/toc_plugins', 'avia_rank_math_register_toc_widget', 10, 1 );
}

