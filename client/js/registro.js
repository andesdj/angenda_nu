$(document).ready(function () {
    $('#registro').on('click', function (event) {
        validar_usuario();
        event.preventDefault();
    });

    $('#cancelar').on('click', function () {
        window.location.href = '../client/index.html'
    })
});

function validar_usuario() {
    var usuario = $('#usuario').val();
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if(usuario != "") {
        var valida = re.test(usuario);
        if(valida == true) {
            registra_usuario()
        } else {
            alert('El usuario debe ser una cuenta de correo válida');
        }
    } else {
        alert('No se ingresó usuario');
    }
}

function registra_usuario(e) {
    let form_data = new FormData();
    form_data.append('nombre_completo', $("#nombre_completo").val());
    form_data.append('usuario',  $("#usuario").val());
    form_data.append('contrasenia', $("#contrasenia").val());

    $.ajax({
        url: '../server/create_user.php',
        dataType: 'json',
        cache: false,
        processData: false,
        contentType: false,
        data: form_data,
        type: 'POST',
        success: (response) => {
            if(response.mensaje === "OK") {
                alert('Usuario agregado');
                window.location.href = '../client/index.html';
            } else {
                alert('Estoy en el else:' + response.mensaje);
            }
        },
        error:  function(response)  {
            alert('Error al agregar usuario por: ' + JSON.stringify(response));
            location.reload();
        },
    });
}