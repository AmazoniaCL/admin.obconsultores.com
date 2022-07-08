@extends('layouts.app')

@section('title_content') Dashboard @endsection

@section('myScripts') <script src="{{ asset('assets/js/sincronizar.js') }}"></script> @endsection

@section('content')

<div class="section-body">
    <div class="container-fluid">
        <h4>Bienvenido {{ \Auth::user()->name }}</h4>
    </div>
</div>

<div class="section-body mt-5">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-12">
                <div class="card">
                    <div class="card-body ribbon">
                        <div class="alert alert-success" id="alert-empty" style="display: none" role="alert">
                            <strong>No se encontraron estados nuevos</strong>
                        </div>

                        <button type="button" class="btn btn-primary" id="btn-sincronizar" onclick="sincronizar()">
                            <span>SINCRONIZAR</span>
                        </button>
                    </div>
                </div>

                <div class="card bg-none p-3">
                    <div class="card-header">
                        <h3 class="card-title">Historial de de Sincronizaciones</h3>
                    </div>
                    <div class="card-body pt-0">
                        <div class="table-responsive table_e2">
                            <table class="table table-hover table-vcenter table_custom spacing5 text-nowrap mb-3">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Usuario</th>
                                        <th>Cantidad Procesos</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $row)
                                        <tr>
                                            <td>
                                                <span>{{ $row['fecha'] }}</span>
                                            </td>
                                            <td>
                                                <span class="c_name ml-0"><span>{{ $row['users']['name'] }}</span></span>
                                            </td>
                                            <td>
                                                <span>{{ count($row['procesos']) }}</span>
                                            </td>
                                            <td class="text-center">
                                                <a href="/administrador/sincronizacion/{{ $row['id'] }}" target="_blank"><button type="button" class="btn btn-primary btn-sm" title="Ver"><i class="fa fa-eye"></i></button></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {{ $data->links() }}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


