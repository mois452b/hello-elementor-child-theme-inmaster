<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

include 'templates/shortcode_students_list.php';
include 'templates/random_quiz_questions.php';
include 'do_enroll.php';

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );
         
if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_child', trailingslashit( get_stylesheet_directory_uri() ) . 'style.css', array( 'hello-elementor','hello-elementor','hello-elementor-theme-style' ) );

        wp_register_script( 'shortcode_students_list_script', get_stylesheet_directory_uri( ) . '/js/students_list.js' );
        wp_localize_script( 'shortcode_students_list_script', 'ajax_var', array(
            'url'    => admin_url( 'admin-ajax.php' ),
            'nonce'  => wp_create_nonce( 'my-ajax-nonce' ),
            'actions' => array(
                'enroll' => 'enroll_students',
                'get_course_students' => 'get_course_students'
                
            )
        ) );
    }
endif;

function tutor_child_before_add_to_cart( ) {
    if( post_has_taxonomy_slug( get_the_ID( ), 'course-category', 'talleres-presenciales' ) ) {
    ?>
        <div class="pretty <?php echo esc_attr( cidw_get_option( 'cidw_radio_theme' ) ); ?>">
            <input type="radio" name="deposit-mode" value="check_deposit" checked >
        </div>
    <?php
    }
}

if ( !function_exists( 'shortCodes_init' ) ) {
    function shortCodes_init( ) {
        add_action( 'tutor_child_before_add_to_cart', 'tutor_child_before_add_to_cart' );
        add_shortcode( 'list_students', 'list_students' );
    }
};

function post_has_taxonomy_slug( $post_id, $taxonomy, $slug ) {
    $course_categories = get_the_terms( $post_id, $taxonomy ) ? get_the_terms( $post_id, $taxonomy ) : [];	
    $course_categories_slug = array_map( function( $element ) {
        return $element->slug;
    }, $course_categories );
    return in_array( $slug, $course_categories_slug );
}

function head( ) {
    echo '<script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>';
    echo '<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.css">';
    echo '<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.js"></script>';
}

add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 100 );

add_action( 'init', 'shortCodes_init' );

add_action( 'wp_head', 'head' );

add_action( 'wp_ajax_enroll_students', 'enroll_students' );
add_action( 'wp_ajax_nopriv_enroll_students', 'enroll_students' );

function events_endpoint() {
    register_rest_route( 'v1/', 'get_course_students/', array(
        'methods'  => 'get',
        'callback' => 'get_course_students',
    ) );
}


add_action( 'wp_ajax_get_course_students', 'get_course_students' );
add_action( 'wp_ajax_nopriv_get_course_students', 'get_course_students' );

// END ENQUEUE PARENT ACTION
