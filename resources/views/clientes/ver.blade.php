@extends('layouts.app')

@section('title_content') Ver Cliente @endsection

@section('myStyles') <link rel="stylesheet" href="{{ asset('assets/plugins/summernote/dist/summernote.css') }}"/> @endsection

@section('myScripts')
    <script src="{{ asset('assets/bundles/summernote.bundle.js') }}"></script>
    <script src="{{ asset('assets/plugins/summernote/dist/lang/summernote-es-ES.js') }}"></script>
    <script src="{{ asset('assets/js/consultas.js') }}"></script>
    <script src="{{ asset('assets/js/cliente.js') }}"></script>
@endsection

@section('content')

<div class="section-body">

    <div class="col-lg-12">

        <a href="/clientes"><button type="button" class="btn btn-primary mb-2"><i class="fa fa-arrow-circle-left mr-2"></i> Atras </button></a>

        @if (session()->has('mensaje_enviado') && session()->has('mensaje_enviado') == 1)
            <div class="alert alert-success" id="confirmacion_mensaje_enviado" role="alert">
                <strong>Correo enviado correctamente</strong>
            </div>
        @endif

        <div class="row clearfix">
            <div class="col-md-12">
                <div class="card">

                    <form action="/clientes/enviar_mensaje" id="form_enviar_mensaje" style="display: none" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="card-header">
                            <h3 class="card-title">Enviar mensaje a {{ $cliente->nombre }} </h3>
                        </div>
                        <div class="card-body">

                            <div class="form-group">
                                <label for="asunto" class="form-label">Asunto</label>
                                <textarea name="asunto" id="asunto" rows="3" placeholder="Escriba el asunto" class="form-control mb-2"></textarea>
                            </div>

                            <label class="form-label">Mensaje</label>

                            <input type="hidden" name="mensaje" id="mensaje" />

                            <div class="summernote"></div>

                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <label class="form-label">Adjunto</label>
                                    <input type="file" class="form-control" accept="application/pdf,image/png,image/jpg,image/jpeg" name="adjunto_correo" id="adjunto_correo" />
                                </div>
                            </div>

                            <input type="hidden" name="id" id="id_mensaje_cliente" value="{{ $cliente->id }}">
                            <input type="hidden" name="correo" id="correo_mensaje_cliente" value="{{ $cliente->correo }}">

                            <div class="row justify-content-center">
                                <button type="submit" class="btn btn-success btn-lg mt-3" id="btn_enviar_mensaje">Enviar</button>
                                <button type="button" class="btn btn-secondary btn-lg ml-2 mt-3" onclick="document.getElementById('form_enviar_mensaje').style.display = 'none'">Cancelar</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <div class="card">

            <form action="/clientes/update" method="POST" id="form_update_cliente" style="display: contents" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="id" value="{{ $cliente->id }}">

                <div class="card-header">
                    <h3 class="card-title">Cliente {{ $cliente->nombre }} </h3>
                    <div class="card-options">
                        <button type="button" class="btn btn-primary mr-2" onclick="habilitar_formularo_correo()"><i class="fa fa-envelope mr-2"></i> Enviar correo </button>
                        <button type="button" class="btn btn-primary" id="btn_habilitar_actualizar_cliente" onclick="habilitar_formularo_cliente()"><i class="fe fe-edit mr-2"></i> Actualizar </button>
                        <button type="submit" class="btn text-white bg-green d-none" id="btn_enviar_actualizar_cliente"><i class="fe fe-check mr-2"></i> Enviar </button>
                        <button type="button" class="btn text-white bg-red ml-1 d-none" id="btn_cancelar_actualizar_cliente" onclick="deshabilitar_formularo_cliente()"><i class="fa fa-times"></i>  </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">

                        @if (session()->has('update') && session('update') == 1)
                            <div class="alert alert-icon alert-success col-12" role="alert">
                                <i class="fe fe-check mr-2" aria-hidden="true"></i> Cliente actualizado correctamente
                            </div>
                        @endif

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">Tipo persona</label>
                                <select name="tipo_cliente" id="tipo_cliente" onchange="select_tipo_persona(this.value)" class="form-control" disabled required>
                                    <option value="">Seleccione</option>
                                    <option {{ $cliente->tipo_cliente == 'Natural' ? 'selected' : '' }} value="Natural">Natural</option>
                                    <option {{ $cliente->tipo_cliente == 'Juridica' ? 'selected' : '' }} value="Juridica">Juridica</option>
                                </select>
                            </div>
                        </div>

                        <div class="{{ $cliente->tipo_cliente == 'Juridica' ? 'col-md-5' : 'col-md-5' }}">
                            <div class="form-group">
                                <label class="form-label">{{ $cliente->tipo_cliente == 'Juridica' ? 'Nit.' : 'Identificación' }}</label>
                                <input type="number" class="form-control" name="identificacion" id="identificacion" required readonly value="{{ $cliente->identificacion }}">
                            </div>
                        </div>
                        @if ($cliente->tipo_cliente == 'Juridica')
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label class="form-label">D.V.</label>
                                    <input type="number" class="form-control" name="verificacion" id="verificacion" required readonly value="{{ $cliente->verificacion }}">
                                </div>
                            </div>
                        @endif
                        <div class="{{ $cliente->tipo_cliente == 'Juridica' ? 'col-md-4' : 'col-md-5' }}">
                            <div class="form-group">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control" name="nombre" id="nombre" required readonly value="{{ $cliente->nombre }}">
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                                <label class="form-label">Telefono</label>
                                <input type="number" class="form-control" name="telefono" id="telefono" readonly value="{{ $cliente->telefono }}">
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                                <label class="form-label">Celular</label>
                                <input type="number" class="form-control" name="celular" id="celular" readonly value="{{ $cliente->celular }}">
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                                <label class="form-label">Direccion</label>
                                <input type="text" class="form-control" name="direccion" id="direccion" required readonly value="{{ $cliente->direccion }}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label class="form-label">Correo 1</label>
                                <input type="email" class="form-control" name="correo" id="correo" required readonly value="{{ $cliente->correo }}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label class="form-label">Correo 2</label>
                                <input type="email" class="form-control" name="correo_dos" id="correo_dos" readonly value="{{ $cliente->correo_dos }}">
                            </div>
                        </div>


                        <hr class="w-100 {{ $cliente->tipo_cliente == 'Natural' ? 'd-none' : '' }}" id="section_representante_hr">

                        <div id="section_representante" class="d-flex w-100 {{ $cliente->tipo_cliente == 'Natural' ? 'd-none' : '' }}">
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Nombre Representante</label>
                                    <input type="text" class="form-control" name="nombre_representante" id="nombre_representante" readonly value="{{ $cliente->nombre_representante }}" placeholder="Nombre del representante legal">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Identificacion</label>
                                    <input type="number" class="form-control" name="identificacion_representante" id="identificacion_representante" readonly value="{{ $cliente->identificacion_representante }}" placeholder="Escriba la Identificacion">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Correo</label>
                                    <input type="email" class="form-control" name="direccion_representante" id="direccion_representante" readonly value="{{ $cliente->direccion_representante }}" placeholder="Escriba el correo">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Celular</label>
                                    <input type="number" class="form-control" name="celular_representante" id="celular_representante" readonly value="{{ $cliente->celular_representante }}" placeholder="Escriba el celular">
                                </div>
                            </div>
                        </div>

                        {{-- <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">EPS</label>
                                <select class="form-control custom-select" name="eps" id="eps" disabled>
                                    <option value="">Seleccione EPS</option>
                                    <option value="ALIANSALUD" {{ $cliente->eps == 'ALIANSALUD' ? 'selected' : '' }}>ALIANSALUD</option>
                                    <option value="COMFENALCO VALLE E.P.S." {{ $cliente->eps == 'COMFENALCO VALLE E.P.S.' ? 'selected' : '' }}>COMFENALCO VALLE E.P.S.</option>
                                    <option value="SURAMERICANA" {{ $cliente->eps == 'SURAMERICANA' ? 'selected' : '' }}>SURAMERICANA</option>
                                    <option value="CAFE SALUD" {{ $cliente->eps == 'CAFE SALUD' ? 'selected' : '' }}>CAFE SALUD</option>
                                    <option value="COLPATRIA" {{ $cliente->eps == 'COLPATRIA' ? 'selected' : '' }}>COLPATRIA</option>
                                    <option value="COMPENSAR" {{ $cliente->eps == 'COMPENSAR' ? 'selected' : '' }}>COMPENSAR</option>
                                    <option value="CRUZ BLANCA" {{ $cliente->eps == 'CRUZ BLANCA' ? 'selected' : '' }}>CRUZ BLANCA</option>
                                    <option value="SALUD TOTAL" {{ $cliente->eps == 'SALUD TOTAL' ? 'selected' : '' }}>SALUD TOTAL</option>
                                    <option value="SANITAS" {{ $cliente->eps == 'SANITAS' ? 'selected' : '' }}>SANITAS</option>
                                    <option value="COOMEVA" {{ $cliente->eps == 'COOMEVA' ? 'selected' : '' }}>COOMEVA</option>
                                    <option value="NUEVA EPS" {{ $cliente->eps == 'NUEVA EPS' ? 'selected' : '' }}>NUEVA EPS</option>
                                    <option value="MEDIMAS" {{ $cliente->eps == 'MEDIMAS' ? 'selected' : '' }}>MEDIMAS</option>
                                    <option value="SALUD COOP" {{ $cliente->eps == 'SALUD COOP' ? 'selected' : '' }}>SALUD COOP</option>
                                    <option value="ASMET SALUD" {{ $cliente->eps == 'ASMET SALUD' ? 'selected' : '' }}>ASMET SALUD</option>
                                    <option value="ASOCIACION INDIGENA DEL CAUCA" {{ $cliente->eps == 'ASOCIACION INDIGENA DEL CAUCA' ? 'selected' : '' }}>ASOCIACION INDIGENA DEL CAUCA</option>
                                    <option value="CAFAM" {{ $cliente->eps == 'CAFAM' ? 'selected' : '' }}>CAFAM</option>
                                    <option value="CAJA DE COMPENSACION FAMILIAR DEL HUILA 'COMFAMILIAR'" {{ $cliente->eps == "CAJA DE COMPENSACION FAMILIAR DEL HUILA 'COMFAMILIAR'" ? 'selected' : '' }}>CAJA DE COMPENSACION FAMILIAR DEL HUILA 'COMFAMILIAR'</option>
                                    <option value="CAPRESOCA" {{ $cliente->eps == 'CAPRESOCA' ? 'selected' : '' }}>CAPRESOCA</option>
                                    <option value="COMPARTA" {{ $cliente->eps == 'COMPARTA' ? 'selected' : '' }}>COMPARTA</option>
                                    <option value="ECOOPSOS" {{ $cliente->eps == 'ECOOPSOS' ? 'selected' : '' }}>ECOOPSOS</option>
                                    <option value="CAPRECOM" {{ $cliente->eps == 'CAPRECOM' ? 'selected' : '' }}>CAPRECOM</option>
                                    <option value="COLSUBSIDIO" {{ $cliente->eps == 'COLSUBSIDIO' ? 'selected' : '' }}>COLSUBSIDIO</option>
                                    <option value="COMFACUNDI" {{ $cliente->eps == 'COMFACUNDI' ? 'selected' : '' }}>COMFACUNDI</option>
                                    <option value="CONVIDA" {{ $cliente->eps == 'CONVIDA' ? 'selected' : '' }}>CONVIDA</option>
                                    <option value="HUMANA VIVIR" {{ $cliente->eps == 'HUMANA VIVIR' ? 'selected' : '' }}>HUMANA VIVIR</option>
                                    <option value="SALUD VIDA" {{ $cliente->eps == 'SALUD VIDA' ? 'selected' : '' }}>SALUD VIDA</option>
                                    <option value="SOL SALUD" {{ $cliente->eps == 'SOL SALUD' ? 'selected' : '' }}>SOL SALUD</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">ARL</label>
                                <select class="form-control custom-select" name="arl" id="arl" disabled >
                                    <option value="">Seleccione ARL</option>
                                    <option value="Axa Colpatria Seguros S.A." {{ $cliente->arl == 'Axa Colpatria Seguros S.A.' ? 'selected' : '' }}>Axa Colpatria Seguros S.A.</option>
                                    <option value="Colmena Seguros" {{ $cliente->arl == 'Colmena Seguros' ? 'selected' : '' }}>Colmena Seguros</option>
                                    <option value="Compañía de Seguros de Vida Aurora S.A." {{ $cliente->arl == 'Compañía de Seguros de Vida Aurora S.A.' ? 'selected' : '' }}>Compañía de Seguros de Vida Aurora S.A.</option>
                                    <option value="Seguros Bolívar S.A." {{ $cliente->arl == 'Seguros Bolívar S.A.' ? 'selected' : '' }}>Seguros Bolívar S.A.</option>
                                    <option value="La Equidad Seguros Generales Organismo Cooperativo" {{ $cliente->arl == 'La Equidad Seguros Generales Organismo Cooperativo' ? 'selected' : '' }}>La Equidad Seguros Generales Organismo Cooperativo</option>
                                    <option value="Positiva Compañía de Seguros S.A." {{ $cliente->arl == 'Positiva Compañía de Seguros S.A.' ? 'selected' : '' }}>Positiva Compañía de Seguros S.A.</option>
                                    <option value="Seguros ALFA S.A. y Seguros de Vida ALFA S.A." {{ $cliente->arl == 'Seguros ALFA S.A. y Seguros de Vida ALFA S.A.' ? 'selected' : '' }}>Seguros ALFA S.A. y Seguros de Vida ALFA S.A.</option>
                                    <option value="Seguros Generales Suramericana S.A." {{ $cliente->arl == 'Seguros Generales Suramericana S.A.' ? 'selected' : '' }}>Seguros Generales Suramericana S.A.</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">AFP</label>
                                <select class="form-control custom-select" name="afp" id="afp" disabled >
                                    <option value="">Seleccione AFP</option>
                                    <option value="Colpensiones" {{ $cliente->afp == 'Colpensiones' ? 'selected' : '' }}>Colpensiones</option>
                                    <option value="Protección S.A." {{ $cliente->afp == 'Protección S.A.' ? 'selected' : '' }}>Protección S.A.</option>
                                    <option value="Porvenir S.A." {{ $cliente->afp == 'Porvenir S.A.' ? 'selected' : '' }}>Porvenir S.A.</option>
                                    <option value="Colfondos Pensiones y Cesantías" {{ $cliente->afp == 'Colfondos Pensiones y Cesantías' ? 'selected' : '' }}>Colfondos Pensiones y Cesantías</option>
                                    <option value="Old Mutual" {{ $cliente->afp == 'Old Mutual' ? 'selected' : '' }}>Old Mutual</option>
                                </select>
                            </div>
                        </div> --}}

                        <hr class="w-100">

                        {{-- DOCUMENTACION CLIENTE --}}
                        <div class="card-body pt-0">
                            <div class="file_folder row">
                                <div class="col-md-4 {{ $cliente->cedula ? '' : 'd-none' }}" id="section_cedula">
                                    <a href="{{ asset('storage/'.$cliente->cedula) }}" target="_blank">
                                        <div class="icon">
                                            <i class="fa fa-file-o text-primary"></i>
                                        </div>
                                        <div class="file-name">
                                            <p class="mb-0 text-muted">Cedula</p>
                                            <small>Size: 68KB</small>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4 {{ $cliente->cedula ? 'd-none' : '' }}" id="input_cedula">
                                    <div class="form-group">
                                        <label class="form-label">Cedula</label>
                                        <input type="file" class="form-control" accept="application/pdf,image/png,image/jpg,image/jpeg" name="cedula" id="cedula" disabled />
                                    </div>
                                </div>

                                <div class="col-md-4 {{ $cliente->contrato ? '' : 'd-none' }}" id="section_contrato">
                                    <a href="{{ asset('storage/'.$cliente->contrato) }}" target="_blank">
                                        <div class="icon">
                                            <i class="fa fa-file-o text-primary"></i>
                                        </div>
                                        <div class="file-name">
                                            <p class="mb-0 text-muted">Camara de Comercio</p>
                                            <small>Size: 68KB</small>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4 {{ $cliente->contrato ? 'd-none' : '' }}" id="input_contrato">
                                    <div class="form-group">
                                        <label class="form-label">Camara de Comercio</label>
                                        <input type="file" class="form-control" accept="application/pdf,image/png,image/jpg,image/jpeg" name="contrato" id="contrato" disabled />
                                    </div>
                                </div>

                                <div class="col-md-4 {{ $cliente->poder ? '' : 'd-none' }}" id="section_poder">
                                    <a href="{{ asset('storage/'.$cliente->poder) }}" target="_blank">
                                        <div class="icon">
                                            <i class="fa fa-file-o text-primary"></i>
                                        </div>
                                        <div class="file-name">
                                            <p class="mb-0 text-muted">Rut</p>
                                            <small>Size: 68KB</small>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4 {{ $cliente->poder ? 'd-none' : '' }}" id="input_poder">
                                    <div class="form-group">
                                        <label class="form-label">Rut</label>
                                        <input type="file" class="form-control" accept="application/pdf,image/png,image/jpg,image/jpeg" name="poder" id="poder" disabled />
                                    </div>
                                </div>

                                {{-- <div class="col-md-3 {{ $cliente->titulo_valor ? '' : 'd-none' }}" id="section_titulo_valor">
                                    <a href="{{ asset('storage/'.$cliente->titulo_valor) }}" target="_blank">
                                        <div class="icon">
                                            <i class="fa fa-file-o text-primary"></i>
                                        </div>
                                        <div class="file-name">
                                            <p class="mb-0 text-muted">Titulo Valor</p>
                                            <small>Size: 68KB</small>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-3 {{ $cliente->titulo_valor ? 'd-none' : '' }}" id="input_titulo_valor">
                                    <div class="form-group">
                                        <label class="form-label">Titulo Valor</label>
                                        <input type="file" class="form-control" accept="application/pdf,image/png,image/jpg,image/jpeg" name="titulo_valor" id="titulo_valor" disabled />
                                    </div>
                                </div> --}}
                            </div>
                        </div>

                    </div>
                </div>

            </form>

            {{-- <div class="card-body pt-0">
                <div class="file_folder row">
                    @if ( $cliente->cedula )
                        <div class="col-md-3">
                            <a href="{{ asset('storage/docs/clientes/documentos/'.$cliente->cedula) }}" target="_blank">
                                <div class="icon">
                                    <i class="fa fa-file-o text-primary"></i>
                                </div>
                                <div class="file-name">
                                    <p class="mb-0 text-muted">Cedula</p>
                                    <small>Size: 68KB</small>
                                </div>
                            </a>
                        </div>
                    @else
                        <div class="col-md-3">
                            <div class="form-group">
                                <form action="/clientes/add-cedula" method="post" enctype="multipart/form-data">
                                    @csrf

                                    <label class="form-label">Cedula</label>
                                    <input type="file" class="form-control" accept="application/pdf,image/png,image/jpg,image/jpeg" name="cedula" id="cedula" required />
                                    <input type="hidden" name="id" value="{{ $cliente->id }}">
                                    <input type="hidden" name="identificacion" value="{{ $cliente->identificacion }}">
                                    <button type="submit" class="btn btn-primary btn-block mt-2">Subir Cedula</button>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if ( $cliente->contrato )
                        <div class="col-md-3">
                            <a href="{{ asset('storage/docs/clientes/documentos/'.$cliente->contrato) }}" target="_blank">
                                <div class="icon">
                                    <i class="fa fa-file-o text-primary"></i>
                                </div>
                                <div class="file-name">
                                    <p class="mb-0 text-muted">Contrato</p>
                                    <small>Size: 68KB</small>
                                </div>
                            </a>
                        </div>
                    @else
                        <div class="col-md-3">
                            <div class="form-group">
                                <form action="/clientes/add-contrato" method="post" enctype="multipart/form-data">
                                    @csrf

                                    <label class="form-label">Contrato</label>
                                    <input type="file" class="form-control" accept="application/pdf,image/png,image/jpg,image/jpeg" name="contrato" id="contrato" required />
                                    <input type="hidden" name="id" value="{{ $cliente->id }}">
                                    <input type="hidden" name="identificacion" value="{{ $cliente->identificacion }}">
                                    <button type="submit" class="btn btn-primary btn-block mt-2">Subir Contrato</button>
                                </form>
                            </div>
                        </div>
                    @endif

                </div>
            </div> --}}

        </div>

        <div class="row clearfix">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Procesos </h3>
                        <div class="card-options">
                            <a href="/procesos/crear?cliente={{ $cliente->identificacion }}" class="btn text-white bg-primary"><i class="fe fe-plus mr-2"></i> Agregar Proceso</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="/clientes/ver/{{ $cliente->id }}/search" id="form_search_proceso" method="get">
                            <input type="text" name="search" id="search_proceso" class="form-control mt-0 mb-2" placeholder="Buscar proceso...">
                        </form>
                        <table class="table table-hover table-vcenter table-striped">
                            <thead>
                                <tr>
                                    <th>Demandado/Demandante</th>
                                    <th>Tipo</th>
                                    <th>Radicado</th>
                                    <th>Juzgado</th>
                                    <th>Juez</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($procesos as $proceso)
                                    <tr>
                                        <td class="width50">
                                            <b>Demandado:</b> {{ \App\Models\Detalle_proceso::where('tipo', 'Demandado')->where('procesos_id', $proceso->id)->with('demandados')->first()->demandados->nombre ?? 'Sin demandados' }}
                                            <br>
                                            <b>Demandante:</b> {{ \App\Models\Detalle_proceso::where('tipo', 'Demandante')->where('procesos_id', $proceso->id)->with('demandados')->first()->demandados->nombre ?? 'Sin demandantes' }}
                                        </td>
                                        <td>{{ $proceso->tipo }}</td>
                                        <td>{{ $proceso->radicado ?? 'N/A' }}</td>
                                        <td>{{ $proceso->juzgado ?? 'N/A' }}</td>
                                        <td>{{ $proceso->juez ?? 'N/A' }}</td>
                                        <td>
                                            <a href="/procesos/ver/{{ $proceso->id }}"><button type="button" class="btn btn-primary btn-sm" title="Ver"><i class="fa fa-eye"></i></button></a>
                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection



