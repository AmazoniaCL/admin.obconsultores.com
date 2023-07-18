@extends('layouts.app')

@section('title_content') Acceso al proceso #{{ $proceso->num_proceso }} @endsection

@section('myScripts') <script src="{{ asset('assets/js/procesos.js') }}"></script> @endsection

@section('content')

<div class="section-body">

    <div class="col-lg-12">

        <a href="/procesos/ver/{{ $proceso->id }}"><button type="button" class="btn btn-primary mb-2"><i class="fa fa-arrow-circle-left mr-2"></i> Atras </button></a>

        @if (session()->has('create') && session()->has('create') == 1)
            <div class="alert alert-icon alert-success col-12" role="alert">
                <i class="fe fe-check mr-2" aria-hidden="true"></i> Se dio acceso correctamente.
            </div>
        @endif

        <div class="alert alert-icon alert-success col-12 d-none" id="delete_confirmed" role="alert">
            <i class="fe fe-check mr-2" aria-hidden="true"></i> Se eliminio el acceso correctamente
        </div>

        <div class="card">
            <div class="card-header">
                <h1 class="card-title">Usuarios que tienen acceso al proceso {{ $proceso->num_proceso }}</h1>
                <div class="card-options">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#agg_acceso" aria-expanded="false" aria-controls="agg_acceso"><i class="fe fe-plus mr-2"></i> Agregar </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">

                    <div class="card p-5 collapse" id="agg_acceso" style="border: solid 1px #cda854;background: #e9ecef !important;">

                        <form action="/procesos/agregar_acceso" method="post">
                            @csrf

                            <p><b>Seleccione el usuario</b></p>
                            <hr>

                            <div class="row">
                                <div class="form-group col-12">
                                    <label class="form-label">Usuarios</label>
                                    <select name="users_id" class="form-control" required>
                                        <option value="">Seleccione</option>
                                        @foreach (\App\User::all() as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <input type="hidden" name="procesos_id" value="{{ $proceso->id }}" />

                            <button type="submit" class="btn btn-primary btn-block">Enviar</button>

                        </form>

                    </div>

                    <table class="table table-bordered">
                        @foreach ($usuarios as $key => $row)
                            <tbody>
                                <tr>
                                    <td>
                                        Nombre <br>
                                        <b>{{ $row->name }}</b>
                                    </td>
                                    <td>
                                        Identifiacion <br>
                                        <b>{{ $row->identificacion ?? 'N/A' }}</b>
                                    </td>
                                    <td>
                                        Telefono <br>
                                        <b>{{ $row->telefono ?? 'N/A' }}</b>
                                    </td>
                                    <td>
                                        Correo <br>
                                        <b>{{ $row->email ?? 'N/A' }}</b>
                                    </td>
                                    <td rowspan="2" class="text-center">
                                        {{-- <button type="button" class="btn btn-danger btn-sm" onclick="eliminar_acceso({{ $row->id }})" title="Eliminar"><i class="fa fa-trash-o"></i></button> --}}
                                    </td>
                                </tr>
                            </tbody>
                        @endforeach
                        @foreach ($accesos as $key => $row)
                            <tbody>
                                <tr>
                                    <td>
                                        Nombre <br>
                                        <b>{{ $row->user->name }}</b>
                                    </td>
                                    <td>
                                        Identifiacion <br>
                                        <b>{{ $row->user->identificacion ?? 'N/A' }}</b>
                                    </td>
                                    <td>
                                        Telefono <br>
                                        <b>{{ $row->user->telefono ?? 'N/A' }}</b>
                                    </td>
                                    <td>
                                        Correo <br>
                                        <b>{{ $row->user->correo ?? 'N/A' }}</b>
                                    </td>
                                    <td rowspan="2" class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm" onclick="eliminar_acceso({{ $row->id }})" title="Eliminar"><i class="fa fa-trash-o"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        @endforeach
                    </table>

                </div>
            </div>
        </div>
    </div>

</div>

@endsection



