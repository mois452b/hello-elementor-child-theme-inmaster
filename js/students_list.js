function update_student_status( ) {
    let elements = document.querySelectorAll('#shortcode_students_list div.students_list tr div')
    elements.forEach( element => {
        element.onclick = event => {
            let target = event.target
            let checkbox = document.querySelector('#shortcode_students_list div.students_list tr input.checkbox-'+target.dataset.value)
            target.classList.toggle('present')
            if( target.classList.contains('present') ) {
                target.setAttribute( 'name', 'presented_students[]' )
                target.innerHTML = "P"
                checkbox.checked = true;
            }
            else {
                target.setAttribute( 'name', '' )
                target.innerHTML = "A"
                checkbox.checked = false;
            }
        }
    })
}
document.addEventListener( 'DOMContentLoaded', update_student_status );

function send_form( ) {
     // Selecciona el formulario
     var form = document.getElementById("students_form");

     // Agrega un controlador para el evento de envío del formulario
     form.addEventListener("submit", function(event) {
        event.preventDefault()
 
         // Recolecta los valores de los estudiantes seleccionados
         let students = [];
         var checkboxes = document.querySelectorAll("#students_form input[type='checkbox']");
         var course_id = document.querySelector("#students_form input.course-id[type='hidden']").value;
         checkboxes.forEach( element => {
            students.push( { student_id:element.value, attendance:element.checked } );
         })
         
         // Envia la solicitud al servidor con los estudiantes
        $.ajax({
            type: "post",
            url: ajax_var.url,
            data: {
                action: ajax_var.actions.enroll,
                nonce: ajax_var.nonce,
                students_data: students,
                course: course_id
            },
            success: function( response ) {
                response = JSON.parse(response)
                console.log(response)
            },
            error: function( error ) {
                console.log(error)
            }
        })
     });
 
}
send_form()