$(document).ready(function () {

});

$.ajaxSetup({
    headers: {
        'Accept': 'application/json, text/plain, */*',
        'Accept-Language': 'es-419,es;q=0.9',
    }
});

function sincronizar() {
	$.ajax({
		url: 'https://consultaprocesos.ramajudicial.gov.co/api/v2/Procesos/Consulta/NumeroRadicacion?SoloActivos=false&numero=41001408800720200013000',
        type: 'GET',
		success: function (data) {
			console.log(data);
		}
    })
}
