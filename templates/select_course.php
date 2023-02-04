<?php

function render_select_course( $courses ) {
?> 
    <div class="select_course" >
        <form method="post" >
            <div class="select-content">
                <select name="id_course_students_list" id="id_course_students_list">
                    <option value=""><?php _e( '-- Select a course --', 'your-text-domain' ); ?></option>
                    <?php
                        // Mostrar las opciones del formulario.
                        foreach( $courses as $course ) {
                            printf( '<option value="%d"%s>%s</option>',
                                    $course->ID,
                                    selected( $course_id, $course->ID, false ),
                                    $course->post_title
                                    );
                        }
                    ?>
                </select>	
            </div>
            
            <input class="send button" type="submit" value="Confirmar" />
        </form>
    </div>
<?php
}