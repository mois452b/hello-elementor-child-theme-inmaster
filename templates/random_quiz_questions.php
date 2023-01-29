<?php

function getCourses( ) {
    // URL del endpoint
    $endpoint = site_url( ) . '/wp-json/tutor/v1/courses?order=desc&orderby=ID&paged=1';
    // Llamada a la API
    $response = wp_remote_get($endpoint);
    // Si la llamada fue exitosa
    if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) == 200) {
        // Decodificar la respuesta JSON
        $data = json_decode(wp_remote_retrieve_body($response), false);
        return $data->data->posts;
    }
    return [];
}
function getCourseTopics( $course_id ) {
    $endpoint = site_url( ) . '/wp-json/tutor/v1/course-topic/'.$course_id;
    $response = wp_remote_get($endpoint);
    if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) == 200) {
        $data = json_decode(wp_remote_retrieve_body($response), false);
        return $data->data;
    }
    return [];
}
function getTopicQuizes( $topic_id ) {
    $endpoint = site_url( ) . '/wp-json/tutor/v1/quiz/'.$topic_id;
    $response = wp_remote_get($endpoint);
    if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) == 200) {
        $data = json_decode(wp_remote_retrieve_body($response), false);
        return $data->data;
    }
    return [];
}
function getQuestionsQuiz( $quiz_id ) {
    $endpoint = site_url( ) . '/wp-json/tutor/v1/quiz-question-answer/'.$quiz_id;
    $response = wp_remote_get($endpoint);
    if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) == 200) {
        $data = json_decode(wp_remote_retrieve_body($response), false);
        return $data->data;
    }
    return [];
}

function getAllQuestions() {
    $courses = getCourses();
    $questions = array();
    foreach ($courses as $course) {
        $topics = getCourseTopics($course->ID);
        foreach ($topics as $topic) {
            $quizes = getTopicQuizes($topic->ID);
            foreach ($quizes as $quiz) {
                // $quiz_questions = getQuestionsQuiz($quiz->ID);
                $quiz_questions = tutor_utils()->get_questions_by_quiz($quiz->ID);
                // $questions[] = $quiz;
                // $questions[] = $quiz_questions;
                array_filter( $quiz_questions );

                
                foreach ($quiz_questions as $question) {
                    $questions[] = $question;
                }
            }
        }
    }
    return $questions;
}

function getMblexQuestions( $course_id, $num_questions_per_quiz ) {
    $questions = array();
    $topics = getCourseTopics( $course_id );
    foreach ($topics as $topic) {
        $topic_questions = array( );
        $quizes = getTopicQuizes($topic->ID);
        foreach ($quizes as $quiz) {
            // $quiz_questions = getQuestionsQuiz($quiz->ID);
            $quiz_questions = tutor_utils()->get_questions_by_quiz($quiz->ID);
            // $questions[] = $quiz;
            // $questions[] = $quiz_questions;
            array_filter( $quiz_questions );

            
            foreach ($quiz_questions as $question) {
                $topic_questions[] = $question;
            }
        }
        array_push( $questions, ...array_slice( $topic_questions, 0, $num_questions_per_quiz ) );
    }
    return $questions;
}