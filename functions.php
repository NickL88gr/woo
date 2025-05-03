<?php
/**
 * Botiga functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Botiga
 */

if ( ! defined( 'BOTIGA_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( 'BOTIGA_VERSION', '2.1.1.8' );
}

// aThemes White Label Compatibility
if( function_exists( 'athemes_wl_get_data' ) ) {
	$botiga_awl_data = athemes_wl_get_data();

	if( isset( $botiga_awl_data[ 'activate_white_label' ] ) && $botiga_awl_data[ 'activate_white_label' ] ) {
		define( 'BOTIGA_AWL_ACTIVE', true );
	}
}

/**
 * Declare incompatibility with WooCommerce 8.3+ new default cart and checkout blocks.
 * 
 */
add_action( 'plugins_loaded', function(){
	add_action( 'before_woocommerce_init', function() {
		if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, false );
		}
	} );
} );

if ( ! function_exists( 'botiga_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function botiga_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Botiga, use a find and replace
		 * to change 'botiga' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'botiga', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'botiga-extra-large', 1140, 9999 );
		add_image_size( 'botiga-large', 920, 9999 );
		add_image_size( 'botiga-big', 575, 9999 );
		add_image_size( 'botiga-medium', 380, 9999 );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'primary'	=> esc_html__( 'Primary', 'botiga' ),
				'secondary' => esc_html__( 'Secondary Menu', 'botiga' ),
			)
		);

		/*
		 * Add post formats
		 */
		add_theme_support(
			'post-formats',
			array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
				'status',
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'botiga_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => ''
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);

		/**
		 * Wide alignments
		 *
		 */		
		add_theme_support( 'align-wide' );

		/**
		 * Color palettes
		 */
		$selected_palette 	= get_theme_mod( 'color_palettes', 'palette1' );
		$palettes 			= botiga_global_color_palettes();

		$colors = array();
		
		$custom_palette_toggle = get_theme_mod( 'custom_palette_toggle', 0 );
		if( $custom_palette_toggle ) {
			for ( $i = 0; $i < 8; $i++ ) {
				$colors[] = array(
					/* translators: %s: color palette */
					'name'  => sprintf( esc_html__( 'Color %s', 'botiga' ), ($i+1) ),
					'slug'  => 'color-' . $i,
					'color' => get_theme_mod( 'custom_color' . ($i+1), '#212121' )
				);
			}
		} else {
			for ( $i = 0; $i < 8; $i++ ) { 
				$colors[] = array(
					/* translators: %s: color palette */
					'name'  => sprintf( esc_html__( 'Color %s', 'botiga' ), ($i+1) ),
					'slug'  => 'color-' . $i,
					'color' => $palettes[$selected_palette][$i],
				);
			}
		}

		add_theme_support(
			'editor-color-palette',
			$colors
		);	
		
		/**
		 * Editor font sizes
		 */
		add_theme_support(
			'editor-font-sizes',
			array(
				array(
					'name'      => esc_html__( 'Small', 'botiga' ),
					'shortName' => esc_html_x( 'S', 'Font size', 'botiga' ),
					'size'      => 14,
					'slug'      => 'small',
				),				
				array(
					'name'      => esc_html__( 'Normal', 'botiga' ),
					'shortName' => esc_html_x( 'N', 'Font size', 'botiga' ),
					'size'      => 16,
					'slug'      => 'normal',
				),
				array(
					'name'      => esc_html__( 'Large', 'botiga' ),
					'shortName' => esc_html_x( 'L', 'Font size', 'botiga' ),
					'size'      => 18,
					'slug'      => 'large',
				),
				array(
					'name'      => esc_html__( 'Larger', 'botiga' ),
					'shortName' => esc_html_x( 'L', 'Font size', 'botiga' ),
					'size'      => 24,
					'slug'      => 'larger',
				),
				array(
					'name'      => esc_html__( 'Extra large', 'botiga' ),
					'shortName' => esc_html_x( 'XL', 'Font size', 'botiga' ),
					'size'      => 32,
					'slug'      => 'extra-large',
				),
				array(
					'name'      => esc_html__( 'Huge', 'botiga' ),
					'shortName' => esc_html_x( 'XXL', 'Font size', 'botiga' ),
					'size'      => 48,
					'slug'      => 'huge',
				),
				array(
					'name'      => esc_html__( 'Gigantic', 'botiga' ),
					'shortName' => esc_html_x( 'XXXL', 'Font size', 'botiga' ),
					'size'      => 64,
					'slug'      => 'gigantic',
				),
			)
		);		

		/**
		 * Responsive embeds
		 */
		add_theme_support( 'responsive-embeds' );

		/**
		 * Page templates with blocks
		 */
		add_theme_support( 'block-templates' );
	}
endif;
add_action( 'after_setup_theme', 'botiga_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function botiga_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'botiga_content_width', 1140 ); // phpcs:ignore WPThemeReview.CoreFunctionality.PrefixAllGlobals.NonPrefixedVariableFound
}
add_action( 'after_setup_theme', 'botiga_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function botiga_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'botiga' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'botiga' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
			'before_sidebar' => '<div class="sidebar-wrapper"><a href="#" role="button" class="close-sidebar" title="'. esc_attr__( 'Close sidebar', 'botiga' ) .'" onclick="botiga.toggleClass.init(event, this, \'sidebar-slide-close\');" data-botiga-selector=".sidebar-slide+.widget-area" data-botiga-toggle-class="show">'. botiga_get_svg_icon( 'icon-cancel' ) .'</a>',
			'after_sidebar'  => '</div>'
		)
	);

	for ( $i = 1; $i <= 4; $i++ ) { 
		register_sidebar(
			array(
				/* translators: %s = footer widget area number */
				'name'          => sprintf( esc_html__( 'Footer %s', 'botiga' ), $i ),
				'id'            => 'footer-' . $i,
				'description'   => esc_html__( 'Add widgets here.', 'botiga' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			)
		);
	}
}
add_action( 'widgets_init', 'botiga_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function botiga_scripts() {
	$fonts_library = get_theme_mod( 'fonts_library', 'google' );
	
	if( $fonts_library === 'google' ) {
		wp_enqueue_style( 'botiga-google-fonts', botiga_google_fonts_url(), array(), botiga_google_fonts_version() );
	} else if( $fonts_library === 'custom' ) {
		wp_enqueue_style( 'botiga-custom-google-fonts', botiga_custom_google_fonts_url(), array(), botiga_google_fonts_version() );
	} else {
		$kits = get_option( 'botiga_adobe_fonts_kits', array() );

		foreach ( $kits as $kit_id => $kit_data ) {

			if ( $kit_data['enable'] == false ) {
				continue;
			}

			wp_enqueue_style( 'botiga-typekit-' . $kit_id, 'https://use.typekit.net/' . $kit_id . '.css', array(), BOTIGA_VERSION );
		}
	}
	wp_enqueue_style( 'default', get_theme_file_uri( '/assets/css/default.css' ) );
	
	//wp_enqueue_style( 'multilevelmenu', get_theme_file_uri( '/assets/css/multilevelmenu.css' ) );

	wp_enqueue_style( 'component', get_theme_file_uri( '/assets/css/component.css' ),'','1.4.9.8' );
	//wp_enqueue_style( 'component_woo', get_theme_file_uri( '/assets/css/component_woo.css' ),'','1.4.9.8' );

//	wp_enqueue_style( 'default_woo', get_theme_file_uri( '/assets/css/default_woo.css' ) );



	wp_enqueue_style( 'animations', get_theme_file_uri( '/assets/css/animations.css' ) );
	wp_enqueue_script( 'botiga-custom', get_template_directory_uri() . '/assets/js/custom.js', array(), 
		'12.121', true );
	//Page Transitions
	wp_enqueue_script( 'dlmenu', get_theme_file_uri( '/assets/js/jquery.dlmenu.js' ), array( 'jquery' ), false, true );
	wp_enqueue_script( 'modernizr', get_theme_file_uri( '/assets/js/modernizr.custom.js' ), array( 'jquery' ), false, true );
	wp_enqueue_script( 'pagetransitions', get_theme_file_uri( '/assets/js/pagetransitions7.js' ), array( 'jquery' ), '121212.331112211223311212231111113212', true );
	wp_enqueue_script( 'cufon', get_theme_file_uri( '/assets/js/cufon-yui.js' ), array( 'jquery' ), false, true );
	wp_enqueue_script( 'BabelSans_500', get_theme_file_uri( '/assets/js/BabelSans_500.font.js' ), array( 'jquery' ), false, true );
	wp_enqueue_script( 'easing', get_theme_file_uri( '/assets/js/jquery.easing.1.3.js' ), array( 'jquery' ), false, true );
	wp_enqueue_script( 'cbpShop', get_theme_file_uri( '/assets/js/cbpShop.min.js' ), array( 'jquery' ), false, true );
	wp_enqueue_script( 'modernizr1', get_theme_file_uri( '/assets/js/modernizr.custom1.js' ), array( 'jquery' ), false, true );

	wp_localize_script( 'botiga-custom', 'botiga', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'i18n'    => array(
			'botiga_sharebox_copy_link' => __( 'Copy link', 'botiga' ),
			'botiga_sharebox_copy_link_copied' => __( 'Copied!', 'botiga' )
		)
	) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_register_script( 'botiga-carousel', get_template_directory_uri() . '/assets/js/botiga-carousel.min.js', NULL, BOTIGA_VERSION, true );
	wp_register_script( 'botiga-popup', get_template_directory_uri() . '/assets/js/botiga-popup.min.js', NULL, BOTIGA_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'botiga_scripts', 10 );

add_action( 'wp_enqueue_scripts', 'deregister_modernizr_script' );

function deregister_modernizr_script() {
    // Check if it's not the front page or home page
    if ( ! is_front_page() ) {
        // Deregister the script
    wp_deregister_script( 'modernizr' );
        wp_deregister_script( 'pagetransitions' );  
        

                wp_deregister_script( 'dlmenu' );  
wp_deregister_style( 'default' );
        
          }else{
          	        

          }
}
/**
 * Enqueue style css
 * Ensure compatibility with Botiga Pro, since pro scripts are enqueued with order "10"
 * We always need the custom.min.css as the last stylesheet enqueued
 * 
 * 'botiga-custom-style' is registered at 'inc/classes/class-botiga-custom-css.php'
 */
function botiga_style_css() {
	wp_enqueue_style( 'botiga-style-min', get_template_directory_uri() . '/assets/css/styles.min.css', array(), BOTIGA_VERSION );
	wp_enqueue_style( 'botiga-custom-styles' );
	wp_enqueue_style( 'botiga-style', get_stylesheet_uri(), array(), BOTIGA_VERSION );
}
add_action( 'wp_enqueue_scripts', 'botiga_style_css', 12 );

/**
 * Enqueue admin scripts and styles.
 */
function botiga_admin_scripts() {
	wp_enqueue_script( 'botiga-admin-functions', get_template_directory_uri() . '/assets/js/admin-functions.min.js', array('jquery'), BOTIGA_VERSION, true );
	wp_localize_script( 'botiga-admin-functions', 'botigaadm', array(
		'hfUpdate' => array(
			'confirmMessage' => __( 'Are you sure you want to upgrade your header?', 'botiga' ),
			'errorMessage' => __( 'It was not possible complete the request, please reload the page and try again.', 'botiga' )
		),
		'hfUpdateDimiss' => array(
			'confirmMessage' => __( 'Are you sure you want to dismiss this notice?', 'botiga' ),
			'errorMessage' => __( 'It was not possible complete the request, please reload the page and try again.', 'botiga' )
		),						
	) );
}
add_action( 'admin_enqueue_scripts', 'botiga_admin_scripts' );

/**
 * Page Templates.
 */
function botiga_remove_page_templates( $page_templates ) {
	if( ! defined( 'BOTIGA_PRO_VERSION' ) ) {
		unset( $page_templates['page-templates/template-wishlist.php'] );
	}
   
	return $page_templates;
}
add_filter( 'theme_page_templates', 'botiga_remove_page_templates' );

/**
 * Helper functions.
 */
require get_template_directory() . '/inc/helpers.php';

/**
 * Deactivate Elementor Wizard.
 */
function botiga_deactivate_ele_onboarding() {
	update_option( 'elementor_onboarded', true );
}
add_action( 'after_switch_theme', 'botiga_deactivate_ele_onboarding' );

/**
 * Modules Class.
 */
require get_template_directory() . '/inc/modules/class-botiga-modules.php';

/**
 * Gutenberg editor.
 */
require get_template_directory() . '/inc/editor.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/plugins/jetpack/jetpack.php';
}

/**
 * Load Max Mega Menu compatibility file.
 */
if ( class_exists( 'Mega_Menu' ) ) {
	require get_template_directory() . '/inc/plugins/max-mega-menu/max-mega-menu.php';
}

/**
 * Load WooCommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/plugins/woocommerce/woocommerce.php';
}

/**
 * Load Merchant compatibility file.
 */
if ( class_exists( 'Merchant' ) ) {
	require get_template_directory() . '/inc/plugins/merchant/merchant.php';
}

/**
 * Load WooCommerce Brands compatibility file.
 */
if ( class_exists( 'WooCommerce' ) && class_exists( 'WC_Brands' ) ) {
	require get_template_directory() . '/inc/plugins/woocommerce-brands/woocommerce-brands.php';
}

/**
 * Load Elementor compatibility file.
 */
if( defined( 'ELEMENTOR_VERSION' ) ) {
	require get_template_directory() . '/inc/plugins/elementor/elementor.php';
}

/**
 * Load Dokan compatibility file.
 */
if( defined( 'DOKAN_PLUGIN_VERSION' ) && class_exists( 'Woocommerce' ) ) {
	require get_template_directory() . '/inc/plugins/dokan/dokan.php';
}

/**
 * Load WC Vendors compatibility file.
 */
if( class_exists( 'WC_Vendors' ) && class_exists( 'Woocommerce' ) ) {
	require get_template_directory() . '/inc/plugins/wc-vendors/wc-vendors.php';
}

/**
 * Load WC Germanized compatibility file.
 */
if( class_exists( 'WooCommerce_Germanized' ) && class_exists( 'Woocommerce' ) ) {
	require get_template_directory() . '/inc/plugins/wc-germanized/class-wc-germanized.php';
}

/**
 * Load WC Germanized EU VAT Compilance compatibility file.
 */
if( class_exists( 'WC_EU_VAT_Compliance' ) && class_exists( 'Woocommerce' ) ) {
	require get_template_directory() . '/inc/plugins/woocommerce-eu-vat-compliance-premium/class-woocommerce-eu-vat-compliance-premium.php';
}

/**
 * Upsell.
 */
if( ! defined( 'BOTIGA_PRO_VERSION' ) ) {
	require get_template_directory() . '/inc/customizer/upsell/class-customize.php';
}

/**
 * Theme classes.
 */
require get_template_directory() . '/inc/classes/class-botiga-topbar.php';
require get_template_directory() . '/inc/classes/class-botiga-header.php';
require get_template_directory() . '/inc/classes/class-botiga-footer.php';
require get_template_directory() . '/inc/classes/class-botiga-posts-archive.php';
require get_template_directory() . '/inc/classes/class-botiga-svg-icons.php';
require get_template_directory() . '/inc/classes/class-botiga-metabox.php';
require get_template_directory() . '/inc/classes/class-botiga-custom-css.php';

/**
 * Theme ajax callbacks.
 */
require get_template_directory() . '/inc/ajax-callbacks.php';

/**
 * Legacy composer autoload.
 * Purpose is autoload only needed kirki-framework controls classes. 
 */
require_once get_parent_theme_file_path( 'vendor-legacy/autoload.php' );

/**
 * Theme dashboard.
 */
require get_template_directory() . '/inc/dashboard/class-dashboard.php';

/**
 * Theme dashboard settings.
 */
require get_template_directory() . '/inc/dashboard/class-dashboard-settings.php';

/**
 * Modules.
 */
require get_template_directory() . '/inc/modules/adobe-typekit/adobe-typekit.php';
require get_template_directory() . '/inc/modules/schema-markup/schema-markup.php';

if( defined( 'BOTIGA_PRO_VERSION' ) ) {
	if( version_compare( BOTIGA_PRO_VERSION, '1.1.0', '>=' ) ) {
		require get_template_directory() . '/inc/modules/hf-builder/class-header-footer-builder.php';
	} else {
		$botiga_all_modules = get_option( 'botiga-modules' );
		$botiga_all_modules = ( is_array( $botiga_all_modules ) ) ? $botiga_all_modules : (array) $botiga_all_modules;
		update_option( 'botiga-modules', array_merge( $botiga_all_modules, array( 'hf-builder' => false ) ) );

		add_action( 'admin_notices', function(){ ?>
			<div class="notice notice-warning" style="position:relative;">
				<p>
					<?php
					printf(
						/* Translators: %s plugins html anchor link. */
						esc_html__(
							'It looks like your website is running Botiga Pro but not with its latest version. Please note that Botiga 1.1.9+ (free theme) requires Botiga Pro updated to a minimum version of 1.1.0. For it please go to %s and update Botiga Pro.', 'botiga'
						),
						'<a href="'. esc_url( admin_url( 'plugins.php' ) ) .'">' . esc_html__( 'Plugins', 'botiga' ) . '</a>'
					);
					?>
				</p>
			</div>
			<?php
		}, 0 );
	}
} else {
	require get_template_directory() . '/inc/modules/hf-builder/class-header-footer-builder.php';
}

/**
 * Review notice.
 */
require get_template_directory() . '/inc/notices/class-botiga-review.php';

/**
 * Botiga pro upsell notice.
 */
require get_template_directory() . '/inc/notices/class-botiga-pro-upsell.php';

/**
 * Theme update migration functions.
 */
require get_template_directory() . '/inc/theme-update.php';

/**
 * Botiga custom get template part
 */
function botiga_get_template_part( $slug, $name = null, $args = array() ) {
	if ( version_compare( get_bloginfo( 'version' ), '5.5', '>=' ) ) {
		return get_template_part( $slug, $name, $args );
	} else {
		extract($args);
	
		$templates = array();
		$name = (string) $name;
		if ( '' !== $name ) {
			$templates[] = "{$slug}-{$name}.php";
		}
		$templates[] = "{$slug}.php";
	 
		return include( locate_template($templates) );
	}
}


function allow_svg_upload( $mimes ) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter( 'upload_mimes', 'allow_svg_upload' );



function customizer_example_customize_register($wp_customize) {
    // Add a main section for Homepage Attitude Options
    $wp_customize->add_section('homepage_attitude_options', array(
        'title'    => 'Homepage Attitude Options',
        'priority' => 30,
    ));

     customizer_example_add_fields($wp_customize, 'homepage_attitude_options_section1_', 'Section 1');

     customizer_example_add_fields($wp_customize, 'homepage_attitude_options_section2_', 'Section 2');

     customizer_example_add_fields($wp_customize, 'homepage_attitude_options_section3_', 'Section 3');

     customizer_example_add_fields($wp_customize, 'homepage_attitude_options_section4_', 'Section 4');

     customizer_example_add_fields($wp_customize, 'homepage_attitude_options_section5_', 'Section 5');

     customizer_example_add_fields($wp_customize, 'homepage_attitude_options_menu_', 'Menu Option');
}

function customizer_example_add_fields($wp_customize, $prefix, $section_title) {
     if ($section_title === 'Menu Option') {
        $wp_customize->add_setting($prefix . 'front_page_menu', array(
            'default'           => '',
            'sanitize_callback' => 'absint', 
        ));

        $wp_customize->add_control($prefix . 'front_page_menu', array(
            'label'       => 'Επιλογή Menu για την αρχική σελίδα',
            'section'     => 'homepage_attitude_options',
            'type'        => 'select',
            'choices'     => customizer_example_get_menus(), 
            'description' => 'Παρακαλώ επιλεξτε το menu για την αρχική σελίδα',
        ));
    } else {
         $wp_customize->add_setting($prefix . 'subtitle', array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ));

        $wp_customize->add_control($prefix . 'subtitle', array(
            'label'   => $section_title . ' Υποτιτλος',
            'section' => 'homepage_attitude_options',
            'type'    => 'text',
        ));
		$wp_customize->add_setting($prefix . 'subtitle_color_desktop', array(
		'default'           => '#000000',
		'sanitize_callback' => 'sanitize_hex_color', 
		));

		$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $prefix . 'subtitle_color_desktop', array(
		'label'    => $section_title . ' Χρώμα Υποτίτλου για Desktop', 
		'section'  => 'homepage_attitude_options', 
		'settings' => $prefix . 'subtitle_color_desktop',
		)));

		$wp_customize->add_setting($prefix . 'subtitle_color_mobile', array(
		'default'           => '#000000',
		'sanitize_callback' => 'sanitize_hex_color', 
		));

		$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $prefix . 'subtitle_color_mobile', array(
		'label'    => $section_title . ' Χρώμα Υποτίτλου για Mobile', 
		'section'  => 'homepage_attitude_options', 
		'settings' => $prefix . 'subtitle_color_mobile',
		)));


                 $wp_customize->add_setting($prefix . 'titlestrong', array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ));

        $wp_customize->add_control($prefix . 'titlestrong', array(
            'label'   => $section_title . ' Τιτλος',
            'section' => 'homepage_attitude_options',
            'type'    => 'text',
        ));

		$wp_customize->add_setting($prefix . 'titlestrong_color_desktop', array(
		'default'           => '#ffffff',
		'sanitize_callback' => 'sanitize_hex_color', 
		));

		$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $prefix . 'titlestrong_color_desktop', array(
		'label'    => $section_title . ' Χρώμα Τιτλου Strong για Desktop', 
		'section'  => 'homepage_attitude_options', 
		'settings' => $prefix . 'titlestrong_color_desktop',
		)));
		
		$wp_customize->add_setting($prefix . 'titlestrong_color_mobile', array(
		'default'           => '#ffffff',
		'sanitize_callback' => 'sanitize_hex_color', 
		));

		$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $prefix . 'titlestrong_color_mobile', array(
		'label'    => $section_title . ' Χρώμα Τιτλου Strong για Mobile', 
		'section'  => 'homepage_attitude_options', 
		'settings' => $prefix . 'titlestrong_color_mobile',
		)));

          $wp_customize->add_setting($prefix . 'titleh1', array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ));

        $wp_customize->add_control($prefix . 'titleh1', array(
            'label'   => $section_title . ' Τιτλος h1 part',
            'section' => 'homepage_attitude_options',
            'type'    => 'text',
        ));

		$wp_customize->add_setting($prefix . 'titlesh1_color_desktop', array(
		'default'           => '#000000',
		'sanitize_callback' => 'sanitize_hex_color', 
		));

		$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $prefix . 'titlesh1_color_desktop', array(
		'label'    => $section_title . ' Χρώμα Τιτλου Strong για Desktop', 
		'section'  => 'homepage_attitude_options', 
		'settings' => $prefix . 'titlesh1_color_desktop',
		)));

		$wp_customize->add_setting($prefix . 'titlesh1_color_mobile', array(
		'default'           => '#000000',
		'sanitize_callback' => 'sanitize_hex_color', 
		));

		$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $prefix . 'titlesh1_color_mobile', array(
		'label'    => $section_title . ' Χρώμα Τιτλου Strong για Mobile', 
		'section'  => 'homepage_attitude_options', 
		'settings' => $prefix . 'titlesh1_color_mobile',
		)));


        $wp_customize->add_setting($prefix . 'description', array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_textarea_field',
        ));

        $wp_customize->add_control($prefix . 'Περιγραφή', array(
            'label'   => $section_title . ' Description',
            'section' => 'homepage_attitude_options',
            'type'    => 'textarea',
        ));

        $wp_customize->add_setting($prefix . 'section_url', array(
            'default'           => 'https://choiceattitude.com',
            'sanitize_callback' => 'esc_url_raw',
        ));

        $wp_customize->add_control($prefix . 'section_url', array(
            'label'   => $section_title . ' Url',
            'section' => 'homepage_attitude_options',
            'type'    => 'text',
        ));

       //$wp_customize->add_setting($prefix . 'transition', array(
         //   'default'           => 'left',
           // 'sanitize_callback' => 'sanitize_text_field',
        //));

        $wp_customize->add_control($prefix . 'transition', array(
            'label'   => $section_title . ' Κίνηση',
            'section' => 'homepage_attitude_options',
            'type'    => 'select',
            'choices' => array(
                'left'  => 'Left',
                'right' => 'Right',
                'down'  => 'Down',
                'up'    => 'Up',
            ),
        ));

            $wp_customize->add_setting($prefix . 'image_upload', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, $prefix . 'image_upload', array(
        'label'   => $section_title . ' Image Upload',
        'section' => 'homepage_attitude_options',
    )));

  $wp_customize->add_setting($prefix . 'image_upload_mobile', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, $prefix . 'image_upload_mobile', array(
        'label'   => $section_title . ' Image Upload Mobile',
        'section' => 'homepage_attitude_options',
    )));




 
        $wp_customize->add_setting('is_' . $prefix . 'active', array(
            'default'           => false,
            'sanitize_callback' => 'absint',
        ));

        $wp_customize->add_control('is_' . $prefix . 'active', array(
            'label'   => 'Είναι το ' . $section_title . ' Ενεργό?',
            'section' => 'homepage_attitude_options',
            'type'    => 'checkbox',
        ));
    }
}

 function customizer_example_get_menus() {
    $menus = get_terms('nav_menu', array('hide_empty' => false));
    $menu_choices = array();

    foreach ($menus as $menu) {
        $menu_choices[$menu->term_id] = $menu->name;
    }

    return $menu_choices;
}

add_action('customize_register', 'customizer_example_customize_register');


function customizer_example_echo_image_upload($section_prefix) {
    $image_url = get_theme_mod($section_prefix . 'image_upload', '');

    if (!empty($image_url)) {
        return 'url("' . esc_url($image_url) . '")';
    } else {
        return 'red';
    }
}


function customizer_example_echo_image_upload_mobile($section_prefix) {
    $image_url_mobile = get_theme_mod($section_prefix . 'image_upload_mobile', '');

    if (!empty($image_url_mobile)) {
        return 'url("' . esc_url($image_url_mobile) . '")';
    } else {
        return 'red';
    }
}


function custom_style_in_head() {
    ?>
    <style type="text/css">
       <?php build_sections_css_new() ?>
    </style>
    <?php
}
add_action('wp_head', 'custom_style_in_head');


function customizer_example_is_section_active($section_prefix) {
    return get_theme_mod('is_' . $section_prefix . 'active', false);
}

function isMobileDevice() {
    $useragent = $_SERVER['HTTP_USER_AGENT'];

    // Use a more reliable method, such as checking if the user agent contains "Mobile"
    return (bool) preg_match('/Mobile|iP(hone|od|ad)|Android|BlackBerry|IEMobile/', $useragent);
}

function build_sections() {


	echo '<div>'.do_action( 'botiga_header' ).'</div>';
	    
	    if (isMobileDevice()) {


		$menu_items = wp_get_nav_menu_items('home');

		$counter = 1; 
		foreach ($menu_items as $menu_item) {
    		 if ($counter >= 2 && $counter <= 5) {
         	$menu_item_urls[$counter] = $menu_item->url;
    	}

     	$counter++;

     	if ($counter > 5) {
        break;
    	}

		}

    	for ($i = 1; $i <= 5; $i++) {
	        $section_prefix = 'homepage_attitude_options_section' . $i . '_';
	        $custom_subtitle = get_theme_mod($section_prefix . 'subtitle', false);
	        $custom_title = get_theme_mod($section_prefix . 'titleh1', false);
	        $custom_strong = get_theme_mod($section_prefix . 'titlestrong', false);
            $custom_url = get_theme_mod($section_prefix . 'section_url', false);

//Desktop Colors
        $custom_subtitle_clr_desktop = get_theme_mod($section_prefix . 'subtitle_color_desktop', false);
        $custom_title_clr_desktop = get_theme_mod($section_prefix . 'titlesh1_color_desktop', false);
        $custom_strong_clr_desktop = get_theme_mod($section_prefix . 'titlestrong_color_desktop', false);
        //Mobile Colors
        $custom_subtitle_clr_mobile = get_theme_mod($section_prefix . 'subtitle_color_mobile', false);
        $custom_title_clr_mobile = get_theme_mod($section_prefix . 'titlesh1_color_mobile', false);
        $custom_strong_clr_mobile = get_theme_mod($section_prefix . 'titlestrong_color_mobile', false);
        if (customizer_example_is_section_active($section_prefix)) {
           	echo '<div class="pt-page pt-page-' . $i . '">';
			//do_action( 'botiga_header' );
			echo '<div class="logo"><img src="' . get_template_directory_uri() . '/images/logo_choice.png"/></div>';
			echo '<h1 class="custom_title_clr-'.$i.'" onclick="window.location.href=\'' . esc_url($custom_url) . '\'"><span class="custom_subtitle_clr-'.$i.'">' . esc_html($custom_subtitle) . ' </span><strong class="custom_strong_clr-'.$i.'">' . esc_html($custom_strong) . ' </strong> ' . esc_html($custom_title) . ' </h1></div>';

            } else {
            echo '<p>This section is not active.</p>';
        }
    }

	                        	}else{




	echo '<div class="pt-page pt-page-1 video">';


	echo '<div class="logo"><img src="'.get_template_directory_uri() .'/images/logo_choice.png"/></div>';
                             		 echo '<div class="video-container"><video loop autoplay muted playsinline>';

             		 echo '<source src="'.get_template_directory_uri() .'/images/video_desktop.mp4" type="video/mp4">';
     
    echo '</video>
	</div></div>';
	$menu_items = wp_get_nav_menu_items('home');

	$counter = 1; 
	foreach ($menu_items as $menu_item) {
     if ($counter >= 2 && $counter <= 5) {
         $menu_item_urls[$counter] = $menu_item->url;
    }

     $counter++;

     if ($counter > 5) {
        break;
    }
}

    for ($i = 2; $i <= 5; $i++) {
        $section_prefix = 'homepage_attitude_options_section' . $i . '_';
        $custom_subtitle = get_theme_mod($section_prefix . 'subtitle', false);
        $custom_title = get_theme_mod($section_prefix . 'titleh1', false);
        $custom_strong = get_theme_mod($section_prefix . 'titlestrong', false);
        $custom_url = get_theme_mod($section_prefix . 'section_url', false);

        //Desktop Colors
        $custom_subtitle_clr_desktop = get_theme_mod($section_prefix . 'subtitle_color_desktop', false);
        $custom_title_clr_desktop = get_theme_mod($section_prefix . 'titlesh1_color_desktop', false);
        $custom_strong_clr_desktop = get_theme_mod($section_prefix . 'titlestrong_color_desktop', false);
        //Mobile Colors
        $custom_subtitle_clr_mobile = get_theme_mod($section_prefix . 'subtitle_color_mobile', false);
        $custom_title_clr_mobile = get_theme_mod($section_prefix . 'titlesh1_color_mobile', false);
        $custom_strong_clr_mobile = get_theme_mod($section_prefix . 'titlestrong_color_mobile', false);
        if (customizer_example_is_section_active($section_prefix)) {
            		echo '<div class="pt-page pt-page-' . $i . '">';
				//do_action( 'botiga_header' );

 echo '<div class="logo"><img src="' . get_template_directory_uri() . '/images/logo_choice.png"/></div>';
echo '<h1 class="custom_title_clr-'. $i .'" onclick="window.location.href=\'' . esc_url($custom_url) . '\'"><span class="custom_subtitle_clr-'. $i .'">' . esc_html($custom_subtitle) . ' </span><strong class="custom_strong_clr-'. $i .'">' . esc_html($custom_strong) . ' </strong> ' . esc_html($custom_title) . ' </h1></div>';

                 } else {
            echo '<p>This section is not active.</p>';
        }
    }
}
}


// Call the function to build the sections

function build_sections_css_new() {
	
    for ($i = 1; $i <= 5; $i++) {
        $section_prefix = 'homepage_attitude_options_section' . $i . '_';
        $custom_subtitle_clr_desktop = get_theme_mod($section_prefix . 'subtitle_color_desktop', false);
        $custom_title_clr_desktop = get_theme_mod($section_prefix . 'titlesh1_color_desktop', false);
        $custom_strong_clr_desktop = get_theme_mod($section_prefix . 'titlestrong_color_desktop', false);
        //Mobile Colors
        $custom_subtitle_clr_mobile = get_theme_mod($section_prefix . 'subtitle_color_mobile', false);
        $custom_title_clr_mobile = get_theme_mod($section_prefix . 'titlesh1_color_mobile', false);
        $custom_strong_clr_mobile = get_theme_mod($section_prefix . 'titlestrong_color_mobile', false);
        if (customizer_example_is_section_active($section_prefix)) {

        	if (isMobileDevice()) {
             		echo '.pt-page-' . $i . '{background:'.customizer_example_echo_image_upload_mobile($section_prefix).';background-size:cover; width:100; height:100%; margin-top:40px;}';
             		echo '.custom_subtitle_clr-'.$i.'{color:'.$custom_subtitle_clr_mobile.'!important}';
					echo '.custom_title_clr-'.$i.'{color:'.$custom_title_clr_mobile.'!important}';
					echo '.custom_strong_clr-'.$i.'{color:'.$custom_strong_clr_mobile.'!important}';


} else {
    // The user is not using a mobile device
    if ($i != 1 ){
                 		echo '.pt-page-' . $i . '{background:'.customizer_example_echo_image_upload($section_prefix).';}';
	             		echo '.custom_subtitle_clr-'.$i.'{color:'.$custom_subtitle_clr_desktop.'!important}';
					echo '.custom_title_clr-'.$i.'{color:'.$custom_title_clr_desktop.'!important}';
					echo '.custom_strong_clr-'.$i.'{color:'.$custom_strong_clr_desktop.'!important}';
    }
}

 

        } else {
            		echo '.pt-page-' . $i . '{background:'.customizer_example_echo_image_upload($section_prefix).';}';
            		             		echo '.custom_subtitle_clr-'.$i.'{color:'.$custom_subtitle_clr_desktop.'!important}';
					echo '.custom_title_clr-'.$i.'{color:'.$custom_title_clr_desktop.'!important}';
					echo '.custom_strong_clr-'.$i.'{color:'.$custom_strong_clr_desktop.'!important}';
        }
    }

    $current_post_id = get_the_ID();

// Get the post object for the current post
$current_post = get_post($current_post_id);

// Get the post slug
$current_post_slug = $current_post->post_name;


            if ( is_front_page()) {


    echo '
        h1 {
            cursor: pointer;
        }
    .video-container {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: -1;
}

.video-container video {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
 @media screen and (min-width: 1024px) {
.slidingMenu {
    position: absolute;
    height: 410px;
    width: 455px;
    top: 100px;
    overflow: hidden;
    right: 1px;
    font-family: Arial,Helvetica,sans-serif;
}
#pt-main{margin-top:-40px;}

.header_layout_2{background:#fff0;}

.custom-logo-link{display:none;}

.site-header, .bottom-header-row {
    border-bottom: none;
}
}


@media screen and (max-width: 1023px) {
#pt-main{margin-top:inherit;}

.slidingMenu {
	display:none;
	}
	.custom-logo-link{display:block;}

	.slidingMenuDesc{	display:none;
}
	#pt-main{margin-top:0px!important;}

	.logo{display:none;}
	.header_layout_2{background:#fff;}

}';
            }else{


}
}


function menu_item_description_field($item_id, $item) {
    $menu_item_desc = get_post_meta($item_id, 'menu_item_custom_description', true);
    $menu_item_color = get_post_meta($item_id, 'menu_item_color', true);

    ?>
    <div style="clear: both;">custom_product_link_close
        <span class="description"><?php _e("Menu Item Description", 'menu-item-desc'); ?></span><br />
        <input type="hidden" class="nav-menu-id" value="<?php echo $item_id; ?>" />
        <div class="logged-input-holder">
            <input type="text" name="menu-item-desc[<?php echo $item_id; ?>]" id="menu-item-desc-<?php echo $item_id; ?>" value="<?php echo esc_attr($menu_item_desc); ?>" />
        </div>
    </div>
    <div style="clear: both;">
        <span class="description"><?php _e("Menu Item Color", 'menu-item-desc'); ?></span><br />
        <input type="hidden" class="nav-menu-id" value="<?php echo $item_id; ?>" />
        <div class="logged-input-holder">
            <input type="text" name="menu-item-color[<?php echo $item_id; ?>]" id="menu-item-color-<?php echo $item_id; ?>" value="<?php echo esc_attr($menu_item_color); ?>" />
        </div>
    </div>
    <?php
}

add_action('wp_nav_menu_item_custom_fields', 'menu_item_description_field', 10, 2);

function save_menu_item_description($menu_id, $menu_item_db_id, $menu_item_args) {
    if (defined('DOING_AJAX') && DOING_AJAX) {
        return;
    }

    check_admin_referer('update-nav_menu', 'update-nav-menu-nonce');

     if (isset($_POST['menu-item-desc'][$menu_item_db_id])) {
        $value = sanitize_text_field($_POST['menu-item-desc'][$menu_item_db_id]);
        if (!empty($value)) {
            update_post_meta($menu_item_db_id, 'menu_item_custom_description', $value);
        } else {
            delete_post_meta($menu_item_db_id, 'menu_item_custom_description');
        }
    }

     if (isset($_POST['menu-item-color'][$menu_item_db_id])) {
        $value = sanitize_text_field($_POST['menu-item-color'][$menu_item_db_id]);
        if (!empty($value)) {
            update_post_meta($menu_item_db_id, 'menu_item_color', $value);
        } else {
            delete_post_meta($menu_item_db_id, 'menu_item_color');
        }
    }
}

add_action('wp_update_nav_menu_item', 'save_menu_item_description', 10, 4);


function output_sliding_menu_with_descriptions($menu_location) {
     $menu_items = wp_get_nav_menu_items($menu_location);

    if (!$menu_items) {
        return;
    }
        if ( ! is_front_page() && ! is_home() ) {

$category = get_queried_object();
$thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
$image = wp_get_attachment_url( $thumbnail_id );
if ( $image ) {
	$html = '<div class="header header-category-background" style="background-image: url(' . esc_url( $image ) . ');">';
}else{
	$html = '<div class="header background-header" style="background:#333;">';

}

    $html .= '<div class="logo"><img src="'.get_template_directory_uri() .'/assets/images/logo_choice.svg"/></div>';
 
    }else{
    		$html = '<div class="header">';

    
    $html .= '<div id="slidingMenuDesc" class="slidingMenuDesc">';
    foreach ($menu_items as $menu_item) {
        $custom_description = get_post_meta($menu_item->ID, 'menu_item_custom_description', true);
 
        if ($custom_description) {
           $html .=  '<div><span>' .$custom_description.'</span></div>';
        }
    }
    $html .= '</div>';

     $html .= '<ul id="slidingMenu" class="slidingMenu">';
    foreach ($menu_items as $menu_item) {
        $html .= '<li><a href="' . esc_url($menu_item->url) . '">' . esc_html($menu_item->title) . '</a></li>';
    }
    }
    $html .= '</ul></div>';

    return $html;
}
 


 function is_current_product_category_empty() {
     $category = get_queried_object();

     return $category && isset($category->count) && $category->count === 0;
}



function add_custom_styles_if_category_empty() {
 if (is_current_product_category_empty()) {
            $custom_styles = '<style>.pswp { display: none; }</style>';
            
            echo $custom_styles;} 
       
    
}

add_action('wp_head', 'add_custom_styles_if_category_empty');

add_action( 'woocommerce_product_meta_start', 'add_content_after_start_product_meta' );

function add_content_after_start_product_meta() {
    // Add your content here
    echo '<div class="share"><ul class="social-share-buttons">
    <li>
        <button onclick="shareOnFacebook()">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path d="M504 256C504 119 393 8 256 8S8 119 8 256c0 123.8 90.7 226.4 209.3 245V327.7h-63V256h63v-54.6c0-62.2 37-96.5 93.7-96.5 27.1 0 55.5 4.8 55.5 4.8v61h-31.3c-30.8 0-40.4 19.1-40.4 38.7V256h68.8l-11 71.7h-57.8V501C413.3 482.4 504 379.8 504 256z"/>
            </svg>
            Share on Facebook
        </button>
    </li>
    <li>
        <button onclick="shareOnMessenger()">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path d="M256.6 8C116.5 8 8 110.3 8 248.6c0 72.3 29.7 134.8 78.1 177.9 8.4 7.5 6.6 11.9 8.1 58.2A19.9 19.9 0 0 0 122 502.3c52.9-23.3 53.6-25.1 62.6-22.7C337.9 521.8 504 423.7 504 248.6 504 110.3 396.6 8 256.6 8zm149.2 185.1l-73 115.6a37.4 37.4 0 0 1 -53.9 9.9l-58.1-43.5a15 15 0 0 0 -18 0l-78.4 59.4c-10.5 7.9-24.2-4.6-17.1-15.7l73-115.6a37.4 37.4 0 0 1 53.9-9.9l58.1 43.5a15 15 0 0 0 18 0l78.4-59.4c10.4-8 24.1 4.5 17.1 15.6z"/>
            </svg>
            Share on Messanger
        </button>
    </li>
    <li>
        <button onclick="copyProductURL()">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 460 460">
                <g>
                    <g>
                        <g>
                            <path d="M425.934,0H171.662c-18.122,0-32.864,14.743-32.864,32.864v77.134h30V32.864c0-1.579,1.285-2.864,2.864-2.864h254.272     c1.579,0,2.864,1.285,2.864,2.864v254.272c0,1.58-1.285,2.865-2.864,2.865h-74.729v30h74.729     c18.121,0,32.864-14.743,32.864-32.865V32.864C458.797,14.743,444.055,0,425.934,0z"/>
                            <path d="M288.339,139.998H34.068c-18.122,0-32.865,14.743-32.865,32.865v254.272C1.204,445.257,15.946,460,34.068,460h254.272     c18.122,0,32.865-14.743,32.865-32.864V172.863C321.206,154.741,306.461,139.998,288.339,139.998z M288.341,430H34.068     c-1.58,0-2.865-1.285-2.865-2.864V172.863c0-1.58,1.285-2.865,2.865-2.865h254.272c1.58,0,2.865,1.285,2.865,2.865v254.273h0.001     C291.206,428.715,289.92,430,288.341,430z"/>
                        </g>
                    </g>
                </g>
            </svg>
            Copy to Clipboard
        </button>
    </li>
</ul></div>';
}
/* = Customize login page  ---------------------------------------------------- */
function sgwp_custom_login_logo(){
  echo
   "<style type='text/css'>
   body.login { background-color: #fff; }
   body.login form { padding-bottom: 24px; }
   body.login h1 a { background-image: url(". get_site_url() ."/wp-content/uploads/2023/12/cropped-CHOIce3.png);
   background-size: 250px 59px; width: 250px; height: 59px; }
   body.login p#backtoblog, body.login p#nav { display: none; }
   </style>";
}
function sgwp_custom_login_url(){
  return get_option('home');
}

function sgwp_custom_login_title(){
  return get_option('blogname');
}

add_action('login_head', 'sgwp_custom_login_logo');
add_filter('login_headerurl', 'sgwp_custom_login_url');
add_filter('login_headertext', 'sgwp_custom_login_title');


function is_login_page(){
  return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
}
/* = End Customize login page  ---------------------------------------------------- */
/*= Remove version number & Rss */
remove_action('wp_head', 'wp_generator');
function remove_wp_version() { return '';}
add_filter('the_generator', 'remove_wp_version');

