$(document).ready(function() {
    // summernote editor
    $('.summernote').summernote({
        lang: 'es-ES',
        height: 280,
        focus: true,
        onpaste: function() {
            alert('You have pasted something to the editor');
        }
    });

    $('.inline-editor').summernote({
        airMode: true
    });

    $('#form_enviar_Consulta').submit(function () {
        let content = $('.note-editable').html();
        $('#mensaje').val(content);
    });
})
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function contenido_media(id, el = null) {
    $('.offline').each(function (index, el) {
        $(el).removeClass('active');
    });

    $(el).parent().addClass('active');

    //console.log("media id:", id);
    cambio_de_estado(id);
    $.ajax({
        url: '/clientes/cosultas/get/media',
        type: 'POST',
        data: { id: id },
        success: function(data) {
            //console.log(data);
            let html = '';
            let mensajes = '';
            let archivos ='';
            let count_archivos = 0;

            data.mensajes.forEach((row, index) => {
                row.adjuntos.forEach((adjuntos, index)=>{
                   archivos += `
                    <a href="/storage/${ adjuntos.file }" target="_blank">
                        <div class="icon">
                            <i class="fa fa-file-o"></i>
                        </div>
                        <div class="file-name">
                            <p class="mb-0 text-muted">${ adjuntos.nombre }</p>
                        </div>
                    </a>
                   `;
                   count_archivos +=1;
                });

                mensajes +=`<div class="mail-cnt p-0 m-0">
                                <div class="detail-header">
                                    <div class="media">
                                        <div class="media-body">
                                            <p class="mb-0"><strong class="text-muted mr-1">Enviado por:</strong><a href="javascript:void(0);">${ row.user.name }</a><span class="text-muted text-sm float-right">${ moment(row.updated_at).format('YYYY/MM/DD h:mm:ss a') }</span></p>
                                        </div>
                                    </div>
                                </div>

                                <p>${row.mensaje}</p><br>
                                <div class="file_folder">
                                    ` + archivos + `
                                </div>
                                <hr>
                            </div>`;
                archivos ='';
            });

            html += `
                <div class="card-body detail">
                    <div class="detail-header">
                        <h4>${ data.asunto }</h4>
                    </div>
                    `+mensajes+`
                </div>
            `;

            $('#contenido_email').html(html);
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#contenido_email").offset().top - 100
            }, 500);
            document.getElementById("mensaje_id").value = data.id;
            document.getElementById('formulario_respuesta').style.display = '';
        }
    })
}

function cambio_de_estado(id) {
    $.ajax({
        url: '/clientes/cosultas/estado',
        type: 'POST',
        data: { id: id },
        success: function(data) {
            //console.log(data);
        }
    })
}
