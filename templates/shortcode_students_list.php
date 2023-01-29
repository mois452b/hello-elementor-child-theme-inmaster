<?php
include 'students_list.php';
include 'select_course.php';

if ( !function_exists( 'list_students' ) ) {
    function list_students( $attr, $content ) {
        // Get courses with the "talleres-presenciales" category
		$courses = get_posts( array(
		  'post_type'   => 'courses',
		  'post_status' => 'publish',
		  'numberposts' => -1,
		  'tax_query'   => array(
				array(
				  'taxonomy' => 'course-category',
				  'field'    => 'slug',
				  'terms'    => 'talleres-presenciales',
				),
		  ),
		) );
		
		ob_start();
		?>
		<div id="shortcode_students_list" >
            <?php
                if( isset( $_POST['id_course_students_list'] ) && $_POST['id_course_students_list']!='' ) {
					wp_enqueue_style('shortcode_students_list_style', get_stylesheet_directory_uri() . '/styles/students_list.css');
                    $students = tutor_utils( )->get_students( 0, 1000, '', $_POST['id_course_students_list'] );
                    render_students_list( $students, $_POST['id_course_students_list'] );
					wp_enqueue_script( 'shortcode_students_list_script' );

                }
                else {
					wp_enqueue_style('shortcode_select_course_style', get_stylesheet_directory_uri() . '/styles/select_course.css');
                    render_select_course( $courses );
				}
            ?>
			
		</div>
		    <?php
            $content = ob_get_clean();
            return $content; // Devolver la salida.
    }
};