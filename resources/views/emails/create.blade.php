@extends('layouts.emails')

@section('title_content') Email @endsection

@section('myStyles') <link rel="stylesheet" href="{{ asset('assets/plugins/summernote/dist/summernote.css') }}"/> @endsection

@section('myScripts')
    <script src="{{ asset('assets/bundles/summernote.bundle.js') }}"></script>
    <script src="{{ asset('assets/plugins/summernote/dist/lang/summernote-es-ES.js') }}"></script>
    <script src="{{ asset('assets/js/consultas.js') }}"></script>
    <script src="{{ asset('assets/js/cliente.js') }}"></script>
@endsection

@section('content')
    <div class="page">
        <div class="inbox_body">
            <div class="inbox_list" id="users">
                <div class="">
                    <h4 class="text-center">{{$cliente->nombre}}</h4>
                </div>
                <div class="input-icon mt-1 mb-2">
                    <input type="text" class="form-control search" placeholder="Search for...">
                    <span class="input-icon-addon">
                        <i class="fe fe-search"></i>
                    </span>
                </div>
                <ul class="right_chat list-unstyled list">
                    @if(count($emails)==0)
                        <li class="offline">
                            <a href="javascript:void(0);" class="media">
                                <div class="media-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <p class="name text_ellipsis">No tiene consultas pendientes por revisar.</p>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @else
                        @foreach($emails as $email)
                        <li class="offline">
                            <a href="javascript:void(0);" class="media">
                                <div class="media-body">
                                    <div class="d-flex justify-content-between mb-1"><small>Phillip Smith</small> <small>{{$email->created_at->isoFormat('MMMM Do YYYY, h:mm a')}}</small></div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="name text_ellipsis">{{$email->asunto}}</span>
                                        <div class="d-flex align-items-center">
                                            <div class="mr-2 ml-2">
                                                <i class="fa fa-star text-muted"></i>
                                            </div>
                                            <label class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input">
                                                <span class="custom-control-label"></span>
                                            </label>
                                        </div>
                                    </div>
                                    @foreach($email->mensajes as $mensajes)
                                    <span class="message">{{Str::limit($mensajes->mensaje,50)}}</span>
                                    @endforeach
                                </div>
                            </a>
                        </li>
                        @endforeach
                    @endif  
                </ul>
            </div>
            <div class="inbox_content">
                <div class="card">
                    <div class="card-body mail_compose">
                        <form action="/clientes/cosultas/store" id="form_enviar_Consulta" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="card-header">
                                <h3 class="card-title">Enviar Consulta a {{ $cliente->nombre }} </h3>
                            </div>
                            <div class="card-body">

                                <div class="form-group">
                                    <label for="asunto" class="form-label">Asunto</label>
                                    <textarea name="asunto" id="asunto" rows="3" placeholder="Escriba el asunto" class="form-control mb-2" required></textarea>
                                </div>

                                <label class="form-label">Mensaje</label>

                                <input type="hidden" name="mensaje" id="mensaje" required/>

                                <div class="summernote"></div>

                                <div class="col-md-12 mt-2">
                                    <div class="form-group">
                                        <label class="form-label">Adjunto</label>
                                        <input type="file" class="form-control" accept="application/pdf,image/png,image/jpg,image/jpeg" name="adjunto_correo" id="adjunto_correo" />
                                    </div>
                                </div>

                                <input type="hidden" name="cliente_id" id="cliente_id" value="{{ $cliente->id }}">

                                <div class="row justify-content-center">
                                    <button type="submit" class="btn btn-success btn-lg mt-3" id="btn_enviar_mensaje">Enviar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

