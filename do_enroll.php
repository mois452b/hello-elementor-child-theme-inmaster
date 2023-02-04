<?php
/**
 * habilita el quiz final de un curso especifico de los estudiantes que han asistido al taller presencial
 */
function enroll_students() {
    // Verificamos que los parametros necesarios esten presentes en la peticion
    if( !isset( $_REQUEST['students_data'] ) || !isset( $_REQUEST['course'] ) ){
        throw new Exception("no se han pasado los parametros suficientes", 1);
        wp_die();
    }
    // Obtenemos los datos de los estudiantes del curso
    $students_data = $_REQUEST['students_data'];
    // Obtenemos el ID del curso al que se refiere la petición
    $course_id = $_REQUEST['course'];
    foreach( $students_data as $student ) {
        // Obtenemos id del estudiante actual
        $student_id = $student['student_id'];
        // Obtenemos el estado del estudiante ( presente-true o ausente-false )
        $attendance = $student['attendance'];
        // verificamos que exista el usuario antes de trabajar con sus metadatos
        if( !get_user_by( 'id', $student_id ) ) continue;
    
        $user_meta = get_user_meta( $student_id );
        $user_data = [];
        // Si el usuario ya tiene una meta "enable_final_quiz Decodificamos la meta en formato JSON a un array
        if( isset( $user_meta['enable_final_quiz'] ) ) {
            $user_data = json_decode( $user_meta['enable_final_quiz'][0] );
        }
        if( $attendance == 'true' ) {
            // Si estuvo presente, agregamos el curso al user_meta
            !in_array( $course_id, $user_data ) ? array_push( $user_data, $course_id ) : null;
        } else {
            // Si estuvo ausente, verificamos si el curso ya se encuentra en el user_meta
            if (in_array( $course_id, $user_data )) {
                // Si se encuentra, lo eliminamos
                $key = array_search( $course_id, $user_data );
                unset( $user_data[$key] );
                // Reordenamos el arreglo
                $user_data = array_values($user_data);
            }
        }
        // Actualizamos la meta del usuario con el nuevo array de cursos
        update_user_meta( $student_id, 'enable_final_quiz', json_encode($user_data) );
    }
    // Devolvemos los estudiantes inscritos en formato JSON
    echo json_encode($students_data);
    // Terminamos la ejecución de la función
    wp_die();
}


function get_course_students( $course_id ) {
    // $course_id = $_REQUEST['course'];
    $students = tutor_utils( )->get_students( 0, 1000, '', $course_id );
    echo ( 'course_id' );
    wp_die();
}