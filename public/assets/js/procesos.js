$(document).ready(function () {
    if (window.location.pathname == '/procesos/crear') {
        cargarDepartamentos()
    }

    $('#identificacion').blur(function () {
        buscar_cliente();
    })

    $('#form_crear_proceso').submit(function () {
        $('#btn_crear_proceso').attr('disabled', true).html(`<span class="spinner-border spinner-border-sm"> </span> Crear`);
    });

    $('#form_agg_audiencia').submit(function () {
        let input = $('#fecha_audienciaInput');
        let value = input.val();
        let date = moment(value, 'DD/MM/YYYY HH:mm:ss').format('YYYY-MM-DD HH:mm:ss');
        input.val(date);

        return true;
    });

    // $('#fecha_audiencia').tempusDominus();
    new tempusDominus.TempusDominus(document.getElementById('fecha_audiencia'), {
        useCurrent: false,
        display: {
            buttons: {
                close: true,
            },
            components: {
                useTwentyfourHour: true,
                decades: false,
                year: true,
                month: true,
                date: true,
                hours: true,
                minutes: true,
                seconds: true
            }
        }
    });
})

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function cargar_subarea(area) {
    console.log(area);
    switch (area) {
        case 'Civil':
            $('#sub_tipo_div').html(`
            <select class="form-control custom-select" name="sub_tipo" id="sub_tipo" onchange="cargar_tipo(this.value)">
                <option value="">Seleccione la sub area</option>
                <option value="Sub area civil">Sub area civil</option>
                <option value="Sub area civil">Sub area civil</option>
                <option value="Sub area civil">Sub area civil</option>
                <option value="Sub area civil">Sub area civil</option>
                <option value="Sub area civil">Sub area civil</option>
            </select>
            `)
            break;

        case 'Familia':
            $('#sub_tipo_div').html(`
            <select class="form-control custom-select" name="sub_tipo" id="sub_tipo" onchange="cargar_tipo(this.value)">
                <option value="">Seleccione la sub area</option>
                <option value="Sub area Familia">Sub area Familia</option>
                <option value="Sub area Familia">Sub area Familia</option>
                <option value="Sub area Familia">Sub area Familia</option>
                <option value="Sub area Familia">Sub area Familia</option>
                <option value="Sub area Familia">Sub area Familia</option>
            </select>
            `)
            break;

        case 'Laboral':
            $('#sub_tipo_div').html(`
            <select class="form-control custom-select" name="sub_tipo" id="sub_tipo" onchange="cargar_tipo(this.value)">
                <option value="">Seleccione la sub area</option>
                <option value="Sub area Laboral">Sub area Laboral</option>
                <option value="Sub area Laboral">Sub area Laboral</option>
                <option value="Sub area Laboral">Sub area Laboral</option>
                <option value="Sub area Laboral">Sub area Laboral</option>
                <option value="Sub area Laboral">Sub area Laboral</option>
            </select>
            `)
            break;

        case 'Seguridad Social':
            $('#sub_tipo_div').html(`
            <select class="form-control custom-select" name="sub_tipo" id="sub_tipo" onchange="cargar_tipo(this.value)">
                <option value="">Seleccione la sub area</option>
                <option value="Pension de vejez">Pension de vejez</option>
                <option value="Pension de invalidez">Pension de invalidez</option>
                <option value="Pension de sobreviviente">Pension de sobreviviente</option>
            </select>
            `)
            break;

        case 'Administrativo':
            $('#sub_tipo_div').html(`
            <select class="form-control custom-select" name="sub_tipo" id="sub_tipo" onchange="cargar_tipo(this.value)">
                <option value="">Seleccione la sub area</option>
                <option value="Sub area Administrativo">Sub area Administrativo</option>
                <option value="Sub area Administrativo">Sub area Administrativo</option>
                <option value="Sub area Administrativo">Sub area Administrativo</option>
                <option value="Sub area Administrativo">Sub area Administrativo</option>
                <option value="Sub area Administrativo">Sub area Administrativo</option>
            </select>
            `)
            break;

        case 'Penal':
            $('#sub_tipo_div').html(`
            <select class="form-control custom-select" name="sub_tipo" id="sub_tipo" onchange="cargar_tipo(this.value)">
                <option value="">Seleccione la sub area</option>
                <option value="Lesiones Personales">Lesiones Personales</option>
                <option value="Homicidio">Homicidio</option>
                <option value="Delitos Sexuales">Delitos Sexuales</option>
                <option value="Extorsión">Extorsión</option>
                <option value="Secuestro">Secuestro</option>
                <option value="Concierto para delinquir">Concierto para delinquir</option>
                <option value="Injuria y Calumnia">Injuria y Calumnia</option>
                <option value="Abuso de confianza">Abuso de confianza</option>
                <option value="Falsificación de documento privado">Falsificación de documento privado</option>
            </select>
            `)
            break;

        case 'Derecho de Petición':
            $('#sub_tipo_div').html(`
            <select class="form-control custom-select" name="sub_tipo" id="sub_tipo" onchange="cargar_tipo(this.value)">
                <option value="">Seleccione la sub area</option>
                <option value="Sub area Derecho de Petición">Sub area Derecho de Petición</option>
                <option value="Sub area Derecho de Petición">Sub area Derecho de Petición</option>
                <option value="Sub area Derecho de Petición">Sub area Derecho de Petición</option>
                <option value="Sub area Derecho de Petición">Sub area Derecho de Petición</option>
                <option value="Sub area Derecho de Petición">Sub area Derecho de Petición</option>
            </select>
            `)
            break;

        case 'Acción de Tutela':
            $('#sub_tipo_div').html(`
            <select class="form-control custom-select" name="sub_tipo" id="sub_tipo" onchange="cargar_tipo(this.value)">
                <option value="">Seleccione la sub area</option>
                <option value="Sub area Acción de Tutela">Sub area Acción de Tutela</option>
                <option value="Sub area Acción de Tutela">Sub area Acción de Tutela</option>
                <option value="Sub area Acción de Tutela">Sub area Acción de Tutela</option>
                <option value="Sub area Acción de Tutela">Sub area Acción de Tutela</option>
                <option value="Sub area Acción de Tutela">Sub area Acción de Tutela</option>
            </select>
            `)
            break;

        case 'Insolvencia':
            $('#sub_tipo_div').html(`
            <select class="form-control custom-select" name="sub_tipo" id="sub_tipo" onchange="cargar_tipo(this.value)">
                <option value="">Seleccione la sub area</option>
                <option value="Empresa">Empresa</option>
                <option value="Persona Natural no comerciante">Persona Natural no comerciante</option>
            </select>
            `)
            break;

        case 'Otros':
            $('#sub_tipo_div').html(`
                <input type="text" placeholder="Escriba Otro" class="form-control" name="sub_tipo">
            `)
            break;
    }
}

function cargar_tipo(tipo) {
    switch (tipo) {
        case 'Empresa':
            $('#section_tipo').html(`
                <div class="form-group">
                    <label class="form-label">Tipo</label>
                    <select class="form-control custom-select" name="tipo_insolvencia">
                        <option value="">Seleccione el tipo</option>
                        <option value="Reorganización">Reorganización</option>
                        <option value="Liquidación">Liquidación</option>
                    </select>
                </div>
            `);
            $('#section_tipo').addClass('col-md-4');
            $('#section_area').removeClass('col-md-6').addClass('col-md-4');
            $('#section_subarea').removeClass('col-md-6').addClass('col-md-4');
            break;
        default:
            $('#section_tipo').html('');
            $('#section_tipo').removeClass('col-md-4');
            $('#section_area').removeClass('col-md-4').addClass('col-md-6');
            $('#section_subarea').removeClass('col-md-4').addClass('col-md-6');
            break;
    }
}

function cargarDepartamentos() {
    var html = '<option value="">Seleccione</option>';
	$.ajax({
		url: '/app/sistema/get/departamentos',
        type: 'POST',
		success: function (data) {
			data.forEach(dpt => {
				html += '<option value="'+dpt.nombre+'">'+dpt.nombre+'</option>';
			});
			$('#departamento').html(html);
		}
    })
}

function cargarMunicipios(dpt) {
    var html = '<option value="">Seleccione</option>';
    $.ajax({
        url: '/app/sistema/get/municipios',
        type: 'POST',
        data: { dpt:dpt },
        success: function (data) {
            data.municipios.forEach(dpt => {
                html += '<option value="'+dpt.nombre+'">'+dpt.nombre+'</option>';
            });
            $('#municipio').html(html);
        }
    })
}

function buscar_cliente() {
    var id = $('#identificacion').val()

    $.ajax({
        url: '/procesos/searh/'+id,
        type: 'get',
        success: function (data) {
            if (data[0]) {
                $('#identificacion').val(data[0].identificacion)
                $('#nombre').val(data[0].nombre)
                $('#direccion').val(data[0].direccion)
                $('#telefono').val(data[0].telefono)
                $('#celular').val(data[0].celular)
                $('#correo').val(data[0].correo)
                $('#correo_dos').val(data[0].correo_dos)
            } else {
                $('#nombre').val('')
                $('#direccion').val('')
                $('#telefono').val('')
                $('#celular').val('')
                $('#correo').val('')
                $('#correo_dos').val('')
            }
        }
    });

    return false;
}

function agregar_actuacion() {
    $('#form_agg_actuacion')[0].reset();
    $('#contenedor-archivo').removeClass('d-none');
    $('#contenedor-archivo').addClass('d-flex');
}

function habilitar_formularo_proceso() {
    $('#identificacion').prop("readonly", false);
    $('#nombre').prop("readonly", false);
    $('#direccion').prop("readonly", false);
    $('#telefono').prop("readonly", false);
    $('#celular').prop("readonly", false);
    $('#correo').prop("readonly", false);
    $('#correo_dos').prop("readonly", false);
    $('#eps').prop("disabled", false);
    $('#arl').prop("disabled", false);
    $('#afp').prop("disabled", false);
    $('#cedula').prop("disabled", false);
    $('#contrato').prop("disabled", false);
    $('#proceso_file').prop("disabled", false);
    $('#contrato_file').prop("disabled", false);
    $('#poder').prop("disabled", false);
    $('#titulo_valor').prop("disabled", false);

    $('#tipo').prop("disabled", false);
    $('#sub_tipo').prop("disabled", false);
    $('#departamento').prop("readonly", false);
    $('#ciudad').prop("readonly", false);
    $('#descripcion').prop("readonly", false);

    $('#section_cedula').addClass('d-none');
    $('#input_cedula').removeClass('d-none');

    $('#section_contrato').addClass('d-none');
    $('#input_contrato').removeClass('d-none');

    $('#section_proceso_file').addClass('d-none');
    $('#input_proceso_file').removeClass('d-none');
    $('#input_contrato').removeClass('d-none');
    $('#input_poder').removeClass('d-none');
    $('#input_titulo_valor').removeClass('d-none');

    $('#btn_habilitar_actualizar_proceso').addClass('d-none');
    $('#btn_enviar_actualizar_proceso').removeClass('d-none');
    $('#btn_cancelar_actualizar_proceso').removeClass('d-none');

    $('#card_proceso').removeClass('card-collapsed');
}

function deshabilitar_formularo_proceso() {
    $('#identificacion').prop("readonly", true);
    $('#nombre').prop("readonly", true);
    $('#direccion').prop("readonly", true);
    $('#telefono').prop("readonly", true);
    $('#celular').prop("readonly", true);
    $('#correo').prop("readonly", true);
    $('#correo_dos').prop("readonly", true);
    $('#eps').prop("disabled", true);
    $('#arl').prop("disabled", true);
    $('#afp').prop("disabled", true);
    $('#cedula').prop("disabled", true);
    $('#contrato').prop("disabled", true);
    $('#proceso_file').prop("disabled", true);
    $('#contrato_file').prop("disabled", true);
    $('#poder').prop("disabled", true);
    $('#titulo_valor').prop("disabled", true);

    $('#tipo').prop("disabled", true);
    $('#sub_tipo').prop("disabled", true);
    $('#departamento').prop("readonly", true);
    $('#ciudad').prop("readonly", true);
    $('#descripcion').prop("readonly", true);

    $('#section_cedula').removeClass('d-none');
    $('#input_cedula').addClass('d-none');

    $('#section_contrato').removeClass('d-none');
    $('#input_contrato').addClass('d-none');

    $('#section_proceso_file').removeClass('d-none');
    $('#input_proceso_file').addClass('d-none');
    $('#input_contrato').addClass('d-none');
    $('#input_poder').addClass('d-none');
    $('#input_titulo_valor').addClass('d-none');

    $('#btn_habilitar_actualizar_proceso').removeClass('d-none');
    $('#btn_enviar_actualizar_proceso').addClass('d-none');
    $('#btn_cancelar_actualizar_proceso').addClass('d-none');
}

function eliminar_proceso(id) {
    if (window.confirm("¿Seguro desea eliminar el preoceso?")) {
        $.ajax({
            url: '/procesos/delete',
            type: 'post',
            data: {id:id},
            success: function (data) {
                $('#delete_confirmed').removeClass('d-none')
                setTimeout(function(){ location.reload(); }, 600);
            }
        });
    }
}

function eliminar_actuacion(id) {
    if (window.confirm("¿Seguro desea eliminar la actuacion?")) {
        $.ajax({
            url: '/procesos/delete_actuacion',
            type: 'post',
            data: {id:id},
            success: function (data) {
                $('#delete_confirmed').removeClass('d-none')
                setTimeout(function(){ location.reload(); }, 600);
            }
        });
    }
}

function update_actuacion(id) {
    $.ajax({
		url: '/procesos/update_actuacion',
        type: 'POST',
        data: {id:id},
		success: function (data) {
			$('#fecha').val(data.fecha)
			$('#actuacion').val(data.actuacion)
			$('#anotacion').val(data.anotacion)
			$('#f_inicio_termino').val(data.f_inicio_termino)
            $('#f_fin_termino').val(data.f_fin_termino)
            $('#actuacion_id').val(data.id)

            $('#form_agg_actuacion').attr("action", "/procesos/update_actuacion_post");
            $('#btn_agg_actuacion').text('Enviar');
            $('#btn_cancelar_actuacion').removeClass('d-none');

            $('#contenedor-archivo').addClass('d-none');
            $('#contenedor-archivo').removeClass('d-flex');

            $('#agg_actuacion').collapse('show');
		}
    })
}

function archivos_actuacion(id) {
    let form = $('#form_agg_actuacion_archivos');
    $('#nombre', form).val('');
    $('#archivo_actuaciones_id', form).val('');

    $.ajax({
		url: '/procesos/actuaciones/archivos',
        type: 'POST',
        data: {id:id},
		success: function (data) {
            let html = '';

            data.forEach((row, index) => {
                if(row.anotacion_file == "" || !row.anotacion_file) return true;

                let nombrearchivo = (!row.nombre) ? row.actuacion : row.nombre;
                nombrearchivo = (!nombrearchivo) ? 'Archivo sin nombre ' + (index + 1) : nombrearchivo.slice(0, 40);

                html += `
                    <tr>
                        <td>
                            <b>${ index + 1 }</b>
                        </td>
                        <td>
                            ${ nombrearchivo }
                        </td>
                        <td class="text-center">
                            <a href="/storage/${ row.anotacion_file }" target="_blank">
                                <button type="button" class="btn btn-primary btn-sm" title="Ver"><i class="fa fa-eye"></i></button>
                            </a>
                            <button type="button" class="btn btn-success btn-sm ${ (!row.id) ? 'd-none' : '' }" onclick="editar_archivo_anotacion(${ row.id }, '${row.nombre}')" title="Editar"><i class="fa fa-pencil"></i></button>
                            <button type="button" class="btn btn-danger btn-sm ${ (!row.id) ? 'd-none' : '' }" onclick="eliminar_archivo_anotacion(${ row.id })" title="Eliminar"><i class="fa fa-trash-o"></i></button>
                        </td>
                    </tr>
                `;
            });

            $('#agg_actuacion_archivos').collapse('show');
            $('#actuaciones_id').val(id);
            $('#content_archivos_actuacion').html(html);
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#agg_actuacion_archivos").offset().top - 100
            }, 500);
		}
    })
}

function editar_archivo_anotacion(id, nombre) {
    let form = $('#form_agg_actuacion_archivos');

    $('#nombre', form).val(nombre);
    $('#archivo_actuaciones_id', form).val(id);
    $('#anotacion_file', form).prop('required', false);
}

function cancelar_update_actuacion() {
	$('#fecha').val('')
	$('#actuacion').val('')
	$('#anotacion').val('')
	$('#f_inicio_termino').val('')
    $('#f_fin_termino').val('')

    $('#form_agg_actuacion').attr("action", "/procesos/agregar_actuacion");
    $('#btn_agg_actuacion').text('Agregar')
    $('#btn_cancelar_actuacion').addClass('d-none')

    $('#agg_actuacion').collapse('hide')
}

function editar_audiencia(id, fecha, observaciones) {
    $('#fecha_audiencia').val(fecha)
	$('#observaciones').val(observaciones)
    $('#audiencia_id').val(id)

    $('#form_agg_audiencia').attr('action', '/procesos/update_audiencia');
    $('#btn_agg_audiencia').text('Actualizar');
    $('#agg_audiencia').collapse('show');
}

function agregar_audiencia() {
    $('#fecha_audiencia').val()
	$('#observaciones').val()
    $('#audiencia_id').val()

    $('#form_agg_audiencia').attr('action', '/procesos/agg_audiencia');
    $('#btn_agg_audiencia').text('Enviar');
    $('#card_audiencias').removeClass('card-collapsed');
}

function agregar_archivos() {
    $('#form_agg_archivos').attr('action', '/procesos/agg_archivos');
    $('#btn_agg_archivos').text('Enviar');
    $('#agg_archivos').removeClass('collapsed');
    $('#card_archivos').removeClass('card-collapsed');
}

function agregar_demandados() {
    let cantidad = $('#cantidad_demandados').val();
    let content_demandados = '';

    for (let index = 1; index <= cantidad; index++) {
        content_demandados += `
            <div class="row">
                <div class="form-group col-1">
                    <label class="form-label">Tipo D${index}</label>
                    <select name="tipo_demandado_${index}" id="tipo_demandado_${index}" onchange="tipo_demandado(this.value, ${index})" class="form-control" required>
                        <option value="">Seleccione</option>
                        <option value="Natural">Natural</option>
                        <option value="Juridica">Juridica</option>
                    </select>
                </div>
                <div class="form-group col-2">
                    <label class="form-label" id="tipo_demandado_label_${index}">Identificacion D${index}</label>
                    <div class="row">
                        <input type="number" name="identificacion_demandado_${index}" id="identificacion_demandado_${index}" class="form-control col-9" onblur="buscar_demandado(${index})" placeholder="Escriba la identificacion" required>
                        <input type="number" name="verificacion_demandado_${index}" id="verificacion_demandado_${index}" class="form-control col-3 d-none" placeholder="CV" />
                    </div>
                </div>
                <div class="form-group col-2">
                    <label class="form-label">Nombre D${index}</label>
                    <input type="text" name="nombre_demandado_${index}" id="nombre_demandado_${index}" class="form-control" placeholder="Escriba el nombre" required  />
                </div>
                <div class="form-group col-2">
                    <label class="form-label">Telefono D${index}</label>
                    <input type="number" class="form-control" name="telefono_demandado_${index}" id="telefono_demandado_${index}" placeholder="Escriba el telefono" />
                </div>
                <div class="form-group col-3">
                    <label class="form-label">Correo D${index}</label>
                    <input type="email" class="form-control" name="correo_demandado_${index}" id="correo_demandado_${index}" placeholder="Escriba el correo" />
                </div>
                <div class="form-group col-2">
                    <label class="form-label">Direccion D${index}</label>
                    <input type="text" class="form-control" name="direccion_demandado_${index}" id="direccion_demandado_${index}" placeholder="Escriba la direccion" />
                </div>
                <input type="hidden" name="existe_demandado_${index}" id="existe_demandado_${index}" />
            </div>

            <div class="row">
                <div class="form-group col-2">
                    <label class="form-label">Identificacion A${index}</label>
                    <input type="number" name="identificacion_abogado_${index}" id="identificacion_abogado_${index}" class="form-control" onblur="buscar_abogado(${index})" placeholder="Escriba la identificacion" />
                </div>
                <div class="form-group col-3">
                    <label class="form-label">Nombre A${index}</label>
                    <input type="text" name="nombre_abogado_${index}" id="nombre_abogado_${index}" class="form-control" placeholder="Escriba el nombre" />
                </div>
                <div class="form-group col-2">
                    <label class="form-label">Telefono A${index}</label>
                    <input type="number" class="form-control" name="telefono_abogado_${index}" id="telefono_abogado_${index}" placeholder="Escriba el telefono" />
                </div>
                <div class="form-group col-3">
                    <label class="form-label">Correo A${index}</label>
                    <input type="email" class="form-control" name="correo_abogado_${index}" id="correo_abogado_${index}" placeholder="Escriba el correo" />
                </div>
                <div class="form-group col-2">
                    <label class="form-label">T. Profesional ${index}</label>
                    <input type="text" class="form-control" name="direccion_abogado_${index}" id="direccion_abogado_${index}" placeholder="Escriba la tarjeta profesional" />
                </div>
                <input type="hidden" name="existe_abogado_${index}" id="existe_abogado_${index}" />
            </div>

            <hr class="w-100">
        `;
    }

    $('#content_demandados').html(content_demandados);

    $('#agg_demandado').collapse('show');
}

function agregar_demandantes() {
    let cantidad = $('#cantidad_demandantes').val();
    let content_demandantes = '';

    for (let index = 1; index <= cantidad; index++) {
        content_demandantes += `
            <div class="row">
                <div class="form-group col-1">
                    <label class="form-label">Tipo D${index}</label>
                    <select name="tipo_demandante_${index}" id="tipo_demandante_${index}" onchange="tipo_demandante(this.value, ${index})" class="form-control" required>
                        <option value="">Seleccione</option>
                        <option value="Natural">Natural</option>
                        <option value="Juridica">Juridica</option>
                    </select>
                </div>
                <div class="form-group col-2">
                    <label class="form-label" id="tipo_demandante_label_${index}">Identificacion D${index}</label>
                    <div class="row">
                        <input type="number" name="identificacion_demandante_${index}" id="identificacion_demandante_${index}" class="form-control col-9" onblur="buscar_demandante(${index})" placeholder="Escriba la identificacion" required>
                        <input name="verificacion_demandante_${index}" id="verificacion_demandante_${index}" class="form-control col-3 d-none" placeholder="CV" />
                    </div>
                </div>
                <div class="form-group col-2">
                    <label class="form-label">Nombre D${index}</label>
                    <input type="text" name="nombre_demandante_${index}" id="nombre_demandante_${index}" class="form-control" placeholder="Escriba el nombre" required  />
                </div>
                <div class="form-group col-2">
                    <label class="form-label">Telefono D${index}</label>
                    <input type="number" class="form-control" name="telefono_demandante_${index}" id="telefono_demandante_${index}" placeholder="Escriba el telefono" />
                </div>
                <div class="form-group col-3">
                    <label class="form-label">Correo D${index}</label>
                    <input type="email" class="form-control" name="correo_demandante_${index}" id="correo_demandante_${index}" placeholder="Escriba el correo" />
                </div>
                <div class="form-group col-2">
                    <label class="form-label">Direccion D${index}</label>
                    <input type="text" class="form-control" name="direccion_demandante_${index}" id="direccion_demandante_${index}" placeholder="Escriba la direccion" />
                </div>
                <input type="hidden" name="existe_demandante_${index}" id="existe_demandante_${index}" />
            </div>

            <div class="row">
                <div class="form-group col-2">
                    <label class="form-label">Identificacion A${index}</label>
                    <input type="number" name="identificacion_abogado_demandante_${index}" id="identificacion_abogado_demandante_${index}" class="form-control" onblur="buscar_abogado_demandante(${index})" placeholder="Escriba la identificacion" />
                </div>
                <div class="form-group col-3">
                    <label class="form-label">Nombre A${index}</label>
                    <input type="text" name="nombre_abogado_demandante_${index}" id="nombre_abogado_demandante_${index}" class="form-control" placeholder="Escriba el nombre" />
                </div>
                <div class="form-group col-2">
                    <label class="form-label">Telefono A${index}</label>
                    <input type="number" class="form-control" name="telefono_abogado_demandante_${index}" id="telefono_abogado_demandante_${index}" placeholder="Escriba el telefono" />
                </div>
                <div class="form-group col-3">
                    <label class="form-label">Correo A${index}</label>
                    <input type="email" class="form-control" name="correo_abogado_demandante_${index}" id="correo_abogado_demandante_${index}" placeholder="Escriba el correo" />
                </div>
                <div class="form-group col-2">
                    <label class="form-label">T. Profesional ${index}</label>
                    <input type="text" class="form-control" name="direccion_abogado_demandante_${index}" id="direccion_abogado_demandante_${index}" placeholder="Escriba la tarjeta profesional" />
                </div>
                <input type="hidden" name="existe_abogado_demandante_${index}" id="existe_abogado_demandante_${index}" />
            </div>

            <hr class="w-100">
        `;
    }

    $('#content_demandantes').html(content_demandantes);

    $('#agg_demandante').collapse('show');
}

function agg_demandado() {
    $('#card_demandados').removeClass('card-collapsed');
    $('#agg_demandado').collapse('show');
    $('#form_agg_demandado')[0].reset();
    $('#tipo_demandado').val('Demandado');
}

function agg_audiencia() {
    $('#card_audiencia').removeClass('card-collapsed');
    $('#agg_audiencia').collapse('show');
    $('#form_agg_audiencia')[0].reset();
}

function agg_demandante() {
    $('#card_demandantes').removeClass('card-collapsed');
    $('#agg_demandante').collapse('show');
    $('#form_agg_demandante')[0].reset();
    $('#tipo_demandante').val('Demandante');
}

function buscar_demandado(index) {
    let identificacion = $('#identificacion_demandado_'+index).val();

    $.ajax({
        url: '/procesos/searh/demandado/'+identificacion,
        type: 'GET',
        // data: { identificacion:identificacion },
        success: function (data) {
            console.log(data.length)
            console.log(identificacion)
            if ( data.length > 0 ) {
                $('#nombre_demandado_'+index).val(data[0].nombre);
                $('#telefono_demandado_'+index).val(data[0].telefono);
                $('#correo_demandado_'+index).val(data[0].correo);
                $('#direccion_demandado_'+index).val(data[0].direccion);
                $('#demandados_id').val(data[0].id);

                $('#existe_demandado_'+index).val('Si');
            } else {
                $('#nombre_demandado_'+index).removeAttr('readonly').val('');
                $('#telefono_demandado_'+index).removeAttr('readonly').val('');
                $('#correo_demandado_'+index).removeAttr('readonly').val('');
                $('#direccion_demandado_'+index).removeAttr('readonly').val('');

                $('#existe_demandado_'+index).val('No');
            }
        }
    });
}

function buscar_demandante(index) {
    let identificacion = $('#identificacion_demandante_'+index).val();

    $.ajax({
        url: '/procesos/searh/demandado/'+identificacion,
        type: 'GET',
        // data: { identificacion:identificacion },
        success: function (data) {
            console.log(data.length)
            console.log(identificacion)
            if ( data.length > 0 ) {
                $('#nombre_demandante_'+index).val(data[0].nombre);
                $('#telefono_demandante_'+index).val(data[0].telefono);
                $('#correo_demandante_'+index).val(data[0].correo);
                $('#direccion_demandante_'+index).val(data[0].direccion);
                $('#demandantes_id').val(data[0].id);

                $('#existe_demandante_'+index).val('Si');
            } else {
                $('#nombre_demandante_'+index).removeAttr('readonly').val('');
                $('#telefono_demandante_'+index).removeAttr('readonly').val('');
                $('#correo_demandante_'+index).removeAttr('readonly').val('');
                $('#direccion_demandante_'+index).removeAttr('readonly').val('');

                $('#existe_demandante_'+index).val('No');
            }
        }
    });
}

function buscar_abogado(index) {
    let identificacion = $('#identificacion_abogado_'+index).val();

    $.ajax({
        url: '/procesos/searh/abogado/'+identificacion,
        type: 'GET',
        // data: { identificacion:identificacion },
        success: function (data) {
            console.log(data.length)
            if ( data.length > 0 ) {
                $('#identificacion_abogado_'+index).val(data[0].identificacion);
                $('#nombre_abogado_'+index).val(data[0].nombre);
                $('#telefono_abogado_'+index).val(data[0].telefono);
                $('#correo_abogado_'+index).val(data[0].correo);
                $('#direccion_abogado_'+index).val(data[0].direccion);
                $('#demandados_id').val(data[0].id);

                $('#existe_abogado_'+index).val('Si');
            } else {
                $('#nombre_abogado_'+index).removeAttr('readonly').val('');
                $('#telefono_abogado_'+index).removeAttr('readonly').val('');
                $('#correo_abogado_'+index).removeAttr('readonly').val('');
                $('#direccion_abogado_'+index).removeAttr('readonly').val('');

                $('#existe_abogado_'+index).val('No');
            }
        }
    });
}

function buscar_abogado_demandante(index) {
    let identificacion = $('#identificacion_abogado_demandante_'+index).val();

    $.ajax({
        url: '/procesos/searh/abogado/'+identificacion,
        type: 'GET',
        // data: { identificacion:identificacion },
        success: function (data) {
            console.log(data.length)
            if ( data.length > 0 ) {
                $('#identificacion_abogado_demandante_'+index).val(data[0].identificacion);
                $('#nombre_abogado_demandante_'+index).val(data[0].nombre);
                $('#telefono_abogado_demandante_'+index).val(data[0].telefono);
                $('#correo_abogado_demandante_'+index).val(data[0].correo);
                $('#direccion_abogado_demandante_'+index).val(data[0].direccion);
                $('#demandados_id').val(data[0].id);

                $('#existe_abogado_demandante_'+index).val('Si');
            } else {
                $('#nombre_abogado_demandante_'+index).removeAttr('readonly').val('');
                $('#telefono_abogado_demandante_'+index).removeAttr('readonly').val('');
                $('#correo_abogado_demandante_'+index).removeAttr('readonly').val('');
                $('#direccion_abogado_demandante_'+index).removeAttr('readonly').val('');

                $('#existe_abogado_demandante_'+index).val('No');
            }
        }
    });
}

function editar_demandado(id) {
    $.ajax({
        url: '/procesos/searh/detalle_proceso',
        type: 'POST',
        data: { id:id },
        success: function (data) {
            $('#nombre_demandado_1').val(data.demandados.nombre).removeAttr('readonly');
            $('#identificacion_demandado_1').val(data.demandados.identificacion);
            $('#telefono_demandado_1').val(data.demandados.telefono).removeAttr('readonly');
            $('#correo_demandado_1').val(data.demandados.correo).removeAttr('readonly');
            $('#direccion_demandado_1').val(data.demandados.direccion).removeAttr('readonly');

            $('#nombre_abogado_1').val(data.abogados.nombre).removeAttr('readonly');
            $('#identificacion_abogado_1').val(data.abogados.identificacion);
            $('#telefono_abogado_1').val(data.abogados.telefono).removeAttr('readonly');
            $('#correo_abogado_1').val(data.abogados.correo).removeAttr('readonly');
            $('#direccion_abogado_1').val(data.abogados.direccion).removeAttr('readonly');

            $('#detalle_proceso_demandado_id').val(data.id);
            $('#demandados_id').val(data.demandados.id);
            $('#abogados_id').val(data.abogados.id);

            $('#agg_demandado').collapse('show');
        }
    });
}

function eliminar_demandado(id) {
    if (window.confirm("¿Seguro desea eliminar el demandado?")) {
        $.ajax({
            url: '/procesos/delete/detalle_proceso',
            type: 'post',
            data: {id:id},
            success: function (data) {
                $('#delete_confirmed').removeClass('d-none')
                setTimeout(function(){ location.reload(); }, 600);
            }
        });
    }
}

function editar_audiencia(id) {
    $.ajax({
        url: '/procesos/searh/audiencia',
        type: 'POST',
        data: { id:id },
        success: function (data) {
            $('#fecha_audiencia').val(data.fecha);
            $('#observaciones').val(data.observaciones);
            $('#audiencia_id').val(data.id);

            $('#agg_audiencia').collapse('show');
        }
    });
}

function eliminar_audiencia(id) {
    if (window.confirm("¿Seguro desea eliminar la audiencia?")) {
        $.ajax({
            url: '/procesos/delete/audiencia',
            type: 'post',
            data: {id:id},
            success: function (data) {
                $('#delete_confirmed').removeClass('d-none');
                setTimeout(function(){ location.reload(); }, 600);
            }
        });
    }
}

function eliminar_archivo(id) {
    if (window.confirm("¿Seguro desea eliminar el archivo?")) {
        $.ajax({
            url: '/procesos/delete_archivos',
            type: 'post',
            data: {id},
            success: function (data) {
                $('#delete_confirmed_archivo').removeClass('d-none');
                setTimeout(function(){ location.reload(); }, 500);
            }
        });
    }
}

function eliminar_archivo_anotacion(id) {
    if (window.confirm("¿Seguro desea eliminar el archivo?")) {
        $.ajax({
            url: '/procesos/actuaciones/archivos/delete',
            type: 'post',
            data: {id},
            success: function (data) {
                $('#delete_confirmed_archivo').removeClass('d-none');
                setTimeout(function(){ location.reload(); }, 500);
            }
        });
    }
}

function editar_demandante(id) {
    $.ajax({
        url: '/procesos/searh/detalle_proceso',
        type: 'POST',
        data: { id:id },
        success: function (data) {
            console.log(data)
            $('#nombre_demandante_1').val(data.demandados.nombre).removeAttr('readonly');
            $('#identificacion_demandante_1').val(data.demandados.identificacion);
            $('#telefono_demandante_1').val(data.demandados.telefono).removeAttr('readonly');
            $('#correo_demandante_1').val(data.demandados.correo).removeAttr('readonly');
            $('#direccion_demandante_1').val(data.demandados.direccion).removeAttr('readonly');

            $('#nombre_abogado_demandante_1').val(data.abogados.nombre).removeAttr('readonly');
            $('#identificacion_abogado_demandante_1').val(data.abogados.identificacion);
            $('#telefono_abogado_demandante_1').val(data.abogados.telefono).removeAttr('readonly');
            $('#correo_abogado_demandante_1').val(data.abogados.correo).removeAttr('readonly');
            $('#direccion_abogado_demandante_1').val(data.abogados.direccion).removeAttr('readonly');

            $('#detalle_proceso_demandante_id').val(data.id);
            $('#demandante_id').val(data.demandados.id);
            $('#abogado_id').val(data.abogados.id);

            $('#agg_demandante').collapse('show');
        }
    });
}

function habilitar_formularo_juzgado() {
    $('#radicado').prop("readonly", false);
    $('#juzgado').prop("readonly", false);
    $('#juez').prop("readonly", false);
    $('#telefono_juzgado').prop("readonly", false);
    $('#direccion_juzgado').prop("readonly", false);
    $('#correo_juzgado').prop("readonly", false);

    $('#btn_habilitar_actualizar_juzgado').addClass('d-none');
    $('#btn_enviar_actualizar_juzgado').removeClass('d-none');
    $('#btn_cancelar_actualizar_juzgado').removeClass('d-none');

    $('#card_juzgado').removeClass('card-collapsed');
}

function deshabilitar_formularo_juzgado() {
    $('#radicado').prop("readonly", true);
    $('#juzgado').prop("readonly", true);
    $('#juez').prop("readonly", true);
    $('#telefono_juzgado').prop("readonly", true);
    $('#direccion_juzgado').prop("readonly", true);
    $('#correo_juzgado').prop("readonly", true);

    $('#btn_habilitar_actualizar_juzgado').removeClass('d-none');
    $('#btn_enviar_actualizar_juzgado').addClass('d-none');
    $('#btn_cancelar_actualizar_juzgado').addClass('d-none');

    // $('#card_juzgado').removeClass('card-collapsed');
}

function habilitar_formularo_fiscalia() {
    $('#fiscalia').prop("readonly", false);
    $('#fiscal').prop("readonly", false);
    $('#telefono_fiscal').prop("readonly", false);
    $('#direccion_fiscal').prop("readonly", false);
    $('#correo_fiscal').prop("readonly", false);

    $('#btn_habilitar_actualizar_fiscalia').addClass('d-none');
    $('#btn_enviar_actualizar_fiscalia').removeClass('d-none');
    $('#btn_cancelar_actualizar_fiscalia').removeClass('d-none');

    $('#card_fiscalia').removeClass('card-collapsed');
}

function deshabilitar_formularo_fiscalia() {
    $('#fiscalia').prop("readonly", true);
    $('#fiscal').prop("readonly", true);
    $('#telefono_fiscal').prop("readonly", true);
    $('#direccion_fiscal').prop("readonly", true);
    $('#correo_fiscal').prop("readonly", true);

    $('#btn_habilitar_actualizar_fiscalia').removeClass('d-none');
    $('#btn_enviar_actualizar_fiscalia').addClass('d-none');
    $('#btn_cancelar_actualizar_fiscalia').addClass('d-none');

    // $('#card_juzgado').removeClass('card-collapsed');
}

function eliminar_acceso(id) {
    if (window.confirm("¿Seguro desea eliminar el acceso a este usuario?")) {
        $.ajax({
            url: '/procesos/delete_acceso',
            type: 'post',
            data: {id:id},
            success: function (data) {
                $('#delete_confirmed').removeClass('d-none')
                setTimeout(function(){ location.reload(); }, 600);
            }
        });
    }
}

function tipo_demandado(tipo, id) {
    if (tipo == 'Juridica') {
        $('#verificacion_demandado_'+id).removeClass('d-none');
        $('#tipo_demandado_label_'+id).text('Nit D'+id);
    } else {
        $('#verificacion_demandado_'+id).addClass('d-none');
        $('#tipo_demandado_label_'+id).text('Identificación D'+id);
    }
}

function tipo_demandante(tipo, id) {
    if (tipo == 'Juridica') {
        $('#verificacion_demandante_'+id).removeClass('d-none');
        $('#tipo_demandante_label_'+id).text('Nit D'+id);
    } else {
        $('#verificacion_demandante_'+id).addClass('d-none');
        $('#tipo_demandante_label_'+id).text('Identificación D'+id);
    }
}

function select_tipo_demandante(tipo) {
    if (tipo == 'Juridica') {
        $('#verificacion_demandante_1').removeClass('d-none');
        $('#tipo_demandado_label_1').text('Nit D');
    } else {
        $('#verificacion_demandante_1').addClass('d-none');
        $('#tipo_demandado_label_1').text('Identificación D');
    }
}

function select_tipo_demandado(tipo) {
    if (tipo == 'Juridica') {
        $('#verificacion_demandado_1').removeClass('d-none');
        $('#tipo_demandante_label_1').text('Nit ');
    } else {
        $('#verificacion_demandado_1').addClass('d-none');
        $('#tipo_demandante_label_1').text('Identificación ');
    }
}
