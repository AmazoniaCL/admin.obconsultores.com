@extends('layouts.app')

@section('title_content') Documentos Legales @endsection

@section('myScripts') 
    <script src="{{ asset('assets/js/administracion.js') }}"></script> 
@endsection

@section('content')

<div class="section-body">

    <div class="col-lg-12">

        <a href="/"><button type="button" class="btn btn-primary mb-2"><i class="fa fa-arrow-circle-left mr-2"></i> Atras </button></a>

        <div class="card p-5 collapse" id="agg_documentacion" style="border: solid 1px #cda854;background: #e9ecef !important;">
            <button type="button" class="close position-absolute" style="width: fit-content;right: 1%;top: 1%;" aria-label="Close" onclick="ocultar_collapse('#agg_documentacion')">
            </button>
            <form action="{{route('agg_documentacion')}}" id="form_agg_documentacion" method="post" enctype="multipart/form-data">
                @csrf

                    <div class="row">
                        <div class="form-group col-6">
                            <label class="form-label">Nombre Del Documento</label>
                            <input name="nombre" id="nombre" class="form-control" placeholder="Nombre" required type="text">
                        </div>
                        
                        <div class="form-group col-6">
                            <label class="form-label">Archivo </label>
                            <input type="file" class="form-control" accept="application/pdf, .doc, .docx" name="file" id="file" required>
                        </div>
                    </div>

                    <input type="hidden" name="id" id="id_documento">

                    <button type="submit" class="btn btn-primary btn-lg text-center" id="btn_agg_documentacion">Agregar Documento</button>

            </form>
        </div>

        <div class="card p-5 collapse" id="exportar_documentos" style="border: solid 1px #cda854;background: #e9ecef !important;">
            <button type="button" class="close position-absolute" style="width: fit-content;right: 1%;top: 1%;" aria-label="Close" onclick="ocultar_collapse('#exportar_documentos')">
            </button>
            <form action="" id="form_exportar_documentos" method="POST">
                @csrf

                <div class="container p-3">

                    <div class="col-sm-12">
                        <div class="mt-4 mt-sm-0">
                            <h5 class="font-size-14 mb-3">Seleccionar documentos a exportar</h5>

                            <div id="content_exportar_documentos"></div>

                        </div>
                    </div>

                </div>

                <div class="mt-3 text-center">
                    <button class="btn btn-primary btn-lg waves-effect waves-light" id="btn_submit_exportar_documentos" type="submit">Enviar</button>
                </div>

            </form>
        </div>

        <div class="card bg-none p-3">
            <div class="card-header">
                <h3 class="card-title">Documentacion</h3>
                <div class="card-options">
                    <button type="button" class="btn btn-primary mr-2" onclick="exportar_documentos()"> Exportar Documentos </button>
                    <button type="button" class="btn btn-primary" data-toggle="collapse" onclick="agg_documento()" data-target="#agg_documentacion" aria-expanded="false"> Agregar Documento + </button>
                </div>
            </div>
            <div class="card-body pt-0">

                <div class="alert alert-icon alert-success col-12 d-none" id="delete_confirmed" role="alert">
                    <i class="fe fe-check mr-2" aria-hidden="true"></i> Documento eliminado correctamente
                </div>

                @if (session()->has('create') && session('create') == 1)
                <div class="alert alert-icon alert-success col-12" id="alert" role="alert">
                    <i class="fe fe-check mr-2" aria-hidden="true"></i> Documento Creado
                </div>
                @endif

                @if (session()->has('edit') && session('edit') == 1)
                <div class="alert alert-icon alert-success col-12" id="alert" role="alert">
                    <i class="fe fe-check mr-2" aria-hidden="true"></i> Documento Editado
                </div>
                @endif

                <div class="table-responsive table_e2">
                    <table class="table table-hover table-vcenter table_custom spacing5 text-nowrap mb-3">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre de Archivo</th>
                                <th>Tipo de Archivo</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $a=0;
                            @endphp
                            @foreach ($documentacion as $doc)
                                @php
                                    $a++;
                                @endphp
                                <tr>
                                    <td>
                                        <span>{{$a}}</span>
                                    </td>
                                    <td>
                                        <span>{{$doc->nombre}}</span>
                                    </td>
                                    <td>
                                        <span>{{strtoupper(pathinfo($doc->file, PATHINFO_EXTENSION))}}</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="/storage/{{$doc->file}}" target="_blank"><button type="button" class="btn btn-primary btn-sm" title="Ver"><i class="fa fa-eye"></i></button></a>
                                        <button type="button" class="btn btn-info text-white btn-sm" onclick="edit_documento({{$doc->id}}, '{{$doc->nombre}}')" title="Editar"><i class="fa fa-pencil-square-o"></i></button>
                                        <button type="button" class="btn text-white bg-red btn-sm" onclick="delete_documento({{$doc->id}}, '{{$doc->nombre}}')" title="Delete"><i class="fa fa-trash-o"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                            
                        </tbody>
                    </table>
                    {{ $documentacion->links() }}
                </div>
            </div>
        </div>
    </div>

</div>

@endsection



