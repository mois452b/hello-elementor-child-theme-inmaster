<?php

function render_students_list( $course_id ) {
    $students = tutor_utils( )->get_students( 0, 1000, '', $course_id );
    $datas = [];
    foreach( $students as $student ) {
        $user_meta = get_user_meta( $student->ID );
        $user_data = [];
        if( isset( $user_meta['enable_final_quiz'] ) ) {
            $user_data = json_decode( $user_meta['enable_final_quiz'][0] );
        }
        $present = in_array( $course_id, $user_data );
        $class = $present ? 'present' : '';
        $checked = $present ? 'checked' : '';
        $txt = $present ? 'P' : 'A';
        $datas[] = [ $student->ID, $student->display_name, "<div class='$class' data-value='$student->ID' >$txt</div>
                                                        <input  type='checkbox' 
                                                                name='presented_students[]' 
                                                                class='checkbox-$student->ID' 
                                                                value='$student->ID' $checked >" ];
    }
    ?>
    <div class="students_list" >
        <form method="post" id="students_form">
            <table id="students_list_table">
                <thead>
                    <tr>
                        <th><span class="student-name">ID</span></th>
                        <th><span class="student-name">Nombre</span></th>
                        <th><span class="student-name">Estado</span></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <input class="course-id" type="hidden" value="<?= $course_id ?>" >
            <input class="button" type="submit" value="Confirmar" >
        </form>
        <script>
            let datas = <?= json_encode( $datas ); ?>;
            $('#students_list_table').DataTable( {
                data: datas
            } );
        </script>
    </div>
<?php
}