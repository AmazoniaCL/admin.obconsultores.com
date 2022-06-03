@extends('layouts.app')

@section('title_content') Procesos @endsection

@section('myStyles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker3.css') }}"/>
    <!-- Tempus Dominus Styles -->
    <link href="https://cdn.jsdelivr.net/gh/Eonasdan/tempus-dominus@master/dist/css/tempus-dominus.css" rel="stylesheet" crossorigin="anonymous">
@endsection

@section('myScripts')
    <script src="{{ asset('assets/plugins/fullcalendar/moment.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/popper.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/datetimepicker6.0.0.js') }}"></script>

    <script src="{{ asset('assets/js/procesos.js') }}"></script>
@endsection

@section('content')

<div class="section-body">

    <div class="col-lg-12">

        <a href="/clientes/ver/{{ $proceso[0]->clientes_id }}"><button type="button" class="btn btn-primary mb-2"><i class="fa fa-arrow-circle-left mr-2"></i> Atras </button></a>

        @hasanyrole('admin|general')
            <a href="/procesos/generar_informe/{{ $proceso[0]->id }}" target="_blank" class="float-right"><button type="button" class="btn btn-primary mb-2 ml-2"><i class="fa fa-print mr-2"></i> Generar informe </button></a>
            <a href="https://procesos.ramajudicial.gov.co/procesoscs/ConsultaJusticias21.aspx?EntryId=grBcWxPg0ZUShlbwdwhIP3U6ZKQ%3d" target="_blank" class="float-right"><button type="button" class="btn btn-primary mb-2 ml-2"><i class="fa fa-bank ml-2"></i> Rama Judicial </button></a>
        @endhasanyrole


        @if ($proceso[0]->users_id == auth()->user()->id || auth()->user()->hasRole('admin'))
            <a href="/procesos/ver/acceso/{{ $proceso[0]->id }}" class="btn btn-primary mb-2 float-right"><i class="fa fa-lock mr-2"></i> Acceso </a>
        @endif

        @if (session()->has('update') && session()->has('update') == 1)
            <div class="alert alert-icon alert-success col-12" role="alert">
                <i class="fe fe-check mr-2" aria-hidden="true"></i> Proceso actualizado correctamente.
            </div>
        @endif

        @if (session()->has('demandado') && session()->has('demandado') == 1)
            <div class="alert alert-icon alert-success col-12" role="alert">
                <i class="fe fe-check mr-2" aria-hidden="true"></i> Demandado agregado correctamente.
            </div>
        @endif

        @if (session()->has('demandado_update') && session()->has('demandado_update') == 1)
            <div class="alert alert-icon alert-success col-12" role="alert">
                <i class="fe fe-check mr-2" aria-hidden="true"></i> Demandado actualizado correctamente.
            </div>
        @endif

        @if (session()->has('juzgado') && session()->has('juzgado') == 1)
            <div class="alert alert-icon alert-success col-12" role="alert">
                <i class="fe fe-check mr-2" aria-hidden="true"></i> Se actualizo la información del juzgado correctamente.
            </div>
        @endif

        @if (session()->has('fiscal') && session()->has('fiscal') == 1)
            <div class="alert alert-icon alert-success col-12" role="alert">
                <i class="fe fe-check mr-2" aria-hidden="true"></i> Se actualizo la información de la fiscalía correctamente.
            </div>
        @endif

        @if (session()->has('archivo') && session()->has('archivo') == 1)
            <div class="alert alert-icon alert-success col-12" role="alert">
                <i class="fe fe-check mr-2" aria-hidden="true"></i> Archivo agregado correctamente.
            </div>
        @endif

        <div class="card card-collapsed collapse" id="collapseAcceso">
            <div class="card-header">
                <h3 class="card-title">Demandante/s</h3>
                <div class="card-options">

                    @if ($detalle_proceso_demandante->count() == 0)
                        <p><b>No hay demandantes</b></p>
                    @else
                        <p><b>Hay {{ $detalle_proceso_demandante->count() }} demandantes</b></p>
                    @endif

                    <a href="#" class="card-options-collapse mr-3" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>

                    <button type="button" class="btn btn-primary btn-sm" onclick="agg_demandante()" data-toggle="collapse" data-target="#agg_demandante" aria-expanded="false" aria-controls="agg_demandante"><i class="fe fe-plus mr-2"></i> Agregar </button>

                </div>
            </div>
            <div class="card-body">
                <div class="row">

                    <div class="card p-5 collapse" id="agg_demandante" style="border: solid 1px #cda854;background: #e9ecef !important;">

                        <form action="/procesos/agregar_demandado" method="post">
                            @csrf

                            <p><b>Demandante</b></p>
                            <hr>

                            <div class="row">
                                <div class="form-group col-2">
                                    <label class="form-label">Identificacion</label>
                                    <input type="number" name="identificacion_demandante_1" class="form-control" onblur="buscar_demandante(1)" placeholder="Escriba la identificacion" required>
                                </div>
                                <div class="form-group col-3">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" name="nombre_demandante_1" class="form-control" placeholder="Escriba el nombre" required  readonly>
                                </div>
                                <div class="form-group col-2">
                                    <label class="form-label">Telefono</label>
                                    <input type="number" class="form-control" name="telefono_demandante_1" placeholder="Escriba el telefono" readonly>
                                </div>
                                <div class="form-group col-3">
                                    <label class="form-label">Correo</label>
                                    <input type="email" class="form-control" name="correo_demandante_1" placeholder="Escriba el correo" readonly>
                                </div>
                                <div class="form-group col-2">
                                    <label class="form-label">Direccion</label>
                                    <input type="text" class="form-control" name="direccion_demandante_1" placeholder="Escriba la direccion" readonly>
                                </div>
                                <input type="hidden" name="existe_demandante_1" />
                            </div>

                            <p><b>Apoderado</b></p>
                            <hr>

                            <div class="row">
                                <div class="form-group col-2">
                                    <label class="form-label">Identificacion</label>
                                    <input type="number" name="identificacion_abogado_1" class="form-control" onblur="buscar_abogado_demandante(1)" placeholder="Escriba la identificacion">
                                </div>
                                <div class="form-group col-3">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" name="nombre_abogado_1" class="form-control" placeholder="Escriba el nombre" required readonly>
                                </div>
                                <div class="form-group col-2">
                                    <label class="form-label">Telefono</label>
                                    <input type="number" class="form-control" name="telefono_abogado_1" placeholder="Escriba el telefono" readonly>
                                </div>
                                <div class="form-group col-3">
                                    <label class="form-label">Correo</label>
                                    <input type="email" class="form-control" name="correo_abogado_1" placeholder="Escriba el correo" readonly>
                                </div>
                                <div class="form-group col-2">
                                    <label class="form-label">T. Profesional</label>
                                    <input type="text" class="form-control" name="direccion_abogado_1" placeholder="Escriba la TP" readonly>
                                </div>
                                <input type="hidden" name="existe_abogado_demandante_1" />
                            </div>

                            <input type="hidden" name="procesos_id" value="{{ $proceso[0]->id }}" />

                            <button type="submit" class="btn btn-primary btn-block">Enviar</button>

                        </form>

                    </div>

                    <table class="table table-bordered">
                        @foreach ($detalle_proceso_demandante as $key => $row)
                            <thead>
                                <tr>
                                    <th colspan="5" class="text-center"><b>Demandante {{ $key + 1 }}</b></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        Nombre <br>
                                        <b>{{ $row->demandados->nombre }}</b>
                                    </td>
                                    <td>
                                        Identifiacion <br>
                                        <b>{{ $row->demandados->identificacion ?? 'N/A' }}</b>
                                    </td>
                                    <td>
                                        Telefono <br>
                                        <b>{{ $row->demandados->telefono ?? 'N/A' }}</b>
                                    </td>
                                    <td>
                                        Correo <br>
                                        <b>{{ $row->demandados->correo ?? 'N/A' }}</b>
                                    </td>
                                    <td>
                                        Direccion <br>
                                        <b>{{ $row->demandados->direccion ?? 'N/A' }}</b>
                                    </td>
                                    <td rowspan="2" class="text-center">
                                        <button type="button" class="btn btn-primary btn-sm" onclick="editar_demandante({{ $row->id }})" title="Editar"><i class="fa fa-edit"></i></button>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="eliminar_demandado({{ $row->id }})" title="Eliminar"><i class="fa fa-trash-o"></i></button>
                                    </td>
                                    <tr>
                                        <td>
                                            Apoderado <br>
                                            <b>{{ $row->abogados->nombre ?? 'N/A' }}</b>
                                        </td>
                                        <td>
                                            Identifiacion <br>
                                            <b>{{ $row->abogados->identificacion ?? 'N/A' }}</b>
                                        </td>
                                        <td>
                                            Telefono <br>
                                            <b>{{ $row->abogados->telefono ?? 'N/A' }}</b>
                                        </td>
                                        <td>
                                            Correo <br>
                                            <b>{{ $row->abogados->correo ?? 'N/A' }}</b>
                                        </td>
                                        <td>
                                            Tarjeta Profesional <br>
                                            <b>{{ $row->abogados->direccion ?? 'N/A' }}</b>
                                        </td>
                                    </tr>
                                </tr>
                            </tbody>
                        @endforeach
                    </table>

                </div>
            </div>
        </div>

        <div class="alert alert-icon alert-success col-12 d-none" id="delete_confirmed" role="alert">
            <i class="fe fe-check mr-2" aria-hidden="true"></i> Demandado eliminado correctamente
        </div>

        <form action="/procesos/update" method="post" style="display: contents;" enctype="multipart/form-data">
            @csrf

            <div class="card card-collapsed mb-2 mt-3" id="card_proceso">
                <div class="card-header">
                    <h3 class="card-title">Proceso {{ $proceso[0]->tipo }} {{ $proceso[0]->radicado ?? $proceso[0]->num_proceso }}</h3>
                    <div class="card-options">

                        <a href="#" class="card-options-collapse mr-3" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>

                        @hasanyrole('admin|general')
                            <button type="button" class="btn btn-primary btn-sm" id="btn_habilitar_actualizar_proceso" onclick="habilitar_formularo_proceso()"><i class="fe fe-edit mr-2"></i> Actualizar </button>
                            <button type="submit" class="btn text-white btn-sm bg-green d-none" id="btn_enviar_actualizar_proceso"><i class="fe fe-check mr-2"></i> Enviar </button>
                            <button type="button" class="btn text-white btn-sm bg-red ml-1 d-none" id="btn_cancelar_actualizar_proceso" onclick="deshabilitar_formularo_proceso()"><i class="fa fa-times"></i>  </button>
                        @endhasanyrole
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">

                        <input type="hidden" name="cliente_id" value="{{ $proceso[0]->clientes->id }}">

                        <div class="{{ $proceso[0]->clientes->tipo_cliente == 'Juridica' ? 'col-md-2' : 'col-md-3' }}">
                            <div class="form-group">
                                <label class="form-label">{{ $proceso[0]->clientes->tipo_cliente == 'Juridica' ? 'Nit' : 'Identificacíon' }}</label>
                                <input type="number" class="form-control" name="identificacion" id="identificacion" required readonly value="{{ $proceso[0]->clientes->identificacion }}">
                            </div>
                        </div>
                        @if ($proceso[0]->clientes->tipo_cliente == 'Juridica')
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label class="form-label">D.V.</label>
                                    <input type="number" class="form-control" name="verificacion" id="verificacion" required readonly value="{{ $proceso[0]->clientes->verificacion }}">
                                </div>
                            </div>
                        @endif
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control" name="nombre" id="nombre" required readonly value="{{ $proceso[0]->clientes->nombre }}">
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-3">
                            <div class="form-group">
                                <label class="form-label">Celular</label>
                                <input type="number" class="form-control" name="celular" id="celular" readonly value="{{ $proceso[0]->clientes->celular }}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label class="form-label">Correo 1</label>
                                <input type="email" class="form-control" name="correo" id="correo" required readonly value="{{ $proceso[0]->clientes->correo }}">
                            </div>
                        </div>
                        {{-- <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                                <label class="form-label">Telefono</label>
                                <input type="number" class="form-control" name="telefono" id="telefono" readonly value="{{ $proceso[0]->clientes->telefono }}">
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                                <label class="form-label">Celular</label>
                                <input type="number" class="form-control" name="celular" id="celular" readonly value="{{ $proceso[0]->clientes->celular }}">
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                                <label class="form-label">Direccion</label>
                                <input type="text" class="form-control" name="direccion" id="direccion" required readonly value="{{ $proceso[0]->clientes->direccion }}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label class="form-label">Correo 1</label>
                                <input type="email" class="form-control" name="correo" id="correo" required readonly value="{{ $proceso[0]->clientes->correo }}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label class="form-label">Correo 2</label>
                                <input type="email" class="form-control" name="correo_dos" id="correo_dos" readonly value="{{ $proceso[0]->clientes->correo_dos }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">EPS</label>
                                <select class="form-control custom-select" name="eps" id="eps" disabled>
                                    <option value="">Seleccione EPS</option>
                                    <option value="ALIANSALUD" {{ $proceso[0]->clientes->eps == 'ALIANSALUD' ? 'selected' : '' }}>ALIANSALUD</option>
                                    <option value="COMFENALCO VALLE E.P.S." {{ $proceso[0]->clientes->eps == 'COMFENALCO VALLE E.P.S.' ? 'selected' : '' }}>COMFENALCO VALLE E.P.S.</option>
                                    <option value="SURAMERICANA" {{ $proceso[0]->clientes->eps == 'SURAMERICANA' ? 'selected' : '' }}>SURAMERICANA</option>
                                    <option value="CAFE SALUD" {{ $proceso[0]->clientes->eps == 'CAFE SALUD' ? 'selected' : '' }}>CAFE SALUD</option>
                                    <option value="COLPATRIA" {{ $proceso[0]->clientes->eps == 'COLPATRIA' ? 'selected' : '' }}>COLPATRIA</option>
                                    <option value="COMPENSAR" {{ $proceso[0]->clientes->eps == 'COMPENSAR' ? 'selected' : '' }}>COMPENSAR</option>
                                    <option value="CRUZ BLANCA" {{ $proceso[0]->clientes->eps == 'CRUZ BLANCA' ? 'selected' : '' }}>CRUZ BLANCA</option>
                                    <option value="SALUD TOTAL" {{ $proceso[0]->clientes->eps == 'SALUD TOTAL' ? 'selected' : '' }}>SALUD TOTAL</option>
                                    <option value="SANITAS" {{ $proceso[0]->clientes->eps == 'SANITAS' ? 'selected' : '' }}>SANITAS</option>
                                    <option value="COOMEVA" {{ $proceso[0]->clientes->eps == 'COOMEVA' ? 'selected' : '' }}>COOMEVA</option>
                                    <option value="NUEVA EPS" {{ $proceso[0]->clientes->eps == 'NUEVA EPS' ? 'selected' : '' }}>NUEVA EPS</option>
                                    <option value="MEDIMAS" {{ $proceso[0]->clientes->eps == 'MEDIMAS' ? 'selected' : '' }}>MEDIMAS</option>
                                    <option value="SALUD COOP" {{ $proceso[0]->clientes->eps == 'SALUD COOP' ? 'selected' : '' }}>SALUD COOP</option>
                                    <option value="ASMET SALUD" {{ $proceso[0]->clientes->eps == 'ASMET SALUD' ? 'selected' : '' }}>ASMET SALUD</option>
                                    <option value="ASOCIACION INDIGENA DEL CAUCA" {{ $proceso[0]->clientes->eps == 'ASOCIACION INDIGENA DEL CAUCA' ? 'selected' : '' }}>ASOCIACION INDIGENA DEL CAUCA</option>
                                    <option value="CAFAM" {{ $proceso[0]->clientes->eps == 'CAFAM' ? 'selected' : '' }}>CAFAM</option>
                                    <option value="CAJA DE COMPENSACION FAMILIAR DEL HUILA 'COMFAMILIAR'" {{ $proceso[0]->clientes->eps == "CAJA DE COMPENSACION FAMILIAR DEL HUILA 'COMFAMILIAR'" ? 'selected' : '' }}>CAJA DE COMPENSACION FAMILIAR DEL HUILA 'COMFAMILIAR'</option>
                                    <option value="CAPRESOCA" {{ $proceso[0]->clientes->eps == 'CAPRESOCA' ? 'selected' : '' }}>CAPRESOCA</option>
                                    <option value="COMPARTA" {{ $proceso[0]->clientes->eps == 'COMPARTA' ? 'selected' : '' }}>COMPARTA</option>
                                    <option value="ECOOPSOS" {{ $proceso[0]->clientes->eps == 'ECOOPSOS' ? 'selected' : '' }}>ECOOPSOS</option>
                                    <option value="CAPRECOM" {{ $proceso[0]->clientes->eps == 'CAPRECOM' ? 'selected' : '' }}>CAPRECOM</option>
                                    <option value="COLSUBSIDIO" {{ $proceso[0]->clientes->eps == 'COLSUBSIDIO' ? 'selected' : '' }}>COLSUBSIDIO</option>
                                    <option value="COMFACUNDI" {{ $proceso[0]->clientes->eps == 'COMFACUNDI' ? 'selected' : '' }}>COMFACUNDI</option>
                                    <option value="CONVIDA" {{ $proceso[0]->clientes->eps == 'CONVIDA' ? 'selected' : '' }}>CONVIDA</option>
                                    <option value="HUMANA VIVIR" {{ $proceso[0]->clientes->eps == 'HUMANA VIVIR' ? 'selected' : '' }}>HUMANA VIVIR</option>
                                    <option value="SALUD VIDA" {{ $proceso[0]->clientes->eps == 'SALUD VIDA' ? 'selected' : '' }}>SALUD VIDA</option>
                                    <option value="SOL SALUD" {{ $proceso[0]->clientes->eps == 'SOL SALUD' ? 'selected' : '' }}>SOL SALUD</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">ARL</label>
                                <select class="form-control custom-select" name="arl" id="arl" disabled >
                                    <option value="">Seleccione ARL</option>
                                    <option value="Axa Colpatria Seguros S.A." {{ $proceso[0]->clientes->arl == 'Axa Colpatria Seguros S.A.' ? 'selected' : '' }}>Axa Colpatria Seguros S.A.</option>
                                    <option value="Colmena Seguros" {{ $proceso[0]->clientes->arl == 'Colmena Seguros' ? 'selected' : '' }}>Colmena Seguros</option>
                                    <option value="Compañía de Seguros de Vida Aurora S.A." {{ $proceso[0]->clientes->arl == 'Compañía de Seguros de Vida Aurora S.A.' ? 'selected' : '' }}>Compañía de Seguros de Vida Aurora S.A.</option>
                                    <option value="Seguros Bolívar S.A." {{ $proceso[0]->clientes->arl == 'Seguros Bolívar S.A.' ? 'selected' : '' }}>Seguros Bolívar S.A.</option>
                                    <option value="La Equidad Seguros Generales Organismo Cooperativo" {{ $proceso[0]->clientes->arl == 'La Equidad Seguros Generales Organismo Cooperativo' ? 'selected' : '' }}>La Equidad Seguros Generales Organismo Cooperativo</option>
                                    <option value="Positiva Compañía de Seguros S.A." {{ $proceso[0]->clientes->arl == 'Positiva Compañía de Seguros S.A.' ? 'selected' : '' }}>Positiva Compañía de Seguros S.A.</option>
                                    <option value="Seguros ALFA S.A. y Seguros de Vida ALFA S.A." {{ $proceso[0]->clientes->arl == 'Seguros ALFA S.A. y Seguros de Vida ALFA S.A.' ? 'selected' : '' }}>Seguros ALFA S.A. y Seguros de Vida ALFA S.A.</option>
                                    <option value="Seguros Generales Suramericana S.A." {{ $proceso[0]->clientes->arl == 'Seguros Generales Suramericana S.A.' ? 'selected' : '' }}>Seguros Generales Suramericana S.A.</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">AFP</label>
                                <select class="form-control custom-select" name="afp" id="afp" disabled >
                                    <option value="">Seleccione AFP</option>
                                    <option value="Colpensiones" {{ $proceso[0]->clientes->afp == 'Colpensiones' ? 'selected' : '' }}>Colpensiones</option>
                                    <option value="Protección S.A." {{ $proceso[0]->clientes->afp == 'Protección S.A.' ? 'selected' : '' }}>Protección S.A.</option>
                                    <option value="Porvenir S.A." {{ $proceso[0]->clientes->afp == 'Porvenir S.A.' ? 'selected' : '' }}>Porvenir S.A.</option>
                                    <option value="Colfondos Pensiones y Cesantías" {{ $proceso[0]->clientes->afp == 'Colfondos Pensiones y Cesantías' ? 'selected' : '' }}>Colfondos Pensiones y Cesantías</option>
                                    <option value="Old Mutual" {{ $proceso[0]->clientes->afp == 'Old Mutual' ? 'selected' : '' }}>Old Mutual</option>
                                </select>
                            </div>
                        </div> --}}

                        {{-- DOCUMENTACION CLIENTE --}}
                        {{-- <div class="card-body pt-0">
                            <div class="file_folder row">
                                <div class="col-md-3 {{ $proceso[0]->clientes->cedula ? '' : 'd-none' }}" id="section_cedula">
                                    <a href="{{ asset('storage/'.$proceso[0]->clientes->cedula) }}" target="_blank">
                                        <div class="icon">
                                            <i class="fa fa-file-o text-primary"></i>
                                        </div>
                                        <div class="file-name">
                                            <p class="mb-0 text-muted">Cedula</p>
                                            <small>Size: 68KB</small>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-3 {{ $proceso[0]->clientes->cedula ? 'd-none' : '' }}" id="input_cedula">
                                    <div class="form-group">
                                        <label class="form-label">Cedula</label>
                                        <input type="file" class="form-control" accept="application/pdf,image/png,image/jpg,image/jpeg" name="cedula" id="cedula" disabled />
                                    </div>
                                </div>

                                <div class="col-md-3 {{ $proceso[0]->clientes->contrato ? '' : 'd-none' }}" id="section_contrato">
                                    <a href="{{ asset('storage/'.$proceso[0]->clientes->contrato) }}" target="_blank">
                                        <div class="icon">
                                            <i class="fa fa-file-o text-primary"></i>
                                        </div>
                                        <div class="file-name">
                                            <p class="mb-0 text-muted">Contrato</p>
                                            <small>Size: 68KB</small>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-3 {{ $proceso[0]->clientes->contrato ? 'd-none' : '' }}" id="input_contrato">
                                    <div class="form-group">
                                        <label class="form-label">Contrato</label>
                                        <input type="file" class="form-control" accept="application/pdf,image/png,image/jpg,image/jpeg" name="contrato" id="contrato" disabled />
                                    </div>
                                </div>

                            </div>
                        </div> --}}

                        <hr class="w-100">

                        <input type="hidden" name="proceso_id" value="{{ $proceso[0]->id }}">

                        <div class="{{ $proceso[0]->tipo == 'Insolvencia' ? 'col-md-4' : 'col-md-3' }}">
                            <div class="form-group">
                                <label class="form-label">Area</label>
                                <select class="form-control custom-select" name="tipo" id="tipo" onchange="cargar_subarea(this.value)" disabled required>
                                    <option value="">Seleccione el area</option>
                                    <option value="Civil" {{ $proceso[0]->tipo == 'Civil' ? 'selected' : '' }}>Civil</option>
                                    <option value="Familia" {{ $proceso[0]->tipo == 'Familia' ? 'selected' : '' }}>Familia</option>
                                    <option value="Laboral" {{ $proceso[0]->tipo == 'Laboral' ? 'selected' : '' }}>Laboral</option>
                                    <option value="Seguridad Social" {{ $proceso[0]->tipo == 'Seguridad Social' ? 'selected' : '' }}>Seguridad Social</option>
                                    <option value="Administrativo" {{ $proceso[0]->tipo == 'Administrativo' ? 'selected' : '' }}>Administrativo</option>
                                    <option value="Penal" {{ $proceso[0]->tipo == 'Penal' ? 'selected' : '' }}>Penal</option>
                                    <option value="Derecho de Petición" {{ $proceso[0]->tipo == 'Derecho de Petición' ? 'selected' : '' }}>Derecho de Petición</option>
                                    <option value="Acción de Tutela" {{ $proceso[0]->tipo == 'Acción de Tutela' ? 'selected' : '' }}>Acción de Tutela</option>
                                    <option value="Insolvencia" {{ $proceso[0]->tipo == 'Insolvencia' ? 'selected' : '' }}>Insolvencia</option>
                                    <option value="Otros" {{ $proceso[0]->tipo == 'Otros' ? 'selected' : '' }}>Otros</option>
                                </select>
                            </div>
                        </div>
                        <div class="{{ $proceso[0]->tipo == 'Insolvencia' ? 'col-md-4' : 'col-md-3' }}">
                            <div class="form-group">
                                <label class="form-label">Sub Area</label>
                                <div id="sub_tipo_div">
                                    <select class="form-control custom-select" name="sub_tipo" id="sub_tipo" disabled>
                                        <option value="">Debe seleccionar el area</option>
                                        <option value="{{ $proceso[0]->sub_tipo }}" selected>{{ $proceso[0]->sub_tipo }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        @if ($proceso[0]->tipo == 'Insolvencia')
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Tipo</label>
                                    <select class="form-control custom-select" name="tipo_insolvencia" id="tipo_insolvencia" disabled>
                                        <option value="">Debe seleccionar el area</option>
                                        <option value="{{ $proceso[0]->tipo_insolvencia }}" selected>{{ $proceso[0]->tipo_insolvencia }}</option>
                                    </select>
                                </div>
                            </div>
                        @endif

                        <div class="{{ $proceso[0]->tipo == 'Insolvencia' ? 'col-md-6' : 'col-md-3' }}">
                            <div class="form-group">
                                <label class="form-label">Departamento</label>
                                <input type="text" class="form-control" name="departamento" id="departamento" readonly value="{{ $proceso[0]->departamento }}">
                            </div>
                        </div>
                        <div class="{{ $proceso[0]->tipo == 'Insolvencia' ? 'col-md-6' : 'col-md-3' }}">
                            <div class="form-group">
                                <label class="form-label">Ciudad</label>
                                <input type="text" class="form-control" name="ciudad" id="ciudad" readonly value="{{ $proceso[0]->ciudad }}">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group mb-0">
                                <label class="form-label">Descripcion y Observaciones</label>
                                <textarea rows="10" class="form-control" name="descripcion" id="descripcion" readonly>{{ $proceso[0]->descripcion }}</textarea>
                            </div>
                        </div>

                        <div class="card-body {{ $proceso[0]->proceso_file ? '' : 'd-none' }}" id="section_proceso_file">
                            <div class="file_folder">
                                <a href="{{ asset('storage/'.$proceso[0]->proceso_file) }}" target="_blank">
                                    <div class="icon">
                                        <i class="fa fa-file-o text-primary"></i>
                                    </div>
                                    <div class="file-name">
                                        <p class="mb-0 text-muted">Archivo de Proceso</p>
                                        <small>Size: 68KB</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3 {{ $proceso[0]->proceso_file ? 'd-none' : '' }}" id="input_proceso_file">
                            <div class="form-group">
                                <label class="form-label">Documento del proceso</label>
                                <input type="file" class="form-control" accept="application/pdf,image/png,image/jpg,image/jpeg" name="proceso_file" id="proceso_file" disabled />
                            </div>
                        </div>
                        <div class="col-md-3 {{ $proceso[0]->contrato ? 'd-none' : '' }}" id="input_contrato_file">
                            <div class="form-group">
                                <label class="form-label">Contrato</label>
                                <input type="file" class="form-control" accept="application/pdf,image/png,image/jpg,image/jpeg" name="contrato" id="contrato_file" disabled />
                            </div>
                        </div>
                        <div class="col-md-3 {{ $proceso[0]->poder ? 'd-none' : '' }}" id="input_poder">
                            <div class="form-group">
                                <label class="form-label">Poder</label>
                                <input type="file" class="form-control" accept="application/pdf,image/png,image/jpg,image/jpeg" name="poder" id="poder" disabled />
                            </div>
                        </div>
                        <div class="col-md-3 {{ $proceso[0]->titulo_valor ? 'd-none' : '' }}" id="input_titulo_valor">
                            <div class="form-group">
                                <label class="form-label">Titulo Valor</label>
                                <input type="file" class="form-control" accept="application/pdf,image/png,image/jpg,image/jpeg" name="titulo_valor" id="titulo_valor" disabled />
                            </div>
                        </div>

                        <hr class="w-100">

                        <h3 class="card-title col-12">Responsable</h3>

                        <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                                <label class="form-label">Identificacion</label>
                                <input type="email" class="form-control" readonly value="{{ $proceso[0]->users->identificacion }}">
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control" readonly value="{{ $proceso[0]->users->name }}">
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                                <label class="form-label">Correo</label>
                                <input type="text" class="form-control" readonly value="{{ $proceso[0]->users->email }}">
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </form>

        <form action="/procesos/juzgado" method="post" style="display: contents;" enctype="multipart/form-data">
            @csrf

            <div class="card card-collapsed mb-2" id="card_juzgado">
                <div class="card-header">
                    <h3 class="card-title">Juzgado</h3>
                    <div class="card-options">

                        @if (!$proceso[0]->radicado)
                            <p><b>Aun no hay Radicado</b></p>
                        @elseif (!$proceso[0]->juzgado)
                            <p><b>Aun no hay Juzgado</b></p>
                        @elseif (!$proceso[0]->juez)
                            <p><b>Aun no hay Juez</b></p>
                        @endif

                        <a href="#" class="card-options-collapse mr-3" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>

                        @hasanyrole('admin|general')
                            <button type="button" class="btn btn-primary btn-sm" id="btn_habilitar_actualizar_juzgado" onclick="habilitar_formularo_juzgado()"><i class="fe fe-edit mr-2"></i> Actualizar </button>
                            <button type="submit" class="btn text-white btn-sm bg-green d-none" id="btn_enviar_actualizar_juzgado"><i class="fe fe-check mr-2"></i> Enviar </button>
                            <button type="button" class="btn text-white btn-sm bg-red ml-1 d-none" id="btn_cancelar_actualizar_juzgado" onclick="deshabilitar_formularo_juzgado()"><i class="fa fa-times"></i>  </button>
                        @endhasanyrole
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">

                        <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                                <label class="form-label">Radicado</label>
                                <input type="text" class="form-control" name="radicado" id="radicado" readonly value="{{ $proceso[0]->radicado }}">
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                                <label class="form-label">Juzgado</label>
                                <input type="text" class="form-control" name="juzgado" id="juzgado" readonly value="{{ $proceso[0]->juzgado }}">
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                                <label class="form-label">Juez</label>
                                <input type="text" class="form-control" name="juez" id="juez" readonly value="{{ $proceso[0]->juez }}">
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                                <label class="form-label">Telefono</label>
                                <input type="number" class="form-control" name="telefono" id="telefono_juzgado" readonly value="{{ $proceso[0]->telefono }}">
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                                <label class="form-label">Direccion</label>
                                <input type="text" class="form-control" name="direccion" id="direccion_juzgado" readonly value="{{ $proceso[0]->direccion }}">
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                                <label class="form-label">Correo</label>
                                <input type="email" class="form-control" name="correo" id="correo_juzgado" readonly value="{{ $proceso[0]->correo }}">
                            </div>
                        </div>

                        <input type="hidden" name="proceso_id" value="{{ $proceso[0]->id }}">

                    </div>
                </div>
            </div>

        </form>

        <div class="card card-collapsed mb-2" id="card_archivos">
            <div class="card-header">
                <h3 class="card-title">Archivos del proceso</h3>
                <div class="card-options">
                    @if ($archivos->count() == 0)
                        <p><b>No hay archivos</b></p>
                    @else
                        <p><b>Hay {{ $archivos->count() }} archivos</b></p>
                    @endif

                    <a href="#" class="card-options-collapse mr-3" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                    <button type="button" class="btn btn-primary btn-sm" onclick="agregar_archivos()" data-toggle="collapse" data-target="#agg_archivos" aria-expanded="false" aria-controls="agg_archivos"><i class="fe fe-plus mr-2"></i> Agregar </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="card p-5 collapse" id="agg_archivos" style="border: solid 1px #cda854;background: #e9ecef !important;">
                        <form action="/procesos/agg_archivos" id="form_agg_archivos" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="form-group col-8">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Escriba el nombre">
                                </div>
                                <div class="form-group col-3">
                                    <label class="form-label">Archivo</label>
                                    <input type="file" class="form-control" accept="application/pdf,image/png,image/jpg,image/jpeg" name="file" id="file" required />
                                </div>
                                <input type="hidden" name="procesos_id" value="{{ $proceso[0]->id }}">

                                <div class="form-group col-1">
                                    <button type="submit" class="btn btn-primary btn-lg text-center mt-4" id="btn_agg_archivos">Enviar</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="alert alert-icon alert-success col-12 d-none" id="delete_confirmed_archivo" role="alert">
                        <i class="fe fe-check mr-2" aria-hidden="true"></i> Archivo eliminado correctamente
                    </div>

                    @if ($archivos->count() > 0)
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th></th>
                                </tr>
                            </thead>
                            @foreach ($archivos as $key => $row)
                                <tbody>
                                    <tr>
                                        <td>
                                            <b>{{ $key + 1 }}</b>
                                        </td>
                                        <td>
                                            {{ $row->nombre }}
                                        </td>
                                        <td rowspan="2" class="text-center">
                                            <a href="{{ asset('/storage/'.$row->file) }}" target="_blank">
                                                <button type="button" class="btn btn-primary btn-sm" title="Ver"><i class="fa fa-eye"></i></button>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm" onclick="eliminar_archivo({{ $row->id }})" title="Eliminar"><i class="fa fa-trash-o"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            @endforeach
                        </table>
                    @endif
                </div>
            </div>
        </div>

        @if ($proceso[0]->tipo == "Penal")
            <form action="/procesos/fiscalia" method="post" style="display: contents;" enctype="multipart/form-data">
                @csrf

                <div class="card card-collapsed mb-2" id="card_fiscalia">
                    <div class="card-header">
                        <h3 class="card-title">Fiscalía</h3>
                        <div class="card-options">

                            @if (!$proceso[0]->fiscalia)
                                <p><b>Aun no hay Fiscalía</b></p>
                            @elseif (!$proceso[0]->fiscal)
                                <p><b>Aun no hay Fiscal</b></p>
                            @endif

                            <a href="#" class="card-options-collapse mr-3" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>

                            <button type="button" class="btn btn-primary btn-sm" id="btn_habilitar_actualizar_fiscalia" onclick="habilitar_formularo_fiscalia()"><i class="fe fe-edit mr-2"></i> Actualizar </button>
                            <button type="submit" class="btn text-white btn-sm bg-green d-none" id="btn_enviar_actualizar_fiscalia"><i class="fe fe-check mr-2"></i> Enviar </button>
                            <button type="button" class="btn text-white btn-sm bg-red ml-1 d-none" id="btn_cancelar_actualizar_fiscalia" onclick="deshabilitar_formularo_fiscalia()"><i class="fa fa-times"></i>  </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-2 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Fiscalía</label>
                                    <input type="text" class="form-control" name="fiscalia" id="fiscalia" readonly value="{{ $proceso[0]->fiscalia }}">
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Fiscal</label>
                                    <input type="text" class="form-control" name="fiscal" id="fiscal" readonly value="{{ $proceso[0]->fiscal }}">
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label class="form-label">Telefono</label>
                                    <input type="number" class="form-control" name="telefono_fiscal" id="telefono_fiscal" readonly value="{{ $proceso[0]->telefono_fiscal }}">
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label class="form-label">Direccion</label>
                                    <input type="text" class="form-control" name="direccion_fiscal" id="direccion_fiscal" readonly value="{{ $proceso[0]->direccion_fiscal }}">
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label class="form-label">Correo</label>
                                    <input type="email" class="form-control" name="correo_fiscal" id="correo_fiscal" readonly value="{{ $proceso[0]->correo_fiscal }}">
                                </div>
                            </div>

                            <input type="hidden" name="proceso_id" value="{{ $proceso[0]->id }}">

                        </div>
                    </div>
                </div>

            </form>
        @endif

        <div class="card card-collapsed mb-2" id="card_demandantes">
            <div class="card-header">
                <h3 class="card-title">Demandante/s</h3>
                <div class="card-options">

                    @if ($detalle_proceso_demandante->count() == 0)
                        <p><b>No hay demandantes</b></p>
                    @else
                        <p><b>Hay {{ $detalle_proceso_demandante->count() }} demandantes</b></p>
                    @endif

                    <a href="#" class="card-options-collapse mr-3" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>

                    @hasanyrole('admin|general')
                        <button type="button" class="btn btn-primary btn-sm" onclick="agg_demandante()" data-toggle="collapse" data-target="#agg_demandante" aria-expanded="false" aria-controls="agg_demandante"><i class="fe fe-plus mr-2"></i> Agregar </button>
                    @endhasanyrole

                </div>
            </div>
            <div class="card-body">
                <div class="row">

                    <div class="card p-5 collapse" id="agg_demandante" style="border: solid 1px #cda854;background: #e9ecef !important;">

                        <form action="/procesos/agregar_demandado" id="form_agg_demandante" method="post">
                            @csrf

                            <p><b>Demandante</b></p>
                            <hr>

                            <div class="row">
                                <div class="form-group col-1">
                                    <label class="form-label">Tipo</label>
                                    <select name="tipo_demandante_1" class="form-control" required onchange="select_tipo_demandante(this.value)">
                                        <option value="">Seleccione</option>
                                        <option value="Natural">Natural</option>
                                        <option value="Juridica">Juridica</option>
                                    </select>
                                </div>
                                <div class="form-group col-2">
                                    <label class="form-label" id="tipo_demandante_label_1">Identificacion</label>
                                    <div class="row">
                                        <input type="number" name="identificacion_demandante_1" id="identificacion_demandante_1" class="form-control col-8" onblur="buscar_demandante(1)" placeholder="Escriba la identificacion" required>
                                        <input type="number" name="verificacion_demandante_1" id="verificacion_demandante_1" class="form-control col-4 d-none" placeholder="CV">
                                    </div>
                                </div>
                                <div class="form-group col-2">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" name="nombre_demandante_1" id="nombre_demandante_1" class="form-control" placeholder="Escriba el nombre" required  readonly>
                                </div>
                                <div class="form-group col-2">
                                    <label class="form-label">Telefono</label>
                                    <input type="number" class="form-control" name="telefono_demandante_1" id="telefono_demandante_1" placeholder="Escriba el telefono" readonly>
                                </div>
                                <div class="form-group col-3">
                                    <label class="form-label">Correo</label>
                                    <input type="email" class="form-control" name="correo_demandante_1" id="correo_demandante_1" placeholder="Escriba el correo" readonly>
                                </div>
                                <div class="form-group col-2">
                                    <label class="form-label">Direccion</label>
                                    <input type="text" class="form-control" name="direccion_demandante_1" id="direccion_demandante_1" placeholder="Escriba la direccion" readonly>
                                </div>
                                <input type="hidden" name="existe_demandante_1" id="existe_demandante_1" />
                            </div>

                            <p><b>Apoderado</b></p>
                            <hr>

                            <div class="row">
                                <div class="form-group col-2">
                                    <label class="form-label">Identificacion</label>
                                    <input type="number" name="identificacion_abogado_1" id="identificacion_abogado_demandante_1" class="form-control" onblur="buscar_abogado_demandante(1)" placeholder="Escriba la identificacion">
                                </div>
                                <div class="form-group col-3">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" name="nombre_abogado_1" id="nombre_abogado_demandante_1" class="form-control" placeholder="Escriba el nombre" required readonly>
                                </div>
                                <div class="form-group col-2">
                                    <label class="form-label">Telefono</label>
                                    <input type="number" class="form-control" name="telefono_abogado_1" id="telefono_abogado_demandante_1" placeholder="Escriba el telefono" readonly>
                                </div>
                                <div class="form-group col-3">
                                    <label class="form-label">Correo</label>
                                    <input type="email" class="form-control" name="correo_abogado_1" id="correo_abogado_demandante_1" placeholder="Escriba el correo" readonly>
                                </div>
                                <div class="form-group col-2">
                                    <label class="form-label">T. Profesional</label>
                                    <input type="text" class="form-control" name="direccion_abogado_1" id="direccion_abogado_demandante_1" placeholder="Escriba la TP" readonly>
                                </div>
                                <input type="hidden" name="existe_abogado_demandante_1" id="existe_abogado_demandante_1" />
                            </div>

                            <input type="hidden" name="procesos_id" value="{{ $proceso[0]->id }}" />
                            <input type="hidden" name="detalle_proceso_id" id="detalle_proceso_demandante_id" />
                            <input type="hidden" name="demandante_id" id="demandante_id" />
                            <input type="hidden" name="abogado_demandante_id" id="abogado_id" />
                            <input type="hidden" name="tipo_proceso" value="Demandante" />

                            <button type="submit" class="btn btn-primary btn-block">Enviar</button>

                        </form>

                    </div>

                    <table class="table table-bordered">
                        @foreach ($detalle_proceso_demandante as $key => $row)
                            <thead>
                                <tr>
                                    <th colspan="5" class="text-center"><b>Demandante {{ $key + 1 }}</b></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        Nombre <br>
                                        <b>{{ $row->demandados->nombre }}</b>
                                    </td>
                                    <td>
                                        {{ $row->demandados->tipo == 'Juridica' ? 'Nit' : 'Identificación' }} <br>
                                        <b>{{ $row->demandados->identificacion ?? 'N/A' }}{{ $row->demandados->tipo == 'Juridica' ? '-'.$row->demandados->verificacion : '' }}</b>
                                    </td>
                                    <td>
                                        Telefono <br>
                                        <b>{{ $row->demandados->telefono ?? 'N/A' }}</b>
                                    </td>
                                    <td>
                                        Correo <br>
                                        <b>{{ $row->demandados->correo ?? 'N/A' }}</b>
                                    </td>
                                    <td>
                                        Direccion <br>
                                        <b>{{ $row->demandados->direccion ?? 'N/A' }}</b>
                                    </td>
                                    <td rowspan="2" class="text-center">
                                        <button type="button" class="btn btn-primary btn-sm" onclick="editar_demandante({{ $row->id }})" title="Editar"><i class="fa fa-edit"></i></button>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="eliminar_demandado({{ $row->id }})" title="Eliminar"><i class="fa fa-trash-o"></i></button>
                                    </td>
                                    <tr>
                                        <td>
                                            Apoderado <br>
                                            <b>{{ $row->abogados->nombre ?? 'N/A' }}</b>
                                        </td>
                                        <td>
                                            Identifiacion <br>
                                            <b>{{ $row->abogados->identificacion ?? 'N/A' }}</b>
                                        </td>
                                        <td>
                                            Telefono <br>
                                            <b>{{ $row->abogados->telefono ?? 'N/A' }}</b>
                                        </td>
                                        <td>
                                            Correo <br>
                                            <b>{{ $row->abogados->correo ?? 'N/A' }}</b>
                                        </td>
                                        <td>
                                            Tarjeta Profesional <br>
                                            <b>{{ $row->abogados->direccion ?? 'N/A' }}</b>
                                        </td>
                                    </tr>
                                </tr>
                            </tbody>
                        @endforeach
                    </table>

                </div>
            </div>
        </div>

        <div class="card card-collapsed mb-2" id="card_demandados">
            <div class="card-header">
                <h3 class="card-title">Demandado/s</h3>
                <div class="card-options">

                    @if ($detalle_proceso_demandado->count() == 0)
                        <p><b>No hay demandados</b></p>
                    @else
                        <p><b>Hay {{ $detalle_proceso_demandado->count() }} demandados</b></p>
                    @endif

                    <a href="#" class="card-options-collapse mr-3" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>

                    @hasanyrole('admin|general')
                        <button type="button" class="btn btn-primary btn-sm" onclick="agg_demandado()" data-toggle="collapse" data-target="#agg_demandado" aria-expanded="false" aria-controls="agg_demandado"><i class="fe fe-plus mr-2"></i> Agregar </button>
                    @endhasanyrole

                </div>
            </div>
            <div class="card-body">
                <div class="row">

                    <div class="card p-5 collapse" id="agg_demandado" style="border: solid 1px #cda854;background: #e9ecef !important;">

                        <form action="/procesos/agregar_demandado" id="form_agg_demandado" method="post">
                            @csrf

                            <p><b>Demandado</b></p>
                            <hr>

                            <div class="row">
                                <div class="form-group col-1">
                                    <label class="form-label">Tipo</label>
                                    <select name="tipo_demandado_1" class="form-control" required onchange="select_tipo_demandado(this.value)">
                                        <option value="">Seleccione</option>
                                        <option value="Natural">Natural</option>
                                        <option value="Juridica">Juridica</option>
                                    </select>
                                </div>
                                <div class="form-group col-2">
                                    <label class="form-label" id="tipo_demandado_label_1">Identificacion</label>
                                    <div class="row">
                                        <input type="number" name="identificacion_demandado_1" id="identificacion_demandado_1" class="form-control col-8" onblur="buscar_demandado(1)" placeholder="Escriba la identificacion" required>
                                        <input type="number" name="verificacion_demandado_1" id="verificacion_demandado_1" class="form-control col-4 d-none" placeholder="CV">
                                    </div>

                                </div>
                                <div class="form-group col-2">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" name="nombre_demandado_1" id="nombre_demandado_1" class="form-control" placeholder="Escriba el nombre" required  readonly>
                                </div>
                                <div class="form-group col-2">
                                    <label class="form-label">Telefono</label>
                                    <input type="number" class="form-control" name="telefono_demandado_1" id="telefono_demandado_1" placeholder="Escriba el telefono" readonly>
                                </div>
                                <div class="form-group col-3">
                                    <label class="form-label">Correo</label>
                                    <input type="email" class="form-control" name="correo_demandado_1" id="correo_demandado_1" placeholder="Escriba el correo" readonly>
                                </div>
                                <div class="form-group col-2">
                                    <label class="form-label">Direccion</label>
                                    <input type="text" class="form-control" name="direccion_demandado_1" id="direccion_demandado_1" placeholder="Escriba la direccion" readonly>
                                </div>
                                <input type="hidden" name="existe_demandado_1" id="existe_demandado_1" />
                            </div>

                            <p><b>Apoderado</b></p>
                            <hr>

                            <div class="row">
                                <div class="form-group col-2">
                                    <label class="form-label">Identificacion</label>
                                    <input type="number" name="identificacion_abogado_1" id="identificacion_abogado_1" class="form-control" onblur="buscar_abogado(1)" placeholder="Escriba la identificacion" >
                                </div>
                                <div class="form-group col-3">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" name="nombre_abogado_1" id="nombre_abogado_1" class="form-control" placeholder="Escriba el nombre" required readonly>
                                </div>
                                <div class="form-group col-2">
                                    <label class="form-label">Telefono</label>
                                    <input type="number" class="form-control" name="telefono_abogado_1" id="telefono_abogado_1" placeholder="Escriba el telefono" readonly>
                                </div>
                                <div class="form-group col-3">
                                    <label class="form-label">Correo</label>
                                    <input type="email" class="form-control" name="correo_abogado_1" id="correo_abogado_1" placeholder="Escriba el correo" readonly>
                                </div>
                                <div class="form-group col-2">
                                    <label class="form-label">T. Profesional</label>
                                    <input type="text" class="form-control" name="direccion_abogado_1" id="direccion_abogado_1" placeholder="Escriba la TP" readonly>
                                </div>
                                <input type="hidden" name="existe_abogado_1" id="existe_abogado_1" />
                            </div>

                            <input type="hidden" name="procesos_id" value="{{ $proceso[0]->id }}" />
                            <input type="hidden" name="detalle_proceso_id" id="detalle_proceso_demandado_id" />
                            <input type="hidden" name="demandados_id" id="demandados_id" />
                            <input type="hidden" name="abogados_id" id="abogados_id" />
                            <input type="hidden" name="tipo_proceso" value="Demandado" />

                            <button type="submit" class="btn btn-primary btn-block">Enviar</button>

                        </form>

                    </div>

                    <table class="table table-bordered">
                        @foreach ($detalle_proceso_demandado as $key => $row)
                            <thead>
                                <tr>
                                    <th colspan="5" class="text-center"><b>Demandado {{ $key + 1 }}</b></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        Nombre<br>
                                        <b>{{ $row->demandados->nombre }}</b>
                                    </td>
                                    <td>
                                        {{ $row->demandados->tipo == 'Juridica' ? 'Nit' : 'Identificación' }}<br>
                                        <b>{{ $row->demandados->identificacion ?? 'N/A' }}{{ $row->demandados->tipo == 'Juridica' ? '-'.$row->demandados->verificacion : '' }}</b>
                                    </td>
                                    <td>
                                        Telefono<br>
                                        <b>{{ $row->demandados->telefono ?? 'N/A' }}</b>
                                    </td>
                                    <td>
                                        Correo<br>
                                        <b>{{ $row->demandados->correo ?? 'N/A' }}</b>
                                    </td>
                                    <td>
                                        Direccion<br>
                                        <b>{{ $row->demandados->direccion ?? 'N/A' }}</b>
                                    </td>
                                    <td rowspan="2" class="text-center">
                                        <button type="button" class="btn btn-primary btn-sm" onclick="editar_demandado({{ $row->id }})" title="Editar"><i class="fa fa-edit"></i></button>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="eliminar_demandado({{ $row->id }})" title="Eliminar"><i class="fa fa-trash-o"></i></button>
                                    </td>
                                    <tr>
                                        <td>
                                            Apoderado <br>
                                            <b>{{ $row->abogados->nombre ?? 'N/A' }}</b>
                                        </td>
                                        <td>
                                            Identifiacion<br>
                                            <b>{{ $row->abogados->identificacion ?? 'N/A' }}</b>
                                        </td>
                                        <td>
                                            Telefono<br>
                                            <b>{{ $row->abogados->telefono ?? 'N/A' }}</b>
                                        </td>
                                        <td>
                                            Correo<br>
                                            <b>{{ $row->abogados->correo ?? 'N/A' }}</b>
                                        </td>
                                        <td>
                                            Tarjeta Profesional<br>
                                            <b>{{ $row->abogados->direccion ?? 'N/A' }}</b>
                                        </td>
                                    </tr>
                                </tr>
                            </tbody>
                        @endforeach
                    </table>

                </div>
            </div>
        </div>

        <div class="card card-collapsed mb-2" id="card_audiencias">
            <div class="card-header">
                <h3 class="card-title">Audiencias</h3>
                <div class="card-options">
                    @if ($audiencias->count() == 0)
                        <p><b>No hay audiencias</b></p>
                    @else
                        <p><b>Hay {{ $audiencias->count() }} audiencias</b></p>
                    @endif

                    <a href="#" class="card-options-collapse mr-3" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>

                    <button type="button" class="btn btn-primary btn-sm" onclick="agregar_audiencia()" data-toggle="collapse" data-target="#agg_audiencia" aria-expanded="false" aria-controls="agg_audiencia"><i class="fe fe-plus mr-2"></i> Agregar </button>

                </div>
            </div>
            <div class="card-body">
                <div class="row">

                    <div class="card p-5 collapse" id="agg_audiencia" style="border: solid 1px #cda854;background: #e9ecef !important;">
                        <form action="/procesos/agg_audiencia" id="form_agg_audiencia" method="post">
                            @csrf

                                <div class="row">
                                    <div class="form-group col-3">
                                        <label class="form-label">Fecha</label>

                                        <div class='input-group' id='fecha_audiencia' data-td-target-input='nearest' data-td-target-toggle='nearest'>
                                            <input id='fecha_audienciaInput' name="fecha_audiencia" type='text' class='form-control' data-td-target='#fecha_audiencia' />
                                            <span class='input-group-text' data-td-target='#fecha_audiencia' data-td-toggle='datetimepicker'>
                                                <span class='fa-solid fa-calendar'></span>
                                            </span>
                                        </div>

                                        {{-- <input type='text' class="form-control" name="fecha_audiencia" id="fecha_audiencia" placeholder="MM/DD/AAAA" autocomplete="off" required/> --}}
                                        {{-- <input data-provide="datepicker" data-date-format='yyyy-mm-dd h:i:s' data-date-autoclose="true" name="fecha_audiencia" id="fecha_audiencia" class="form-control" placeholder="MM/DD/AAAA" autocomplete="off" required> --}}
                                    </div>
                                    <div class="form-group col-8">
                                        <label class="form-label">Observaciones</label>
                                        <input type="text" class="form-control" name="observaciones" id="observaciones" placeholder="Escriba las observaciones">
                                    </div>
                                    <input type="hidden" name="procesos_id" value="{{ $proceso[0]->id }}">
                                    <input type="hidden" name="audiencia_id" id="audiencia_id" value="">

                                    <div class="form-group col-1">
                                        <button type="submit" class="btn btn-primary btn-lg text-center mt-4" id="btn_agg_audiencia">Enviar</button>
                                    </div>
                                </div>

                        </form>
                    </div>

                    @if ($audiencias->count() > 0)
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Observaciones</th>
                                    <th>Fecha</th>
                                    <th></th>
                                </tr>
                            </thead>
                            @foreach ($audiencias as $key => $row)
                                <tbody>
                                    <tr>
                                        <td>
                                            <b>{{ $key + 1 }}</b>
                                        </td>
                                        <td>
                                            {{ $row->observaciones }}
                                        </td>
                                        <td>
                                            <b>{{ $row->fecha ?? 'N/A' }}
                                        </td>
                                        <td rowspan="2" class="text-center">
                                            <button type="button" class="btn btn-primary btn-sm" onclick="editar_audiencia({{ $row->id }})" title="Editar"><i class="fa fa-edit"></i></button>
                                            <button type="button" class="btn btn-danger btn-sm" onclick="eliminar_audiencia({{ $row->id }})" title="Eliminar"><i class="fa fa-trash-o"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            @endforeach
                        </table>
                    @endif

                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-md-12">
                @if (session()->has('update_actuacion') && session()->has('update_actuacion') == 1)
                    <div class="alert alert-icon alert-success col-12" role="alert">
                        <i class="fe fe-check mr-2" aria-hidden="true"></i> Actuacion actualizada correctamente.
                    </div>
                @endif

                @if (session()->has('audiencia') && session()->has('audiencia') == 1)
                    <div class="alert alert-icon alert-success col-12" role="alert">
                        <i class="fe fe-check mr-2" aria-hidden="true"></i> Audiencia agregada correctamente.
                    </div>
                @endif

                @if (session()->has('audiencia_update') && session()->has('audiencia_update') == 1)
                    <div class="alert alert-icon alert-success col-12" role="alert">
                        <i class="fe fe-check mr-2" aria-hidden="true"></i> Audiencia actualizada correctamente.
                    </div>
                @endif
                <div class="alert alert-icon alert-success col-12 d-none" id="delete_confirmed" role="alert">
                    <i class="fe fe-check mr-2" aria-hidden="true"></i> Actuacion eliminada correctamente
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Actuaciones </h3>
                        <div class="card-options">

                            @if (isset($proxima_audiencia))
                                <h4 class="mr-3">Proxima Audiencia en: {{ ($proxima_audiencia == 'Hoy') ? 'Hoy' : $proxima_audiencia.' Dias' }} </h4>
                                @hasanyrole('admin|general')
                                    <button type="button" onclick="editar_audiencia({{ $audiencias[0]->id ?? '' }}, '{{ $audiencias[0]->fecha ?? '' }}', '{{ $audiencias[0]->observaciones ?? '' }}')" class="btn btn-primary btn-sm mr-2"><i class="fe fe-edit"></i></button>
                                @endhasanyrole

                            @else
                                @hasanyrole('admin|general')
                                    {{-- <button type="button" onclick="agregar_audiencia()" data-toggle="collapse" data-target="#agg_audiencia" aria-expanded="false" aria-controls="agg_audiencia" class="btn btn-primary btn-sm mr-2">Agregar audiencia</button> --}}
                                @endhasanyrole
                            @endif
                            {{-- @if (isset($proxima_audiencia))
                            <h4 class="mr-3">Proxima Audiencia en: {{ ($proxima_audiencia == 'Hoy') ? 'Hoy' : $proxima_audiencia.' Dias' }} </h4>
                            <button type="button" onclick="editar_audiencia({{ $audiencias[0]->id ?? '' }}, '{{ $audiencias[0]->fecha ?? '' }}', '{{ $audiencias[0]->observaciones ?? '' }}')" class="btn btn-primary btn-sm mr-2"><i class="fe fe-edit"></i></button>
                            @else
                                <button type="button" onclick="agregar_audiencia()" data-toggle="collapse" data-target="#agg_audiencia" aria-expanded="false" aria-controls="agg_audiencia" class="btn btn-primary btn-sm mr-2">Agregar audiencia</button>
                            @endif --}}

                            @hasanyrole('admin|general')
                                <button type="button" onclick="agregar_actuacion()" data-toggle="collapse" data-target="#agg_actuacion" aria-expanded="false" aria-controls="agg_actuacion" class="btn btn-primary btn-sm">Agregar +</button>
                            @endhasanyrole

                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <div class="card p-5 collapse" id="agg_actuacion" style="border: solid 1px #cda854;background: #e9ecef !important;">
                            <form action="/procesos/agregar_actuacion" id="form_agg_actuacion" method="post" enctype="multipart/form-data">
                                @csrf

                                    <div class="row">
                                        <div class="form-group col-3">
                                            <label class="form-label">Fecha</label>
                                            <input data-provide="datepicker" data-date-format='yyyy-mm-dd' data-date-autoclose="true" name="fecha" id="fecha" class="form-control" placeholder="MM/DD/AAAA" required autocomplete="off" >
                                        </div>
                                        <div class="form-group col-12">
                                            <label class="form-label">Actuacion</label>
                                            <textarea rows="5" class="form-control" name="actuacion" id="actuacion" placeholder="Escriba la actuación" required ></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Anotacion</label>
                                        <textarea rows="10" placeholder="Escriba la anotación" class="form-control" name="anotacion" id="anotacion"></textarea>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-6">
                                            <label class="form-label">Fecha Inicio Termino</label>
                                            <input data-provide="datepicker" autocomplete="off" data-date-format='yyyy-mm-dd' data-date-autoclose="true" name="f_inicio_termino" id="f_inicio_termino" class="form-control" placeholder="MM/DD/AAAA" >
                                        </div>
                                        <div class="form-group col-6">
                                            <label class="form-label">Fecha Fin Termino</label>
                                            <input data-provide="datepicker" autocomplete="off" data-date-format='yyyy-mm-dd' data-date-autoclose="true" name="f_fin_termino" id="f_fin_termino" class="form-control" placeholder="MM/DD/AAAA" >
                                        </div>

                                        <div id="contenedor-archivo" class="row col-12 d-flex">
                                            <div class="form-group col-6">
                                                <label class="form-label">Nombre del archivo</label>
                                                <input type="text" class="form-control" name="nombre_archivo" id="nombre_archivo" placeholder="Escriba el nombre">
                                            </div>
                                            <div class="form-group col-6">
                                                <label class="form-label">Archivo</label>
                                                <input type="file" class="form-control" accept="application/pdf,image/png,image/jpg,image/jpeg" name="anotacion_file" id="anotacion_file" />
                                            </div>
                                        </div>

                                        {{-- <div class="form-group col-4">
                                            <label class="form-label">Archivo de la anotación</label>
                                            <input type="file" class="form-control" accept="application/pdf, .doc, .docx" name="anotacion_file[]" id="anotacion_file[]" multiple>
                                        </div> --}}
                                    </div>

                                    <input type="hidden" name="procesos_id" id="procesos_id" value="{{ $proceso[0]->id }}">
                                    <input type="hidden" name="actuacion_id" id="actuacion_id" value="">

                                    <button type="submit" class="btn btn-primary btn-lg text-center" id="btn_agg_actuacion">Agregar</button>
                                    <button type="button" onclick="cancelar_update_actuacion()" class="btn btn-secondary btn-lg text-center d-none" id="btn_cancelar_actuacion">Cancelar</button>

                            </form>
                        </div>

                        <div class="card p-5 collapse" id="agg_actuacion_archivos" style="border: solid 1px #cda854;background: #e9ecef !important;">
                            <form action="/procesos/actuaciones/archivos/add" id="form_agg_actuacion_archivos" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-8">
                                        <label class="form-label">Nombre</label>
                                        <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Escriba el nombre">
                                    </div>
                                    <div class="form-group col-3">
                                        <label class="form-label">Archivo</label>
                                        <input type="file" class="form-control" accept="application/pdf,image/png,image/jpg,image/jpeg" name="anotacion_file" id="anotacion_file" required />
                                    </div>

                                    <input type="hidden" name="actuaciones_id" id="actuaciones_id" value="">
                                    <input type="hidden" name="archivo_actuaciones_id" id="archivo_actuaciones_id" value="">

                                    <div class="form-group col-1">
                                        <button type="submit" class="btn btn-primary btn-lg text-center mt-4" id="btn_agg_archivos">Enviar</button>
                                    </div>
                                </div>
                            </form>

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="content_archivos_actuacion">

                                </tbody>
                            </table>
                        </div>

                        <table class="table table-hover table-vcenter table-striped">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Actuación</th>
                                    <th>Anotación</th>
                                    <th>Fecha Inicio Termino</th>
                                    <th>Fecha Fin Termino</th>
                                    <th>Tipo</th>
                                    <th>Archivo</th>
                                </tr>
                            </thead>
                            <tbody>

                                @if($proceso[0]->actuaciones)
                                    @foreach ($proceso[0]->actuaciones as $actuacion)
                                        <tr>
                                            <td class="width40">{{ $actuacion->fecha }}</td>
                                            <td>{{ $actuacion->actuacion }}</td>
                                            <td>{{ $actuacion->anotacion ?? 'No aplica' }}</td>
                                            <td>{{ $actuacion->f_inicio_termino ?? 'No aplica' }}</td>
                                            <td>{{ $actuacion->f_fin_termino ?? 'No aplica' }}</td>
                                            <td>{{ $actuacion->idsincronizacion ? 'Sincronizado' : 'Manual' }}</td>
                                            <td class="text-center">
                                                {{-- @if ($actuacion->anotacion_file)
                                                    <a href="/storage/{{ $actuacion->anotacion_file }}" target="_blank" class="h5"><i class="fa fa-file"></i></a>
                                                @endif
                                                @if($actuacion->anotaciones)
                                                    @foreach ($actuacion->anotaciones as $anotacion)
                                                        <a href="http://admin.obconsultores.com/storage/{{ $anotacion->anotacion_file }}" title="{{$anotacion->anotacion_file}}" target="_blank" class="h5"><i class="fa fa-file"></i></a>
                                                    @endforeach
                                                @endif --}}

                                                @hasanyrole('admin|general')
                                                    @if (!$actuacion->idsincronizacion)
                                                        <a href="javascript:;" onclick="archivos_actuacion({{ $actuacion->id }})" class="ml-2 text-info h5"><i class="fa fa-file"></i></a>
                                                        <a href="javascript:;" onclick="update_actuacion({{ $actuacion->id }})" class="ml-2 text-dark h5"><i class="fa fa-pencil"></i></a>
                                                        <a href="javascript:;" onclick="eliminar_actuacion({{ $actuacion->id }})" class="ml-2 text-red h5"><i class="fa fa-close"></i></a>
                                                    @endif
                                                @endhasanyrole
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection



