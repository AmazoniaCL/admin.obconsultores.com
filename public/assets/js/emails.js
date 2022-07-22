$(document).ready(function() {

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
                            <div class="file_folder">
                                <a href="javascript:void(0);">
                                    <div class="icon">
                                        <i class="fa fa-file-excel-o text-success"></i>
                                    </div>
                                    <div class="file-name">
                                        <p class="mb-0 text-muted">Report2017.xls</p>
                                        <small>Size: 68KB</small>
                                    </div>
                                </a>
                                <a href="javascript:void(0);">
                                    <div class="icon">
                                        <i class="fa fa-file-word-o text-primary"></i>
                                    </div>
                                    <div class="file-name">
                                        <p class="mb-0 text-muted">Report2017.doc</p>
                                        <small>Size: 68KB</small>
                                    </div>
                                </a>
                                <a href="javascript:void(0);">
                                    <div class="icon">
                                        <i class="fa fa-file-pdf-o text-danger"></i>
                                    </div>
                                    <div class="file-name">
                                        <p class="mb-0 text-muted">Report2017.pdf</p>
                                        <small>Size: 68KB</small>
                                    </div>
                                </a>
                            </div>
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