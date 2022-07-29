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

function contenido_media(id) {
    //console.log("media id:", id);
    cambio_de_estado(id);
    $.ajax({
        url: '/clientes/cosultas/get/media',
        type: 'POST',
        data: { id: id },
        success: function(data) {
            //console.log(data);
            let html = '';

            data.forEach((row, index) => {

                let htmlArchivo = ``

                if (row.file) {
                    htmlArchivo = `
                    <div class="file_folder">
                        <a href="/storage/${ row.file }" target="_blank">
                            <div class="icon">
                                <i class="fa fa-file-o"></i>
                            </div>
                            <div class="file-name">
                                <p class="mb-0 text-muted">${ (row.file).split("/").pop() }</p>
                                <small>Size: 68KB</small>
                            </div>
                        </a>
                    </div>
                    `
                }

                html += `
                    <div class="card-body detail">
                        <div class="detail-header">
                            <h4>${ row.asunto }</h4>
                            <div class="media">
                                <div class="media-body">
                                    <p class="mb-0"><strong class="text-muted mr-1">From:</strong><a href="javascript:void(0);">${ row.name }</a><span class="text-muted text-sm float-right">${ row.created_at }</span></p>
                                    <p class="mb-0"><strong class="text-muted mr-1">To:</strong>${ row.nombre } <small class="float-right"><i class="fe fe-paperclip mr-1"></i>(2 files, 89.2 KB)</small></p>                                        
                                </div>
                            </div>
                        </div>
                        <div class="mail-cnt">
                            <p>${ row.mensaje }</p><br>
                            <hr>
                            ` + htmlArchivo + `
                        </div>
                    </div>
                `;
            });

            $('#contenido_email').html(html);
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#contenido_email").offset().top - 100
            }, 500);
        }
    })
}

function cambio_de_estado(id) {
    $.ajax({
        url: '/clientes/cosultas/estado',
        type: 'POST',
        data: { id: id },
        success: function(data) {
            console.log(data);
        }
    })
}