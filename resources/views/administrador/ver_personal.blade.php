@extends('layouts.app')

@section('title_content') Personal @endsection

@section('myStyles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/multi-select/css/multi-select.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}"/>
@endsection

@section('myScripts')
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/administracion.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-multiselect/bootstrap-multiselect.js') }}"></script>
    <script src="{{ asset('assets/plugins/multi-select/js/jquery.multi-select.js') }}"></script>
    <script>$('#multiselect2').multiselect({ maxHeight: 500 });</script>
@endsection

@section('content')

<div class="section-body">

    <div class="col-lg-12">

        <a href="{{route('personal')}}"><button type="button" class="btn btn-primary mb-2"><i class="fa fa-arrow-circle-left mr-2"></i> Atras </button></a>

        <div class="card p-5 collapse" id="agg_usuario" style="border: solid 1px #cda854;background: #e9ecef !important;">
            <button type="button" class="close position-absolute" style="width: fit-content;right: 1%;top: 1%;" aria-label="Close" onclick="ocultar_collapse('#agg_usuario')">
            </button>
            <h3 class="card-title" id="title_agg_usuario">Agregar Usuario</h3>
            <form action="/administrador/usuarios/create" id="form_agg_usuario" method="post" enctype="multipart/form-data">
                @csrf

                    <div class="row">
                        <div class="form-group col-6">
                            <label class="form-label">Identificacion</label>
                            <input type="number" class="form-control" value="{{$persona->identificacion}}" name="identificacion" id="identificacion" placeholder="Escriba la identificacion" required >
                        </div>
                        <div class="form-group col-6">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="name" value="{{$persona->nombres .' '.$persona->primer_apellido.' '. $persona->segundo_apellido}}" id="name" placeholder="Escriba el nombre" required >
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label class="form-label">Correo</label>
                            <input type="text" class="form-control" value="{{$persona->correo}}" name="email" id="email" placeholder="Escriba el correo" >
                        </div>
                        <div class="form-group col-6">
                            <label class="form-label">Contraseña</label>
                            <input type="password" class="form-control" name="password" placeholder="Escriba la contraseña" {{!$usuario->password ? 'required' : ''}} >
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label class="form-label">Rol</label>
                            <select name="rol" id="rol" class="form-control" required>
                                <option value="">Seleccione el rol</option>
                                <option value="admin" {{$usuario->roles[0]->name == "admin" ? 'selected' : ''}}>Administrador</option>
                                <option value="general" {{$usuario->roles[0]->name == "general" ? 'selected' : ''}}>General</option>
                                <option value="cliente" {{$usuario->roles[0]->name == "cliente" ? 'selected' : ''}}>Cliente</option>
                            </select>
                        </div>
                        <div class="form-group col-6">
                            <label class="form-label">Estado</label>
                            <select name="estado" id="estado" class="form-control" required>
                                <option value="">Seleccione el estado</option>
                                <option value="Activo" {{$usuario->estado == "Activo" ? 'selected' : ''}}>Activo</option>
                                <option value="Inactivo" {{$usuario->estado == "Inactivo" ? 'selected' : ''}}>Inactivo</option>
                            </select>
                        </div>
                        {{-- <div class="form-group multiselect_div col-6">
                            <label class="form-label">Permisos</label>
                            <select id="multiselect2" name="permisos[]" class="multiselect multiselect-custom" multiple="multiple" required>
                                @foreach (\Spatie\Permission\Models\Permission::all() as $permiso)
                                    <option value="{{ $permiso->name }}">{{ $permiso->name }}</option>
                                @endforeach
                            </select>
                        </div> --}}
                    </div>

                    <input type="hidden" name="id" value="{{ $usuario->id }}">
                    <button type="submit" class="btn btn-primary btn-lg text-center"  id="btn_agg_usuario">Agregar Usuario</button>
            </form>
        </div>



        <div class="card bg-none p-3">
            <div class="card-header">
                <h3 class="card-title">Personal {{$persona->nombres}}</h3>
                <div class="card-options">
                        <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#agg_usuario" aria-expanded="false"> Asignar contraseña </button>
                </div>
            </div>
            <div class="card-body pt-0">


                @if (session()->has('creado') && session('creado') == 1)
                    <div class="alert alert-icon alert-{{ session('tipo') }} col-12" id="alert" role="alert">
                        <i class="fe fe-check mr-2" aria-hidden="true"></i> {{ session('mensaje') }}
                    </div>
                @endif

                <div class="table-responsive table_e2">
                    <table class="table table-bordered mb-3">
                        @php
                        $id;
                            switch ($persona->tipo_identificacion) {
                                case 'Cedula de ciudadania':
                                    $id="CC";
                                    break;
                                case 'Cedula de Extranjeria':
                                    $id="CE";
                                    break;
                                case 'Nit':
                                    $id="Nit";
                                    break;
                                case 'Registro Civil':
                                    $id="RC";
                                    break;
                            }
                        @endphp
                        <tbody>
                                <tr>
                                    <td>
                                        <b>Nombre</b>
                                    </td>
                                    <td>
                                        <span>{{$persona->nombres . ' ' . $persona->primer_apellido .' '. $persona->segundo_apellido }}</span>
                                    </td>
                                    <td>
                                        <b>Identificacion</b>
                                    </td>
                                    <td>
                                        <span>{{$id}}. {{$persona->identificacion}}</span>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <b>Teléfono</b>
                                    </td>
                                    <td>
                                        <span>{{$persona->telefonos}}</span>
                                    </td>
                                    <td>
                                        <b>Correo</b>
                                    </td>
                                    <td>
                                        <span>{{$persona->correo}}</span>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <b>Sexo</b>
                                    </td>
                                    <td>
                                        <span>{{$persona->sexo}}</span>
                                    </td>
                                    <td>
                                        <b>RH</b>
                                    </td>
                                    <td>
                                        <span>{{$persona->rh}}</span>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <b>Estado</b>
                                    </td>
                                    <td>
                                        <span>{{$persona->estado}}</span>
                                    </td>
                                    <td>
                                        <b>Viculación</b>
                                    </td>
                                    <td>
                                        <span>{{$persona->tipo_vinculacion}}</span>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <b>Dirección</b>
                                    </td>
                                    <td>
                                        <span>{{$persona->direccion}}</span>
                                    </td>
                                    <td>
                                        <b>Tarjeta Profesional</b>
                                    </td>
                                    <td>
                                        <span>{{$persona->tarjetaprofesional}}</span>
                                    </td>
                                </tr>
                        </tbody>
                    </table>
                </div>

                <div class="card p-5 collapse" id="agg_documento" style="border: solid 1px #cda854;background: ##f3f0f0 !important;">
                    <button type="button" class="close position-absolute" style="width: fit-content;right: 1%;top: 1%;" aria-label="Close" onclick="ocultar_collapse('#agg_documento')">
                    </button>
                    <h3 class="card-title" id="agg_title_documento"> </h3>
                    <form action="" id="form_agg_documento" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="container p-3">

                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="tipo" id="consecutivo_title">Tipo Documento</label>
                                    <div class="form-group form-group-custom mb-4">
                                        <input type="text" class="form-control" id="tipo" name="tipo" required readonly>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label for="fecha_expedicion">Fecha expedición</label>
                                    <div class="form-group form-group-custom mb-4">
                                        <input type="text" class="form-control datepicker-here" autocomplete="off" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-autoclose="true" id="fecha_expedicion" name="fecha_expedicion" required="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6 d-none" id="fecha_inicio_vigencia_div">
                                    <label for="fecha_inicio_vigencia">Fecha inicio de vigencia</label>
                                    <div class="form-group form-group-custom mb-4">
                                        <input type="text" class="form-control datepicker-here" autocomplete="off" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-autoclose="true" name="fecha_inicio_vigencia"  id="fecha_inicio_vigencia">
                                    </div>
                                </div>
                                <div class="col-sm-6 d-none" id="fecha_fin_vigencia_div">
                                    <label for="fecha_fin_vigencia">Fecha fin de vigencia</label>
                                    <div class="form-group form-group-custom mb-4">
                                        <input type="text" class="form-control datepicker-here" autocomplete="off" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-autoclose="true" name="fecha_fin_vigencia"  id="fecha_fin_vigencia">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <label for="observaciones">Observaciones</label>
                                    <div class="form-group mb-4">
                                        <textarea name="observaciones" id="observaciones" class="form-control" rows="7" ></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <label for="adjunto">Agregar Adjunto</label>
                                    <div class="form-group form-group-custom mb-4">
                                        <input type="file" class="form-control" name="adjunto" id="adjunto" required>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="id" id="id">
                            <input type="hidden" name="isfecha" id="isfecha">
                            <input type="hidden" name="id_table" id="id_table">
                            <input type="hidden" name="personal_id" value="{{ $persona->id }}">

                        </div>

                        <div class="mt-3 text-center">
                            <button class="btn btn-primary btn-lg waves-effect waves-light" type="submit">Enviar</button>
                        </div>

                    </form>
                </div>

                <div class="card p-5 collapse" id="collapse_ver_documento" style="border: solid 1px #cda854;background: ##f3f0f0 !important;">
                    <button type="button" class="close position-absolute" style="width: fit-content;right: 1%;top: 1%;" aria-label="Close" onclick="ocultar_collapse('#collapse_ver_documento')">
                    </button>
                    <h3 class="card-title" id="collapse_ver_documento_title"> </h3>
                    <div class="collapse-body" id="collapse_ver_documento_content">

                    </div>
                </div>

                <div id="accordion" class="col-12">
                    {{-- TAB CONTRATO LABORAL --}}
                    <div class="card mb-1">
                        <a data-toggle="collapse" onclick="cargar_contratos({{ $persona->id }})" data-parent="#accordion" href="#collapseContratos" aria-expanded="false" aria-controls="collapseContratos" class="text-dark collapsed">
                            <div class="card-header bg-primary" id="headingOne">
                                <h6 class="ml-4 fs-1 text-white">CONTRATO LABORAL</h6>
                                <i class="fa fa-chevron-down text-white position-absolute" style="right: 3%" aria-hidden="true"></i>
                            </div>
                        </a>

                        <div id="collapseContratos" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">

                                <button class="btn btn-primary waves-effect waves-light mb-2 float-right" data-toggle="collapse" data-target="#agg_contrato" onclick="agg_contrato_ini()"><i class="fa fa-plus"></i></button>

                                <div class="card p-5 collapse" id="agg_contrato" style="border: solid 1px #cda854;background: #e9ecef !important;">
                                    <button type="button" class="close position-absolute" style="width: fit-content;right: 1%;top: 1%;" aria-label="Close" onclick="ocultar_collapse('#agg_contrato')">
                                    </button>
                                    <form action="" id="form_agg_contrato" method="post">
                                        @csrf

                                        <div class="container p-4">

                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label for="salario">Salario</label>
                                                    <div class="form-group form-group-custom mb-4">
                                                        <input type="number" class="form-control" id="salario" name="salario" placeholder="Escriba el salario" required="">
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label for="estado">Estado</label>
                                                    <div class="form-group form-group-custom mb-4">
                                                        <select name="estado" id="estado" class="form-control" required>
                                                            <option value="">Seleccione</option>
                                                            <option value="Activo">Activo</option>
                                                            <option value="Terminado">Terminado</option>
                                                            <option value="Suspendido">Suspendido</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label for="tipo_contrato">Tipo contrato</label>
                                                    <div class="form-group form-group-custom mb-4">
                                                        <select name="tipo_contrato" id="tipo_contrato" onchange="tipo_contrato_select(this.value)" class="form-control" required>
                                                            <option value="">Seleccione</option>
                                                            <option value="Obra labor">Obra labor</option>
                                                            <option value="Termino fijo">Termino fijo</option>
                                                            <option value="Termino indefinido">Termino indefinido</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-6 d-none" id="fecha_inicio_div">
                                                    <label for="fecha_inicio">Fecha inicio</label>
                                                    <div class="form-group form-group-custom mb-4">
                                                        <input type="text" class="form-control" autocomplete="off" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-autoclose="true" name="fecha_inicio"  id="fecha_inicio" >
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 d-none" id="fecha_fin_div">
                                                    <label for="fecha_fin">Fecha fin</label>
                                                    <div class="form-group form-group-custom mb-4">
                                                        <input type="text" class="form-control" autocomplete="off" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-autoclose="true" name="fecha_fin"  id="fecha_fin">
                                                    </div>
                                                </div>
                                            </div>

                                            <hr>

                                            <div class="row d-none" id="clausulas_div">
                                                <div class="col-sm-12">
                                                    <label for="clausulas_parte_uno">Clausulas parte uno</label>
                                                    <div class="form-group mb-4">
                                                        <textarea name="clausulas_parte_uno" id="clausulas_parte_uno" class="form-control" rows="18"></textarea>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="col-sm-12" id="clausulas_div">
                                                    <label for="clausulas_parte_dos">Clausulas parte Dos</label>
                                                    <div class="form-group mb-4">
                                                        <textarea name="clausulas_parte_dos" id="clausulas_parte_dos" class="form-control" rows="18"></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <input type="hidden" name="personal_id" value="{{ $persona->id }}">
                                            <input type="hidden" name="contrato_id" id="contrato_id" >

                                        </div>

                                            <button type="submit" class="btn btn-primary btn-lg text-center"  id="btn_agg_contrato">Agregar Contrato</button>

                                    </form>
                                </div>

                                <div class="card p-5 collapse" id="agg_contrato_otro_si" style="border: solid 1px #cda854;background: #e9ecef !important;">
                                    <button type="button" class="close position-absolute" style="width: fit-content;right: 1%;top: 1%;" aria-label="Close" onclick="ocultar_collapse('#agg_contrato_otro_si')">
                                    </button>
                                    <form action="" id="form_agg_otro_si" method="POST">
                                        @csrf

                                        <div class="container p-3">

                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label for="fecha">Fecha</label>
                                                    <div class="form-group form-group-custom mb-4">
                                                        <input type="text" class="form-control" autocomplete="off" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-autoclose="true" name="fecha"  id="fecha" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr>

                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <label for="descripcion">Clausulas parte uno</label>
                                                    <div class="form-group mb-4">
                                                        <textarea name="descripcion" id="descripcion" class="form-control" rows="15" required></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <input type="hidden" name="contratos_personal_id" id="contratos_personal_id">

                                        </div>

                                        <div class="mt-3 text-center">
                                            <button class="btn btn-primary btn-lg waves-effect waves-light" type="submit">Enviar</button>
                                        </div>

                                    </form>
                                </div>

                                <table class="table">
                                    <thead class="">
                                        <tr>
                                            <th class="text-center"><b>No</b></th>
                                            <th class="text-center"><b>Tipo Contrato</b></th>
                                            <th class="text-center"><b>Estado</b></th>
                                            <th class="text-center"><b>Fecha Inicio</b></th>
                                            <th class="text-center"><b>Fecha Final</b></th>
                                            <th class="text-center"><b>Acciones</b></th>
                                            <th class="text-center"><b>Otro si</b></th>
                                        </tr>
                                    </thead>
                                    <tbody id="content_table_contratos">
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- TAB HOJA DE VIDA --}}
                    <div class="card mb-1">
                        <a data-toggle="collapse" onclick="cargar_documentos('HOJA DE VIDA', 'content_table_hoja_vida', {{ $persona->id }}, 0)" data-parent="#accordion" href="#collapseHojaVida" aria-expanded="false" aria-controls="collapseHojaVida" class="text-dark collapsed">
                            <div class="card-header bg-primary" id="headingOne">
                                <h6 class="ml-4 fs-1 text-white">HOJA DE VIDA</h6>
                                <i class="fa fa-chevron-down text-white position-absolute" style="right: 3%" aria-hidden="true"></i>
                            </div>
                        </a>

                        <div id="collapseHojaVida" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">

                                <button class="btn btn-primary waves-effect waves-light mb-2 float-right" onclick="collapse_agg_documento('HOJA DE VIDA', 'content_table_hoja_vida',0)" data-toggle="collapse" data-target="#agg_documento"><i class="fa fa-plus"></i></button>

                                <table class="table">
                                    <thead class="thead-inverse">
                                        <tr>
                                            <th class="text-center"><b>No</b></th>
                                            <th class="text-center"><b>Expedicion</b></th>
                                            <th class="text-center"><b>Observación</b></th>
                                            <th class="text-center"><b>Estado</b></th>
                                            <th class="text-center"><b>Acciones</b></th>
                                        </tr>
                                    </thead>
                                    <tbody id="content_table_hoja_vida">
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- TAB CÉDULA DE CIUDADANÍA --}}
                    <div class="card mb-1">
                        <a data-toggle="collapse" onclick="cargar_documentos('CÉDULA DE CIUDADANÍA', 'content_table_cedula', {{ $persona->id }}, 0)" data-parent="#accordion" href="#collapseCedula" aria-expanded="false" aria-controls="collapseCedula" class="text-dark collapsed">
                            <div class="card-header bg-primary" id="headingOne">
                                <h6 class="ml-4 fs-1 text-white">CÉDULA DE CIUDADANÍA</h6>
                                <i class="fa fa-chevron-down text-white position-absolute" style="right: 3%" aria-hidden="true"></i>
                            </div>
                        </a>

                        <div id="collapseCedula" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">

                                <button class="btn btn-primary waves-effect waves-light mb-2 float-right" onclick="collapse_agg_documento('CÉDULA DE CIUDADANÍA', 'content_table_cedula',0)" data-toggle="collapse" data-target="#agg_documento"><i class="fa fa-plus"></i></button>

                                <table class="table">
                                    <thead class="thead-inverse">
                                        <tr>
                                            <th class="text-center"><b>No</b></th>
                                            <th class="text-center"><b>Expedicion</b></th>
                                            <th class="text-center"><b>Observación</b></th>
                                            <th class="text-center"><b>Estado</b></th>
                                            <th class="text-center"><b>Acciones</b></th>
                                        </tr>
                                    </thead>
                                    <tbody id="content_table_cedula">
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- TAB RUNT --}}
                    <div class="card mb-1">
                        <a data-toggle="collapse" onclick="cargar_documentos('RUNT', 'content_table_runt', {{ $persona->id }}, 1)" data-parent="#accordion" href="#collapseRunt" aria-expanded="false" aria-controls="collapseRunt" class="text-dark collapsed">
                            <div class="card-header bg-primary" id="headingOne">
                                <h6 class="ml-4 fs-1 text-white">RUNT</h6>
                                <i class="fa fa-chevron-down text-white position-absolute" style="right: 3%" aria-hidden="true"></i>
                            </div>
                        </a>

                        <div id="collapseRunt" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">

                                <button class="btn btn-primary waves-effect waves-light mb-2 float-right" onclick="collapse_agg_documento('RUNT', 'content_table_runt',1)" data-toggle="collapse" data-target="#agg_documento"><i class="fa fa-plus"></i></button>

                                <table class="table">
                                    <thead class="thead-inverse">
                                        <tr>
                                            <th class="text-center"><b>No</b></th>
                                            <th class="text-center"><b>Expedicion</b></th>
                                            <th class="text-center"><b>Inicio Vigencia</b></th>
                                            <th class="text-center"><b>Fin Vigencia</b></th>
                                            <th class="text-center"><b>Dias</b></th>
                                            <th class="text-center"><b>Observación</b></th>
                                            <th class="text-center"><b>Estado</b></th>
                                            <th class="text-center"><b>Acciones</b></th>
                                        </tr>
                                    </thead>
                                    <tbody id="content_table_runt">
                                        <tr>
                                            <td colspan="8" class="text-center">
                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- TAB TARJETA PROFESIONAL --}}
                    <div class="card mb-1">
                        <a data-toggle="collapse" onclick="cargar_documentos('TARJETA PROFESIONAL', 'content_table_tarjeta_profesional', {{ $persona->id }}, 0)" data-parent="#accordion" href="#collapsetarjetaprofesional" aria-expanded="false" aria-controls="collapseRunt" class="text-dark collapsed">
                            <div class="card-header bg-primary" id="headingOne">
                                <h6 class="ml-4 fs-1 text-white">TARJETA PROFESIONAL</h6>
                                <i class="fa fa-chevron-down text-white position-absolute" style="right: 3%" aria-hidden="true"></i>
                            </div>
                        </a>

                        <div id="collapsetarjetaprofesional" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">

                                <button class="btn btn-primary waves-effect waves-light mb-2 float-right" onclick="collapse_agg_documento('TARJETA PROFESIONAL', 'content_table_tarjeta_profesional',0)" data-toggle="collapse" data-target="#agg_documento"><i class="fa fa-plus"></i></button>

                                <table class="table">
                                    <thead class="thead-inverse">
                                        <tr>
                                            <th class="text-center"><b>No</b></th>
                                            <th class="text-center"><b>Expedicion</b></th>
                                            <th class="text-center"><b>Observación</b></th>
                                            <th class="text-center"><b>Estado</b></th>
                                            <th class="text-center"><b>Acciones</b></th>
                                        </tr>
                                    </thead>
                                    <tbody id="content_table_tarjeta_profesional">
                                        <tr>
                                            <td colspan="8" class="text-center">
                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
