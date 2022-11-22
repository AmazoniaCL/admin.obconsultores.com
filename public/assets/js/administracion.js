$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

setTimeout(function () {
    $('#alert').addClass('d-none');
}, 2000);


$('#form_agg_contrato').submit(function () {
    $.ajax({
        url: '/administrador/crear_contratos_personal',
        type: 'POST',
        data: $('#form_agg_contrato').serialize(),
        success: function (data) {
            $('#form_agg_contrato')[0].reset();
            $('#agg_contrato').collapse('hide');
            cargar_contratos(data)
        }
    });

    return false;
});

$('#form_exportar_documentos').submit(function () {
    $('#btn_submit_exportar_documentos').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>').attr('disabled', true);

    $.ajax({
        url: '/administrador/exportar_documentos',
        type: 'POST',
        data: $('#form_exportar_documentos').serialize(),
        success: function (data) {
            console.log(data);
            $('#btn_submit_exportar_documentos').html('Enviar').attr('disabled', false);
            $('#form_exportar_documentos')[0].reset();
            $('#exportar_documentos').collapse('hide');
            window.open('/storage/docs/documentos_legales/documentacion.zip', '_blank');
        }
    });

    return false;
});

$('#form_agg_documento').submit(function () {
    var form = document.getElementById('form_agg_documento');
    var formData = new FormData(form);
    $.ajax({
        url: '/administrador/agg_documento',
        type: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            $('#form_agg_documento')[0].reset();
            $('#agg_documento').collapse('hide');
            cargar_documentos(data.tipo, data.id_table, data.personal_id, data.isfecha);
        }
    });

    return false;
});

$('#form_agg_otro_si').submit(function () {
    $.ajax({
        url: '/administrador/agg_otro_si',
        type: 'POST',
        data: $('#form_agg_otro_si').serialize(),
        success: function (data) {
            $('#form_agg_otro_si')[0].reset();
            $('#agg_contrato_otro_si').collapse('hide');
            cargar_contratos(data);
        }
    });

    return false;
});

function delete_documento(id, nombre){
    if(confirm('¿Quieres Eliminar el documento ' + nombre+ ' ?')){
        $.ajax({
            url: '/administrador/delete_documento',
            type: 'post',
            data: {id:id},
            success: function (data) {
                $('#delete_confirmed').removeClass('d-none')
                setTimeout(function(){ window.location.href = '/administrador/documentacion_legal'; }, 600);
            }
        });
    }

}

function edit_documento(id, nombre){
    $('#nombre').val(nombre);
    $('#id_documento').val(id);
    $('#file').removeAttr('required');
    $('#btn_agg_documentacion').html('Editar Documento');
    $('#agg_documentacion').collapse('show');
}

function agg_documento(){
    $('#nombre').val('');
    $('#file').attr('required', 'true');
    $('#id_documento').val('');
    $('#btn_agg_documentacion').html('Agregar Documento');
}

function edit_personal(personal){
    $(`#tipo_identificacion option[value="${ personal.tipo_identificacion }"]`).attr("selected", true);
    $('#identificacion').val(personal.identificacion);
    $('#nombres').val(personal.nombres);
    $('#primer_apellido').val(personal.primer_apellido);
    $('#segundo_apellido').val(personal.segundo_apellido);
    $('#fecha').val(personal.fecha_ingreso);
    $('#direccion').val(personal.direccion);
    $(`#sexo option[value="${ personal.sexo }"]`).attr("selected", true);
    $(`#estado option[value="${ personal.estado }"]`).attr("selected", true);
    $(`#tipo_vinculacion option[value="${ personal.tipo_vinculacion }"]`).attr("selected", true);
    $('#rh').val(personal.rh);
    $('#correo').val(personal.correo);
    $('#telefonos').val(personal.telefonos);
    $('#tarjetaprofesional').val(personal.tarjetaprofesional);
    $('#id').val(personal.id);
    $('#agg_personal').collapse('show');
    $('#btn_agg_personal').html('Editar Personal');
}

function agg_personal(){
    $('#form_agg_personal')[0].reset();
    $(`#tipo_identificacion option[value=""]`).attr("selected", true);
    $(`#sexo option[value=""]`).attr("selected", true);
    $(`#estado option[value=""]`).attr("selected", true);
    $(`#tipo_vinculacion option[value=""]`).attr("selected", true);
    $('#id').val('');
    $('#btn_agg_personal').html('Agregar Personal');
}

function cargar_contratos(id) {
    $.ajax({
        url: '/administrador/cargar_contratos_personal',
        type: 'POST',
        data: { id:id },
        success: function (data) {
            content = '';
            data.forEach( function (contrato, indice) {
                content += `
                    <tr>
                        <td scope="row">${ indice + 1 }</td>
                        <td>${ contrato.tipo_contrato }</td>
                        <td>${ contrato.estado }</td>
                        <td>${ contrato.fecha_inicio }</td>
                        <td>${ (contrato.fecha_fin == null) ? 'N/A' : contrato.fecha_fin }</td>
                        <td class="text-center">
                            <button onclick="limpiarOtrosi(${contrato.id})" type="button" title="Agregar otro si" data-toggle="collapse" data-target="#agg_contrato_otro_si" onclick="modal_agg_otro_si(${ contrato.id }, ${ contrato.vehiculo_id })" class="btn btn-success waves-effect waves-light"><i class="fa fa-save"></i></button>
                            <a href="/administrador/contrato/print/${ contrato.id }" target="_blank"><button type="button" title="Imprimir contrato" onclick="contrato_pdf(${ contrato.id }, ${ contrato.vehiculo_id })" class="btn btn-dark waves-effect waves-light"><i class="fa fa-print"></i></button></a>
                            <a href="/administrador/certificado-laboral/print/${ contrato.id }" target="_blank"><button type="button" title="Imprimir certificado" onclick="certificado(${ contrato.id }, ${ contrato.vehiculo_id })" class="btn btn-primary waves-effect waves-light"><i class="fa fa-print"></i></button></a>
                            <button type="button" title="Editar contrato" onclick="editar_contrato(${ contrato.id})" class="btn btn-warning waves-effect waves-light"><i class="fa fa-edit"></i></button>
                            <button type="button" title="Eliminar contrato" onclick="eliminar_contrato(${ contrato.id }, ${contrato.personal_id})" class="btn btn-danger waves-effect waves-light"><i class="fa fa-trash"></i></button>
                        </td>
                        <td style="max-width: 300px;">`;
                        contrato.otro_si.forEach(otro_si => {
                            content += `
                                <a href="/administrador/otro_si/print/${ otro_si.id }" target="_blank"><button type="button" title="Imprimir otro si" class="btn btn-dark waves-effect waves-light mb-1"><i class="fa fa-save"></i> ${ otro_si.fecha }</button></a>
                            `;
                        });
                        content += `
                        </td>
                    </tr>
                `;
            });
            $('#content_table_contratos').html(content);
        }
    });
}

function editar_contrato(id){
    $.ajax({
        url: '/administrador/editar_contrato_personal',
        type: 'POST',
        data: {id:id},
        success: function (contrato) {
            $('#tipo_contrato option').removeAttr('selected');
            $('#estado option').removeAttr('selected');
            $('#salario').val(contrato.salario);
            $(`#estado option[value="${contrato.estado}"]`).attr("selected", true);
            $(`#tipo_contrato option[value="${contrato.tipo_contrato}"]`).attr("selected", true);
            $('#fecha_inicio_div').removeClass('d-none');
            $('#fecha_inicio').val(contrato.fecha_inicio);
            $('#fecha_fin').val(contrato.fecha_fin);
            if(contrato.fecha_fin != '' && contrato.fecha_fin != null){
                $('#fecha_fin_div').removeClass('d-none');
            }
            $('#clausulas_parte_uno').val(contrato.clausulas_parte_uno);
            $('#clausulas_parte_dos').val(contrato.clausulas_parte_dos);
            $('#clausulas_div').removeClass('d-none');
            $('#btn_agg_contrato').html('Editar Contrato');
            $('#contrato_id').val(contrato.id);
            $('#agg_contrato').collapse('show');
        }
    });
}

function limpiarOtrosi(id){
    $('#fecha').val('');
    $('#descripcion').html(`Entre los suscritos, a saber JOIMER OSORIO BAQUERO, identificado con cedula de ciudadanía 7.706.232 de Neiva Huila, actuando en su calidad de Representante Legal de Amazonia C&L SAS, identificada con Nit. 900447438-6, con domicilio principal en la ciudad de Neiva Huila, quien para los efectos del presente documento se denominara EL EMPLEADOR de una parte, y, de la otra ALIETH NATHALIE CASTRO TENGONO mayor de edad, identificado con cedula de ciudadanía número 1075262366 de XXXXXXXXXX, domiciliado en XXXXXXXXX, obrando en nombre propio quien para efectos de este documento se denominará EL EMPLEADO, hemos convenido en modificar el contrato de trabajo de fecha 2020-09-01 celebrado entre EL EMPLEADOR y EL EMPLEADO el cual quedará así:`);
    $('#contratos_personal_id').val(id);
}

function agg_contrato_ini(){
    $('#form_agg_contrato')[0].reset();
    $('#contrato_id').val('');
    $('#btn_agg_contrato').html('Agregar Contrato');
    $('#estado option').removeAttr('selected');
    $(`#estado option[value=""]`).attr("selected", true);
    $('#tipo_contrato option').removeAttr('selected');
    $(`#tipo_contrato option[value=""]`).attr("selected", true);
    $('#fecha_fin_div').addClass('d-none');
    $('#fecha_inicio_div').addClass('d-none');
    $('#clausulas_div').addClass('d-none');

}

function eliminar_contrato(id, personal_id) {
    $.ajax({
        url: '/administrador/eliminar_contrato_personal',
        type: 'POST',
        data: {id:id, personal_id:personal_id},
        success: function (data) {
            cargar_contratos(data)
        }
    });
}

function delete_personal(id, nombre){
    if(confirm('¿Quieres Eliminar A ' + nombre + ' ?')){
        $.ajax({
            url: '/administrador/delete_personal',
            type: 'post',
            data: {id:id},
            success: function (data) {
                $('#delete_confirmed').removeClass('d-none')
                setTimeout(function(){ window.location.href = '/administrador/personal'; }, 600);
            }
        });
    }

}

function cargar_documentos(tipo, id_table, personal_id, fechas) {
    $.ajax({
        url: '/administrador/cargar_documentos',
        type: 'POST',
        data: {tipo:tipo, id_table:id_table, personal_id:personal_id},
        success: function (data) {
            content = '';
            data.forEach( function (documento, indice) {
                content += `
                <tr>
                    <td scope="row">${ indice+1 }</td>
                    <td>${ documento.fecha_expedicion }</td>`

                    if(fechas==1){
                        content += `<td>${ documento.fecha_inicio_vigencia ?? 'N/A' }</td>
                        <td>${ documento.fecha_fin_vigencia ?? 'N/A' }</td>
                        <td>NA</td>`
                    }

                    content += `
                    <td>${ documento.observaciones }</td>
                    <td>Activo</td>
                    <td class="text-center">
                        <button type="button" onclick="editar_documento(${ documento.id }, '${ id_table }', '${ tipo }', '${fechas}')" class="btn btn-sm btn-success waves-effect waves-light"><i class="fa fa-edit"></i></button>
                        <button type="button" onclick="ver_documento('${ documento.adjunto }', '${ documento.tipo }')" class="btn btn-sm btn-info waves-effect waves-light"><i class="fa fa-eye"></i></button>
                        <a href="/storage/${ documento.adjunto }" download class="${ (documento.adjunto) ? '' : 'disabled' } btn btn-sm btn-primary waves-effect waves-light"><i class="fa fa-download"></i></a>
                        <button type="button" onclick="eliminar_documento(${ documento.id }, ${ documento.personal_id }, '${ documento.tipo }', '${ id_table }', '${fechas}')" class="btn btn-sm btn-danger waves-effect waves-light"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
                `;
            });

            $('#'+id_table).html(content);

        }
    });
}

function editar_documento(id, id_table, tipo, isfecha) {
    $.ajax({
        url: '/administrador/editar_documento',
        type: 'POST',
        data: {id:id},
        success: function (data) {
            $('#tipo').val(data.tipo);
            $('#fecha_expedicion').val(data.fecha_expedicion);
            $('#fecha_inicio_vigencia').val(data.fecha_inicio_vigencia);
            $('#fecha_fin_vigencia').val(data.fecha_fin_vigencia);
            $('#observaciones').val(data.observaciones);

            $('#id_table').val(id_table);
            $('#id').val(id);
            $('#isfecha').val(isfecha);

            $('#adjunto').removeAttr('required');

            $('#agg_documento').collapse('show');
            $('#agg_title_documento').text('Editar ' + data.tipo);
        }
    });
}

function ver_documento(adjunto, tipo) {
    $('#collapse_ver_documento_title').text(tipo)
    $('#collapse_ver_documento_content').html(`<iframe src="/storage/${ adjunto }" width="100%" height="810px" frameborder="0"></iframe>`)
    $('#collapse_ver_documento').collapse('show');
    url = window.location.href.split('#', 2)
    window.location.href = url[0] + '#collapse_ver_documento';
}

function eliminar_documento(id, personal_id, tipo, id_table, isfecha) {
    $.ajax({
        url: '/administrador/eliminar_documento',
        type: 'POST',
        data: {id:id, personal_id:personal_id, tipo:tipo, isfecha:isfecha},
        success: function (data) {
            cargar_documentos(data.tipo, id_table, personal_id, isfecha)
        }
    });
}

function exportar_documentos(){
    $.ajax({
        url: '/administrador/cargar_documentos_all',
        type: 'POST',
        data: {},
        success: function (data) {
            let content = '';
            data.forEach(item => {
                if(item.file != null && item.file != ''){
                    content += `
                    <div class="custom-control custom-checkbox mb-2">
                        <input type="checkbox" class="custom-control-input" id="customCheck${item.id}" name="documentos[]" value="${item.id}">
                        <label class="custom-control-label" for="customCheck${item.id}">${item.nombre}</label>
                    </div>
                `;
                }
            });


            if(content != null && content != ''){
                $('#content_exportar_documentos').html(content);
            }else{
                $('#content_exportar_documentos').html(`<span class="text-center">No hay Documentos<span>`);
            }
            $('#exportar_documentos').collapse('show');
        }
    });
}

function collapse_agg_documento(tipo, id_table,fecha) {
    switch (tipo) {
        case 'RUNT':
            $('#fecha_inicio_vigencia_div').removeClass('d-none');
            $('#fecha_fin_vigencia_div').removeClass('d-none');
            break;

        default:
            $('#fecha_inicio_vigencia_div').addClass('d-none');
            $('#fecha_fin_vigencia_div').addClass('d-none');
            break;
    }

    $('#agg_documento').collapse('show');
    $('#agg_title_documento').text('Agregar ' + tipo);
    $('#tipo').val(tipo);
    $('#isfecha').val(fecha);
    $('#id').val('');
    $('#id_table').val(id_table)
    url = window.location.href.split('#', 2)
    window.location.href = url[0] + '#agg_documento';

}




function ocultar_collapse(collapse){
    $(collapse).collapse('hide');
}


function tipo_contrato_select(tipo) {
    switch (tipo) {
        case 'Obra labor':
            $('#fecha_inicio_div').removeClass('d-none');
            $('#fecha_fin_div').removeClass('d-none');
            $('#clausulas_div').removeClass('d-none');
            $('#clausulas_parte_uno').val(
                `Entre EL EMPLEADOR  y EL TRABAJADOR, de las condiciones ya dichas, identificados como aparece al pie de sus   firmas, se ha celebrado el presente contrato individual de trabajo, regido además por las siguientes cláusulas: PRIMERA: OBJETO. EL EMPLEADOR contrata los servicios personales del TRABAJADOR, para el ejercicio de la conducción de un vehículo automotor que se le asigne por parte del empleador, el cual se entregará mediante acta, éste vehículo será objeto de la función de trabajo el cual hace parte integral del presente contrato. SEGUNDA: A)  OBLIGACIONES DEL TRABAJADOR.   El trabajador se obliga a: a) a poner al servicio del empleador  toda su capacidad normal de trabajo, en forma exclusiva en el desempeño de las funciones propias del oficio mencionado y en las labores anexas y complementarias del mismo, de conformidad con las órdenes e instrucciones que le imparta el Empleador directamente o a quien él delegue. b) a no prestar directa ni indirectamente servicios laborales a otros empleadores, ni a trabajar  por cuenta propia en el mismo oficio, durante la vigencia de este contrato; c) a guardar absoluta reserva sobre los hechos, documentos, informaciones y en general, sobre todos los asuntos y materias que lleguen a su conocimiento por causa u con ocasión de su contrato de trabajo.  d) Las obligaciones especiales determinadas en el art. 58 del C.S.T. e) A cumplir la política interna de calidad, y en especial a capacitarse; y en el cumplimiento estricto de las políticas del Sistema de Gestión Integrado de Calidad Y Seguridad Y Salud en el Trabajo. f) A no transportar personal no autorizado por parte del contratista o por el Empleador. g)  A no utilizar el vehículo en diligencias personales o familiares. h) A no transportar bebidas alcohólicas y alucinógenas. i) Se obliga a responder por cualquier hecho dañino que se generé contra el objeto (vehículo) función del trabajo imputable a su responsabilidad. j) A cumplir todas las normas de tránsito y transporte. k)  Se obliga a responder por los actos contravencionales de tránsito en el ejercicio de la función laboral, como también a cubrir los gastos que correspondan por concepto de inmovilización del vehículo derivado al incumplimiento de las normas de tránsito y transporte. l)  A informar de forma inmediata, oportuna y concomitante al jefe de transporte o jefe inmediato de la empresa Amazonia C&L S.A.S. o quien se haya delegue, sobre la ocurrencia de un accidente de tránsito en que se encuentre involucrado el vehículo que esté a su cargo, para que se presente al lugar de los hechos. ll)  A desarrollar su función de trabajo con la adecuada prevención,  diligencia , previsibilidad y cuidado. O) a informar de forma inmediata a su jefe en  Amazonia C&L S.A.S. o a quien delegue, cualquier accidente o incidente de trabajo. B) PROHIBICIONES AL TRABAJADOR Prohibiciones  en el ejercicio de la función. Fuera de las provisiones reguladas por el artículo 60 del C.S.T. y las estipuladas en el reglamento interno del trabajo se prohíbe al trabajador: a) realizar operaciones mecánicas o de mantenimiento técnico mecánico al vehículo entregado a su cargo para el ejercicio de la función del trabajo. b) En caso de inconvenientes mecánicos no se puede dejar o estacionar el vehículo sobre las vías y sin la debida señalización c) retirar del vehículo los elementos de seguridad tales como extintores, botiquín y demás equipos de carretera d) e) se prohíbe ser omisivo en el retiro de las planillas de despacho diario o salir a prestar su recorrido sin la correspondiente planilla de control f) dirigirse en contra de los jefes inmediatos o compañeros de forma agresiva soez o no cumplir los horarios y los despachos que se le designe g) Cambiar de ruta o desviarse de dichas rutas sin plena autorizaciones de los Jefes de operación que tenga en el momento  h) Retirar el vehículo de las rutas de forma intempestiva o sin justificación alguna y sin previa autorización del jefe de control de la operación. TERCERA: REMUNERACION. EL EMPLEADOR pagará al TRABAJADOR por la prestación de sus servicios el salario indicado, pagadero en las oportunidades  también señaladas arriba.  Además se pagará los dominicales y festivos que llegare a laborar según  los Capítulos I, II y III del Título VII del C.S.T. CUARTA: DURACION DEL CONTRATO. El término de duración del presente contrato es mientras dure la obra CONTRATO PEREGRINACIONES TOUR´S,  Paragrafo1: Suspensión del contrato: Las partes de común acuerdo determinan que el contrato se podrá suspender por fuerza mayor o caso fortuito que temporalmente impida su ejecución de conformidad al artículo 51 subrogado por la Ley 50/90 artículo 4.  Toda vez que la función de trabajo se ejerce en un bien rodante que pueda sufrir estas eventualidades o fallas mecánicas, colisiones entre otras que impidan el normal desarrollo de la función de trabajo.  Mientras que persista el daño o deterioró del objeto de elemento de la relación laboral se suspenderá el contrato con el trabajador y tendrá el alcance de una licencia no remunerada. QUINTA: trabajo nocturno,suplementario, dominical y/o festivo; Todo trabajo suplementario  y/o todo trabajo en día domingo o festivo en los que legalmente debe concederse descanso, se remunerará conforme a la Ley, así como los correspondientes recargos nocturnos. Para el reconocimiento y pago del trabajo suplementario, horas extras, nocturno, dominical o festivo, el empleador o sus representantes deberán haberlo autorizado previamente y por escrito. Cuando la necesidad de este trabajo se presente de manera imprevista o inaplazable, deberá ejecutarse y darse cuenta de él por escrito, a la mayor brevedad, al empleador o a sus representantes  para su aprobación. EL empleador, en consecuencia, no reconocerá ningún trabajo suplementario, o trabajo nocturno en días de descanso legalmente  obligatorio que no haya sido autorizado previamente o que, habiendo sido avisado inmediatamente, no haya sido aprobado como queda dicho. SEXTA: Jornada de trabajo; el trabajador, se obliga a laborar la jornada máxima legal, salvo estipulación expresa y escrita en contrario, en los turnos y dentro de las horas señaladas por el empleador, pudiendo hacer éste ajustes o cambios de horario cuando lo estime conveniente. Por el acuerdo expreso o tácito de las partes, podrán repartirse las horas de la jornada ordinaria en la forma prevista en el Art. 164 del C.S.T., modificado por el Art. 23 de la Ley 50/90, teniendo en cuenta que los tiempos de descanso entre  las secciones de la jornada no se computan dentro de las misma, según el Art.167 ibídem. SEPTIMA: periodo de prueba; Las partes acuerdan un período de prueba de 2 días . De acuerdo con lo dispuesto por el Art. 78 del C.S.T., modificado por el Art. 7º de la Ley 50/90. Durante este período tanto el empleador como  el trabajador podrán terminar el contrato en cualquier tiempo, sin que se cause el pago de indemnización alguna, en forma unilateral, de conformidad con el Art. 80 del C.S.T., modificado por el Art. 3º del Decreto 617/54. OCTAVA: terminacion unilateral.  Son justas causas para dar por terminado unilateralmente este contrato, por cualquiera de las partes, las enumeradas en los Arts. 62 y 63 del C.S.T., modificados por el Art. 7º del Decreto 2351/65 y además, por parte del empleador las faltas que para el efecto se califiquen como graves en reglamentos y demás documentos que contengan reglamentaciones, órdenes, instrucciones o prohibiciones de carácter general o particular, pactos, convenciones colectivas, laudos arbítrales.  Igualmente generan justas causas por una sola vez el incumplimiento de las obligaciones definidas por parte del trabajador en la cláusula segunda del presente contrato, presentarse en estado de embriaguez, con olor de alicoramiento o con resaca o guayabo.   Las causales anteriormente citadas se califican como actos graves y violatorias a las obligaciones y provisiones contenidas en el presente contrato y en el artículo 58 y 60 del Código Sustantivo del Trabajo, como también constituye justas causas las siguientes: a) Retardo en la salida para la prestación del servicio conforme al horario frecuencias, rutas y rodamientos establecidas por el Empleador en la correspondiente planilla diaria establecida sobre el particular por la empresa;  b) No tanquear el vehículo asignado para sus funciones en la Estación de Servicio legalmente habilitadas y asignadas para ello;  c)   Mala presentación del vehículo;  d)  Mala presentación personal y no portar el uniforme reglamentario;  e)  Recoger  y dejar el personal  fuera de los sitios permitidos y establecidos por la empresa o por el contratista;  f)  Violación de rutas o recorridos asignados;  g)  Insultos, ultrajes y malos tratos a los usuarios, compañeros de trabajo y superiores.  h)  Presentarse bajo los estados de alicoramiento o bajo los efectos de alucinógenos dentro de la jornada laboral.  i)  Responsabilidad comprobada en un accidente de tránsito;  j)  Indebida utilización del vehículo;  k)  Cobro de tarifas no autorizadas;  l)  Sobre cupo;  ll)Incumplir con las citaciones que efectúe tanto la empresa, como los comités, las jefaturas de transporte;  m) Permitir que  personas no autorizadas por el Empleador conduzcan el automotor designado para sus funciones;  n) rehusarse a facilitar los documentos personales como los del vehículo a las autoridades que los solicite como a los miembros de la empresa;  ñ)  Falsedad de documentos;  o)  Suplantación del conductor en las pruebas de alcoholimetría;  p)  Transportar personas ajenas a la empresa contratista;  q)    No presentarse en el correspondiente sitio previamente establecido por el Empleador y o contratista en el desarrollo de su jornada laboral;   r)  Alteración de planillas;  s)  No presentarse en forma oportuna, o alterar el rodamiento o recorridos establecidas por el Empleador o a quien él delegue;  t)  Inasistencia injustificada a los cursos y capacitaciones programadas por la empresa;  u)  No portar los documentos y demás permisos exigidos por la Ley al día para la prestación del servicio público especial de transporte de personas;  v)  Disociar  y  poner en tela de juicio el buen nombre de la empresa o contratista  W)  La no entrega de cuentas a la empresa de gastos ocasionales  del vehículo.  NOVENA: Modificaciones de las condiciones laborales, El trabajador acepta desde ahora expresamente todas las modificaciones determinadas por el empleador, en ejercicio de su poder subordinante, de sus condiciones laborales, tales como la jornada de trabajo, el lugar de prestación de servicio,  siempre que tales modificaciones no afecten su honor, dignidad o sus derechos mínimos ni impliquen desmejoras sustanciales o graves perjuicios para él, de conformidad con lo dispuesto por el Art. 23 del C.S.T., modificado por el Art. 1º de la Ley 50/90. Los gastos que se originen con el traslado de lugar de prestación del servicio serán cubiertos por el empleador de conformidad con el numeral 8º del Art. 57 del C.S.T.  DECIMA PRIMERA: Direccion del trabajador. Se compromete a informar por escrito al empleador  cualquier cambio de dirección teniéndose  como suya, para todos los efectos, la última dirección registrada en la empresa. DECIMA SEGUNDA: Efectos, El presente contrato reemplaza en su integridad y deja sin efecto cualquiera otro contrato, verbal o escrito, celebrado entre las partes con anterioridad, pudiendo las partes convenir por escrito modificaciones al mismo, las que formarán parte integral de este contrato. Para constancia se firma en dos  ejemplares del mismo tenor y valor, ante testigos, un ejemplar de los cuales recibe el trabajador en éste acto, en la ciudad y fecha que se indican a continuación:`
            );
            $('#clausulas_parte_dos').val('');
            break;

        case 'Termino fijo':
            $('#fecha_inicio_div').removeClass('d-none');
            $('#fecha_fin_div').removeClass('d-none');
            $('#clausulas_div').removeClass('d-none');
            $('#clausulas_parte_uno').val(
                `Entre el empleador y el empleado, mayores de edad, de forma libre y voluntaria, suscriben el siguiente contrato de trabajo a término indefinido, regido por las siguientes cláusulas: PRIMERA: OBJETO, AMAZONIA C&L S.A.S, contrata los servicios personales del trabajador y este se obliga : A poner al servicio de AMAZONIA C&L SAS, toda su capacidad normal de trabajo, en forma exclusiva, en el desempeño de las funciones propias del oficio y cargo mencionado y en las labores anexas y complementarias del mismo, de conformidad con las órdenes e instrucciones que se le imparta por medio del jefe inmediato o quien haga sus veces, a guardar absoluta reserva sobre los hechos, documentación, información y en general, sobre todos los asuntos y materias que lleguen a su conocimiento por causa o por ocasión de su contrato de trabajo. Parágrafo primero, hace parte integral del presente contrato las funciones detalladas en el manual de competencias del presente cargo. Parágrafo segundo, la descripción anterior en general no excluye ni limita para ejecutar labores conexas complementarias, asesorías o similares y en general aquellas que sean necesarias para un mejor resultado en la ejecución de la causa que dio origen al contrato. SEGUNDA: LUGAR, El trabajador desarrollará sus funciones principalmente en la oficina de AMAZONIA C&L SAS, en Neiva, o donde tenga su domicilio principal o cualquier otro lugar que la empresa determine, para ello, debe ser notificado por medio escrito por su jefe inmediato o quien haga sus veces, siempre y cuando no desmejore su condición laboral. TERCERA: FUNCIONES. El empleador contrata al trabajador, para desempeñarse como`
            );

            $('#clausulas_parte_dos').val(
                `CUARTO: ELEMENTOS DE TRABAJO. Corresponde al empleador suministrar los elementos necesarios para el normal desempeño de las funciones del cargo contratado. QUINTA: OBLIGACIONES DEL EMPLEADO. El trabajador por su parte, prestará su fuerza laboral con fidelidad, entrega y buena fe, cumpliendo debidamente el (Reglamento Interno de Trabajo),  acatar las órdenes e instrucciones que le imparta el Jefe inmediato o quien haga sus veces, asistir a todas las capacitaciones que la organización crea conveniente, aceptar y acatar todas las recomendaciones que haga la ARL o cualquier organización o persona que AMAZONIA C&L S.A.S. crea conveniente, conocer los riesgos a los cuales están expuesto en la Organización, generar cultura de autocuidado, cumplir con los horarios establecidos por la organización, no sacar  ninguna objeto, ni información de la esfera de la organización, al igual que no laborar por cuenta propia o con otro empleador en el mismo oficio, mientras esté vigente éste contrato, tener total confidencialidad en cuanto a la información o cualquier tema que tenga que ver con AMAZONIA C&L S.A.S. y su actividad económica o a quien ella delegue, tener total respeto con sus compañeros y superiores de la organización, AMAZONIA C&L S.A.S. podrá en el trascurso del contrato asignar otras obligaciones que crea conveniente para un mejor desarrollo de la actividad. SEXTA: DURACION DEL CONTRATO. La duración del contrato será de forma indefinida mientras subsistan las causas que dieron origen y la materia del trabajo, hasta que una de las partes lo estime conveniente, o cuando el empleado incurra en violación de una de las causales graves de despido o terminación del contrato, escrita en éste. SEPTIMA: JUSTAS CAUSA DE DESPIDO, Son justas causas para dar por terminado unilateralmente este contrato, por cualquiera de las partes, las que establece la Ley, el reglamento interno de trabajo, el presente contrato y/o las circulares qué a lo largo de la ejecución del presente, establezcan conductas no previstas en virtud de hechos o tecnologías o cambios de actividad diferentes a las consideradas en el presente contrato. Se trata de reglamentaciones, ordenes instrucciones de carácter general o particular que surjan con posterioridad al presente acuerdo, cuya violación sea calificada como grave. Expresamente se califican en este acto como faltas graves la violación a las obligaciones y prohibiciones descritas y además las siguientes: A) El incumplimiento de las normas y políticas que tenga la organización para el uso de los sistemas, informática, software, claves de seguridad, materiales, computadores, útiles de oficina etc., que la Organización entrega al trabajador para la mejor ejecución de sus funciones. Así como violación a lo contenido en las normas de seguridad industrial. B) La utilización para fines distintos a los considerados por el Empleador para el cumplimiento de su objeto social de las bases de datos de su propiedad. C) Desatender las actividades de capacitación programadas por el Empleador así sea en horario diferente a la ordinaria. D) La mala atención y desinterés para con los clientes y proveedores. E) En caso de laborar en turnos, efectuar cambios sin la debida autorización del jefe inmediato. F) Llegar tarde al sitio de trabajo. G) No cumplir con las normas del SG-SST. H) Negarse a cumplir con los protocolos y procesos para la prestación de servicios encomendados, y demás establecidos por la Organización en desarrollo de su objeto social. I) Desatender las obligaciones antes mencionadas constituye justa causa para dar por terminado el contrato por parte del Empleador. J) Violar el acuerdo de confidencialidad determinado por la Empresa. K) El incumplimiento a las obligaciones y prohibiciones que se expresan en los artículos 57 y siguientes del Código sustantivo del Trabajo. L) Además del incumplimiento o violación a las normas establecidas en el (Reglamento Interno de Trabajo) .M) no cumplir u omitir las condiciones de trabajo seguro establecidas por la organización en el SGSST, N) faltarle al respeto a los superiores o compañeros de la organización en forma verbal, física o psicológica). Ñ) tomar dinero o cualquier objeto de la organización sin la autorización del jefe inmediato, para uso personal o para terceros, O) entregar o usar información de la organización a terceros, P) las demás que la organización crea conveniente según la ley. OCTAVA: SALARIO, El empleador cancelará al trabajador un salario mensual de, NOVECIENTOS CINCUENTA MIL PESOS M/CTE ($950.000.oo) y podrá dar una bonificación cuando lo crea conveniente, la cual no constituye base salarial, acordado y aceptado voluntariamente con la firma de éste contrato. NOVENO: HORARIO, el trabajador se obliga a laborar la jornada tiempo completo, equivalente a 48 horas semanales laboradas, de lunes a viernes de 8:00 am a 12:00 m y de 2:00 pm a 6:00 pm y los sabados de 8:00 am a 12:00 m, el empleador puede hacer ajustes o cambios de horario cuando lo estime conveniente, teniendo de presente que la jornada ordinaria va de 6 am a 10 pm. Podrán aplicar el artículo 164 del Código Sustantivo del Trabajo, modificado por el artículo 23 de la Ley 50 de 1990, teniendo en cuenta que los tiempos de descanso entre las secciones de la jornada no se computan dentro de la misma, según el artículo 167 ibídem. DECIMA: AFILIACIÓN Y PAGO A SEGURIDAD SOCIAL. Es obligación del empleador afiliar al trabajador a la seguridad social integral: salud, pensión, riesgos laborales y CCF, autorizando el trabajador el descuento en su salario, los valores que le corresponda aportar, en la proporción establecida por la ley. DECIMA PRIMERA: MODIFICACIONES. Cualquier modificación al presente contrato debe efectuarse por escrito y anexarse a este documento. DECIMA SEGUNDA: Efectos. El presente contrato reemplaza y deja sin efecto cualquier otro contrato verbal o escrito, que se hubiera celebrado entre las partes con anterioridad.`
            );
            break;

        case 'Termino indefinido':
            $('#fecha_inicio_div').removeClass('d-none');
            $('#clausulas_div').removeClass('d-none');
            $('#clausulas_parte_uno').val(
                `Entre el empleador y el empleado, mayores de edad, de forma libre y voluntaria, suscriben el siguiente contrato de trabajo a término indefinido, regido por las siguientes cláusulas: PRIMERA: OBJETO, AMAZONIA C&L S.A.S, contrata los servicios personales del trabajador y este se obliga : A poner al servicio de AMAZONIA C&L SAS, toda su capacidad normal de trabajo, en forma exclusiva, en el desempeño de las funciones propias del oficio y cargo mencionado y en las labores anexas y complementarias del mismo, de conformidad con las órdenes e instrucciones que se le imparta por medio del jefe inmediato o quien haga sus veces, a guardar absoluta reserva sobre los hechos, documentación, información y en general, sobre todos los asuntos y materias que lleguen a su conocimiento por causa o por ocasión de su contrato de trabajo. Parágrafo primero, hace parte integral del presente contrato las funciones detalladas en el manual de competencias del presente cargo. Parágrafo segundo, la descripción anterior en general no excluye ni limita para ejecutar labores conexas complementarias, asesorías o similares y en general aquellas que sean necesarias para un mejor resultado en la ejecución de la causa que dio origen al contrato. SEGUNDA: LUGAR, El trabajador desarrollará sus funciones principalmente en la oficina de AMAZONIA C&L SAS, en Neiva, o donde tenga su domicilio principal o cualquier otro lugar que la empresa determine, para ello, debe ser notificado por medio escrito por su jefe inmediato o quien haga sus veces, siempre y cuando no desmejore su condición laboral. TERCERA: FUNCIONES. El empleador contrata al trabajador, para desempeñarse como`
            );

            $('#clausulas_parte_dos').val(
                `SEXTA: DURACION DEL CONTRATO. La duración del contrato será de forma indefinida mientras subsistan las causas que dieron origen y la materia del trabajo, hasta que una de las partes lo estime conveniente, o cuando el empleado incurra en violación de una de las causales graves de despido o terminación del contrato, escrita en éste. SEPTIMA: JUSTAS CAUSA DE DESPIDO, Son justas causas para dar por terminado unilateralmente este contrato, por cualquiera de las partes, las que establece la Ley, el reglamento interno de trabajo, el presente contrato y/o las circulares qué a lo largo de la ejecución del presente, establezcan conductas no previstas en virtud de hechos o tecnologías o cambios de actividad diferentes a las consideradas en el presente contrato. Se trata de reglamentaciones, ordenes instrucciones de carácter general o particular que surjan con posterioridad al presente acuerdo, cuya violación sea calificada como grave. Expresamente se califican en este acto como faltas graves la violación a las obligaciones y prohibiciones descritas y además las siguientes: A) El incumplimiento de las normas y políticas que tenga la organización para el uso de los sistemas, informática, software, claves de seguridad, materiales, computadores, útiles de oficina etc., que la Organización entrega al trabajador para la mejor ejecución de sus funciones. Así como violación a lo contenido en las normas de seguridad industrial. B) La utilización para fines distintos a los considerados por el Empleador para el cumplimiento de su objeto social de las bases de datos de su propiedad. C) Desatender las actividades de capacitación programadas por el Empleador así sea en horario diferente a la ordinaria. D) La mala atención y desinterés para con los clientes y proveedores. E) En caso de laborar en turnos, efectuar cambios sin la debida autorización del jefe inmediato. F) Llegar tarde al sitio de trabajo. G) No cumplir con las normas del SG-SST. H) Negarse a cumplir con los protocolos y procesos para la prestación de servicios encomendados, y demás establecidos por la Organización en desarrollo de su objeto social. I) Desatender las obligaciones antes mencionadas constituye justa causa para dar por terminado el contrato por parte del Empleador. J) Violar el acuerdo de confidencialidad determinado por la Empresa. K) El incumplimiento a las obligaciones y prohibiciones que se expresan en los artículos 57 y siguientes del Código sustantivo del Trabajo. L) Además del incumplimiento o violación a las normas establecidas en el (Reglamento Interno de Trabajo) .M) no cumplir u omitir las condiciones de trabajo seguro establecidas por la organización en el SGSST, N) faltarle al respeto a los superiores o compañeros de la organización en forma verbal, física o psicológica). Ñ) tomar dinero o cualquier objeto de la organización sin la autorización del jefe inmediato, para uso personal o para terceros, O) entregar o usar información de la organización a terceros, P) las demás que la organización crea conveniente según la ley. OCTAVA: SALARIO, El empleador cancelará al trabajador un salario mensual de, NOVECIENTOS CINCUENTA MIL PESOS M/CTE ($950.000.oo) y podrá dar una bonificación cuando lo crea conveniente, la cual no constituye base salarial, acordado y aceptado voluntariamente con la firma de éste contrato. NOVENO: HORARIO, el trabajador se obliga a laborar la jornada tiempo completo, equivalente a 48 horas semanales laboradas, de lunes a viernes de 8:00 am a 12:00 m y de 2:00 pm a 6:00 pm y los sabados de 8:00 am a 12:00 m, el empleador puede hacer ajustes o cambios de horario cuando lo estime conveniente, teniendo de presente que la jornada ordinaria va de 6 am a 10 pm. Podrán aplicar el artículo 164 del Código Sustantivo del Trabajo, modificado por el artículo 23 de la Ley 50 de 1990, teniendo en cuenta que los tiempos de descanso entre las secciones de la jornada no se computan dentro de la misma, según el artículo 167 ibídem. DECIMA: AFILIACIÓN Y PAGO A SEGURIDAD SOCIAL. Es obligación del empleador afiliar al trabajador a la seguridad social integral: salud, pensión, riesgos laborales y CCF, autorizando el trabajador el descuento en su salario, los valores que le corresponda aportar, en la proporción establecida por la ley. DECIMA PRIMERA: MODIFICACIONES. Cualquier modificación al presente contrato debe efectuarse por escrito y anexarse a este documento. DECIMA SEGUNDA: Efectos. El presente contrato reemplaza y deja sin efecto cualquier otro contrato verbal o escrito, que se hubiera celebrado entre las partes con anterioridad.`
            );
            break;

        default:
            break;
    }
}

//formato
function delete_formato(id, nombre){
    if(confirm('¿Quieres Eliminar el formato ' + nombre+ ' ?')){
        $.ajax({
            url: '/administrador/delete_formatos',
            type: 'post',
            data: {id:id},
            success: function (data) {
                $('#delete_confirmed').removeClass('d-none')
                setTimeout(function(){ window.location.href = '/administrador/formatos'; }, 600);
            }
        });
    }

}

function edit_formato(id, nombre, descripcion){
    $('#nombre').val(nombre);
    $('#id_documento').val(id);
    $('#descripcion').val(descripcion);
    $('#file').removeAttr('required');
    $('#btn_agg_formato').html('Editar Documento');
    $('#agg_formato').collapse('show');
}
