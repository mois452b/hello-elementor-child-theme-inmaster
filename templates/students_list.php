<?php

function render_students_list( $students, $course_id ) {
?> 
    <div class="students_list" >
        <form method="post" id="students_form">
            <table>
                <thead>
                    <tr>
                        <th><span class="student-name">Nombre</span></th>
                        <th><span class="student-name">Estado</span></th>
                    </tr>
                </thead>
                <tbody>
            <?php
                foreach ( $students as $student ) {
                    $user_meta = get_user_meta( $student->ID );
                    $user_data = [];
                    if( isset( $user_meta['enable_final_quiz'] ) ) {
                        $user_data = json_decode( $user_meta['enable_final_quiz'][0] );
                    }
                    $present = in_array( $course_id, $user_data );
                    $class = $present ? 'present' : '';
                    // Añadir una fila para cada estudiante.
                    echo '<tr>';
                    echo 	'<td><span class="student-name">' . $student->display_name . '</span></td>';
                    echo 	"<td><div class='$class' data-value='$student->ID' >P</div>";
                    echo 		"<input type='checkbox' name='presented_students[]' class='checkbox-$student->ID' value='$student->ID' ></td>";
                    echo '</tr>';
                }
            ?>
                </tbody>
            </table>
			<?php
				if( count( $students ) == 0 ) {
					echo '<div>sin estudiantes registrados</div>';
				}
			?>
            <input type="hidden" value="<?= $course_id ?>" >
            <input type="submit" value="Confirmar" >
        </form>
    </div>
<?php
}