@extends('layouts.app')

@section('title_content') Personal @endsection

@section('myStyles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}"/>
@endsection

@section('myScripts')
    <script src="{{ asset('assets/js/administracion.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
@endsection

@section('content')

<div class="section-body">

    <div class="col-lg-12">

        <a href="/"><button type="button" class="btn btn-primary mb-2"><i class="fa fa-arrow-circle-left mr-2"></i> Atras </button></a>

        <div class="card p-5 collapse" id="agg_personal" style="border: solid 1px #cda854;background: #e9ecef !important;">
            <button type="button" class="close position-absolute" style="width: fit-content;right: 1%;top: 1%;" aria-label="Close" onclick="ocultar_collapse('#agg_personal')">
            </button>
            <form action="{{route('crear_personal')}}" id="form_agg_personal" method="post">
                @csrf

                    <div class="row">
                        <div class="form-group col-3">
                            <label class="form-label">Tipo de Identificacion</label>
                            <select name="tipo_identificacion" id="tipo_identificacion" class="form-control" required>
                                <option value="">Seleccione T.I</option>
                                <option value="Cedula de ciudadania">Cedula de ciudadania</option>
                                <option value="Cedula de Extranjeria">Cedula de Extranjeria</option>
                                <option value="Nit">Nit</option>
                                <option value="Registro Civil">Registro Civil</option>
                            </select>
                        </div>

                        <div class="form-group col-3">
                            <label class="form-label">Numero de Identificacion</label>
                            <input name="identificacion" autocomplete="off" id="identificacion" class="form-control" placeholder="Nº Identificación" required type="number">
                        </div>

                        <div class="form-group col-3">
                            <label class="form-label">Nombres</label>
                            <input name="nombres" id="nombres" class="form-control" placeholder="Nombres" required type="text">
                        </div>

                        <div class="form-group col-3">
                            <label class="form-label">Primer Apellido</label>
                            <input name="primer_apellido" id="primer_apellido" class="form-control" placeholder="Primer Apellido" required type="text">
                        </div>
                    </div>

                    <div class="row mt-3">

                        <div class="form-group col-3">
                            <label class="form-label">Segundo Apellido</label>
                            <input name="segundo_apellido" id="segundo_apellido" class="form-control" placeholder="Segundo Apellido" type="text">
                        </div>

                        <div class="form-group col-3">
                            <label class="form-label">Fecha Ingreso</label>
                            <input data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-autoclose="true" name="fecha_ingreso" id="fecha" class="form-control" placeholder="MM/DD/AAAA" required="" autocomplete="off">
                        </div>

                        <div class="form-group col-3">
                            <label class="form-label">Direccion</label>
                            <input name="direccion" id="direccion" class="form-control" placeholder="Direccion" required type="text">
                        </div>

                        <div class="form-group col-3">
                            <label class="form-label">Sexo</label>
                            <select name="sexo" id="sexo" class="form-control" required>
                                <option value="">Seleccione Sexo</option>
                                <option value="Hombre">Hombre</option>
                                <option value="Mujer">Mujer</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">

                        <div class="form-group col-3">
                            <label class="form-label">Estado</label>
                            <select name="estado" id="estado" class="form-control" required>
                                <option value="">Seleccione Estado</option>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>

                        <div class="form-group col-1">
                            <label class="form-label">RH</label>
                            <input type="text"t name="rh" id="rh" class="form-control" placeholder="RH" required >
                        </div>

                        <div class="form-group col-4">
                            <label class="form-label">Tipo Vinculacion</label>
                            <select name="tipo_vinculacion" id="tipo_vinculacion" class="form-control" required>
                                <option value="">Seleccione T.V</option>
                                <option value="ObConsultores">ObConsultores</option>
                                <option value="Externo">Externo</option>
                            </select>
                        </div>

                        <div class="form-group col-4">
                            <label class="form-label">Correo</label>
                            <input type="email"t name="correo" id="correo" class="form-control" placeholder="Correo" required >
                        </div>

                    </div>

                    <div class="row mt-3">
                        <div class="form-group col-6">
                            <label class="form-label">Teléfono</label>
                            <input type="text"t name="telefonos" id="telefonos" class="form-control" placeholder="Telefono" required >
                        </div>
                        <div class="form-group col-6">
                            <label class="form-label">Tarjeta Profesional</label>
                            <input type="text"t name="tarjetaprofesional" id="tarjetaprofesional" class="form-control" placeholder="Tarjeta Profesional" >
                        </div>
                    </div>

                    <input type="hidden"t name="id" id="id" >

                    <button type="submit" class="btn btn-primary btn-lg text-center mt-3" id="btn_agg_personal">Agregar Personal</button>

            </form>
        </div>

        <div class="card bg-none p-3">
            <div class="card-header">
                <h3 class="card-title">Personal</h3>
                <div class="card-options">
                    <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#agg_personal" onclick="agg_personal()" aria-expanded="false"> Agregar Personal + </button>
                </div>
            </div>
            <div class="card-body pt-0">

                <div class="alert alert-icon alert-success col-12 d-none" id="delete_confirmed" role="alert">
                    <i class="fe fe-check mr-2" aria-hidden="true"></i> Persona eliminado correctamente
                </div>

                @if (session()->has('create') && session('create') == 1)
                <div class="alert alert-icon alert-success col-12" id="alert" role="alert">
                    <i class="fe fe-check mr-2" aria-hidden="true"></i> Personal Creado
                </div>
                @endif

                @if (session()->has('edit') && session('edit') == 1)
                <div class="alert alert-icon alert-success col-12" id="alert" role="alert">
                    <i class="fe fe-check mr-2" aria-hidden="true"></i> Personal Editado
                </div>
                @endif

                <div class="table-responsive table_e2">
                    <table class="table table-hover table-vcenter table_custom spacing5 text-nowrap mb-3">
                        <thead>
                            <tr>
                                <th>Identificación</th>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Telefono</th>
                                <th>Fecha Ingreso</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $a=0;
                            @endphp
                            @foreach ($personal as $persona)
                                @php
                                    $a++;
                                @endphp
                                <tr>
                                    <td>
                                        <span>{{$persona->identificacion}}</span>
                                    </td>
                                    <td>
                                        <span>{{$persona->nombres}} {{$persona->primer_apellido}}</span>
                                    </td>
                                    <td>
                                        <span>{{$persona->correo}}</span>
                                    </td>
                                    <td>
                                        <span>{{$persona->telefonos}}</span>
                                    </td>
                                    <td>
                                        <span>{{$persona->fecha_ingreso}}</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{route('ver_personal', $persona->id)}}"><button type="button" class="btn btn-primary btn-sm" title="Ver"><i class="fa fa-eye"></i></button></a>
                                        <button type="button" class="btn btn-info text-white btn-sm" onclick="edit_personal({{ $persona}})" title="Editar"><i class="fa fa-pencil-square-o"></i></button>
                                        <button onclick="delete_personal({{$persona->id}}, '{{$persona->nombres . ' ' . $persona->primer_apellido}}')" type="button" class="btn text-white bg-red btn-sm" title="Eliminar"><i class="fa fa-trash-o"></i></button>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                    {{ $personal->links() }}
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
