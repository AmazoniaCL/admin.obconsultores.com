@extends('layouts.app')

@section('title_content') Ver Sincronizacion @endsection

@section('content')

<div class="section-body">

    <div class="col-lg-12">

        {{-- {{ dd($data['procesos']) }} --}}


        <a href="/administrador/sincronizacion"><button type="button" class="btn btn-primary mb-2"><i class="fa fa-arrow-circle-left mr-2"></i> Atras </button></a>

        <div class="card bg-none p-3">
            <div class="card-header">
                <h3 class="card-title">Detalle de Sincronización</h3>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive table_e2">
                    <table class="table table-hover table-vcenter table_custom spacing5 text-nowrap mb-3">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Radicado</th>
                                <th>Cantidad</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['procesos'] as $proceso)
                                <tr>
                                    <td>
                                        <span>{{ $data['fecha'] }}</span>
                                    </td>
                                    <td>
                                        <span class="c_name ml-0"><span>{{ $proceso['procesos']['radicado'] }}</span></span>
                                    </td>
                                    <td>
                                        <span>{{ $proceso['cantidad'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="/procesos/ver/{{ $proceso['procesos']['id'] }}" target="_blank"><button type="button" class="btn btn-primary btn-sm" title="Ver"><i class="fa fa-eye"></i></button></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- {{ $clientes->links() }} --}}
                </div>
            </div>
        </div>
    </div>

</div>

@endsection



