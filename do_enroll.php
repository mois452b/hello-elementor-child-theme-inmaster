<?php

function events_endpoint() {
    register_rest_route( 'v1/register', '/enroll', array(
        'methods'  => 'GET',
        'callback' => 'enroll_students',
    ) );
    register_rest_route( 'v1/register', '/enroll', array(
        'methods'  => 'GET',
        'callback' => 'enroll_students',
    ) );
}

function enroll_students( ) {
    $students = $_REQUEST['students'];
    $course_id = $_REQUEST['course'];
    $enrolled_students = 1;
    foreach( $students as $student_id ) {
        $user_meta = get_user_meta( $student_id );
        $user_data = [];
        if( isset( $user_meta['enable_final_quiz'] ) ) {
            $user_data = json_decode( $user_meta['enable_final_quiz'][0] );
        }
        !in_array( $course_id, $user_data ) ? array_push( $user_data, $course_id ) : null;
        $boole = update_user_meta( $student_id, 'enable_final_quiz', json_encode($user_data) );
        $enrolled_students++;
    }
    echo json_encode($students);
    wp_die();
}
