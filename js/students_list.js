(function update_student_status( ) {
    let elements = document.querySelectorAll('#shortcode_students_list div.students_list div')
    elements.forEach( element => {
        element.onclick = event => {
            let target = event.target
            let checkbox = document.querySelector('#shortcode_students_list div.students_list tr input.checkbox-'+target.dataset.value)
            target.classList.toggle('present')
            if( target.classList.contains('present') ) {
                target.setAttribute( 'name', 'presented_students[]' )
                checkbox.checked = true;
            }
            else {
                target.setAttribute( 'name', '' )
                checkbox.checked = false;
            }
        }
    })
})()

function send_form( ) {
     // Selecciona el formulario
     var form = document.getElementById("students_form");

     // Agrega un controlador para el evento de envío del formulario
     form.addEventListener("submit", function(event) {
 
         // Recolecta los valores de los estudiantes seleccionados
         let students = [];
         var checkboxes = document.querySelectorAll("#students_form input[type='checkbox']:checked");
         var course_id = document.querySelector("#students_form input[type='hidden']").value;
         checkboxes.forEach( element => {
            students.push( element.value );
         })
 
         // Envia la solicitud al servidor con los estudiantes seleccionados
         console.log(students)
        $.ajax({
            type: "post" ,
            url: ajax_var.url,
            data: {
                action: ajax_var.action,
                nonce: ajax_var.nonce,
                students: students,
                course: course_id
            },
            success: function( response ) {
                console.log(response)
            }
        })
     });
 
 
 
 
 
}
send_form()