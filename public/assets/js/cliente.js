$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('#identificacion').blur(function () {
    var id = $('#identificacion').val()

    $.ajax({
        url: '/procesos/searh/'+id,
        type: 'get',
        success: function (data) {
            if (data[0]) {
                $('#identificacion').addClass('is-invalid')
            } else {
                $('#identificacion').removeClass('is-invalid')
            }
        }
    });
})

function habilitar_formularo_cliente() {
    $('#identificacion').prop("readonly", false);
    $('#nombre').prop("readonly", false);
    $('#direccion').prop("readonly", false);
    $('#telefono').prop("readonly", false);
    $('#celular').prop("readonly", false);
    $('#correo').prop("readonly", false);
    $('#correo_dos').prop("readonly", false);
    $('#identificacion_representante').prop("readonly", false);
    $('#nombre_representante').prop("readonly", false);
    $('#direccion_representante').prop("readonly", false);
    $('#celular_representante').prop("readonly", false);
    $('#eps').prop("disabled", false);
    $('#arl').prop("disabled", false);
    $('#afp').prop("disabled", false);
    $('#cedula').prop("disabled", false);
    $('#contrato').prop("disabled", false);
    $('#poder').prop("disabled", false);
    $('#titulo_valor').prop("disabled", false);

    $('#section_cedula').addClass('d-none');
    $('#input_cedula').removeClass('d-none');

    $('#section_contrato').addClass('d-none');
    $('#input_contrato').removeClass('d-none');

    $('#section_poder').addClass('d-none');
    $('#input_poder').removeClass('d-none');

    $('#section_titulo_valor').addClass('d-none');
    $('#input_titulo_valor').removeClass('d-none');

    $('#btn_habilitar_actualizar_cliente').addClass('d-none');
    $('#btn_enviar_actualizar_cliente').removeClass('d-none');
    $('#btn_cancelar_actualizar_cliente').removeClass('d-none');
}

function deshabilitar_formularo_cliente() {
    $('#identificacion').prop("readonly", true);
    $('#nombre').prop("readonly", true);
    $('#direccion').prop("readonly", true);
    $('#telefono').prop("readonly", true);
    $('#celular').prop("readonly", true);
    $('#correo').prop("readonly", true);
    $('#correo_dos').prop("readonly", true);
    $('#identificacion_representante').prop("readonly", true);
    $('#nombre_representante').prop("readonly", true);
    $('#direccion_representante').prop("readonly", true);
    $('#celular_representante').prop("readonly", true);
    $('#eps').prop("disabled", true);
    $('#arl').prop("disabled", true);
    $('#afp').prop("disabled", true);
    $('#cedula').prop("disabled", true);
    $('#contrato').prop("disabled", true);
    $('#poder').prop("disabled", true);
    $('#titulo_valor').prop("disabled", true);

    $('#section_cedula').removeClass('d-none');
    $('#input_cedula').addClass('d-none');

    $('#section_contrato').removeClass('d-none');
    $('#input_contrato').addClass('d-none');

    $('#section_poder').removeClass('d-none');
    $('#input_poder').addClass('d-none');

    $('#section_titulo_valor').removeClass('d-none');
    $('#input_titulo_valor').addClass('d-none');

    $('#btn_habilitar_actualizar_cliente').removeClass('d-none');
    $('#btn_enviar_actualizar_cliente').addClass('d-none');
    $('#btn_cancelar_actualizar_cliente').addClass('d-none');
}

function eliminar_cliente(id) {
    if (window.confirm("¿Seguro desea eliminar el cliente?")) {
        $.ajax({
            url: '/clientes/delete',
            type: 'post',
            data: {id:id},
            success: function (data) {
                $('#delete_confirmed').removeClass('d-none')
                setTimeout(function(){ window.location.href = '/clientes'; }, 600);
            }
        });
    }
}

$('#form_enviar_mensaje').submit(function () {
    $('#btn_enviar_mensaje').html('<span class="spinner-border spinner-border-sm"></span>');

    let mensaje_temp = $('.note-editable').html();

    $('#mensaje').val(mensaje_temp);
});

function habilitar_formularo_correo() {
    $('#form_enviar_mensaje').fadeIn(1000)
}

$('#search_cliente').keypress(function(e) {
    var keycode = (e.keyCode ? e.keyCode : e.which);
    if (keycode == '13') {
        event.preventDefault();
        document.location.href = '/clientes/search/'+$('#search_cliente').val()
    }
});

$('#search_proceso').keypress(function(e) {
    var keycode = (e.keyCode ? e.keyCode : e.which);
    if (keycode == '13') {
        event.preventDefault();
        // document.location.href = '/clientes/search/'+$('#search_cliente').val()
        $('#form_search_proceso').submit();
    }
});

function select_tipo_persona(tipo) {
    if (tipo == 'Juridica') {
        $('#tipo_cliente_label').text('Nit.');
        $('#verificacion').removeClass('d-none');
        $('#section_representante').removeClass('d-none');
        $('#section_representante_hr').removeClass('d-none');
    } else {
        $('#tipo_cliente_label').text('Identificacion');
        $('#verificacion').addClass('d-none');
        $('#section_representante').addClass('d-none');
        $('#section_representante_hr').addClass('d-none');
    }
}

function confirmarDelete(url) {
    if(confirm('¿Seguro desea eliminar el registro?')) {
        window.location.href = url;
    }
}
