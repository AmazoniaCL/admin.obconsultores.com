$(document).ready(function () {

});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function sincronizar() {
    $('#btn-sincronizar').html(`
        <div class="spinner-border text-light" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    `)
    .prop('disabled', true);

    $.ajax({
		url: window.location.host + '/sincronizar-procesos',
        type: 'POST',
        dataType: 'json',
        cache: false,
		success: function (data) {
            if(data == 0) $('#alert-empty').show(350);
            else window.location.href = '/administrador/sincronizacion/' + data;
		},
        complete: function() {
            $('#btn-sincronizar').html(`<span>SINCRONIZAR</span>`).prop('disabled', false);
        }
    });
}
