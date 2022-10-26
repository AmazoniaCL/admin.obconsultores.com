@extends('layouts.emails')

@section('title_content') Consultas @endsection

@section('myStyles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/summernote/dist/summernote.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-multiselect/bootstrap-multiselect.css') }}"/>
@endsection

@section('myScripts')
    <script src="{{ asset('assets/plugins/fullcalendar/moment.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/summernote.bundle.js') }}"></script>
    <script src="{{ asset('assets/plugins/summernote/dist/lang/summernote-es-ES.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-multiselect/bootstrap-multiselect.js') }}"></script>
    <script src="{{ asset('assets/js/emails.js') }}"></script>
@endsection

@section('content')
    <div class="page">
        <div class="inbox_body">
            <div class="inbox_list" id="users">
                <div class="">
                    <h4 class="text-center">{{$cliente->nombre}}</h4>
                </div>
                <div class="input-icon mt-1 mb-2">
                    <input type="text" class="form-control search" placeholder="Buscar...">
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
                            <a href="javascript:;" onclick="contenido_media({{ $email->id }}, this)" class="media">
                                <div class="media-body">
                                    <div class="d-flex justify-content-between mb-1"><small>{{$email->estado}}</small> <small>{{$email->created_at->isoFormat('MMMM Do YYYY, h:mm a')}}</small></div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="name text_ellipsis">{{$email->asunto}}</span>
                                    </div>

                                    @if ($email->procesos)
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="message">{{ $email->procesos->tipo }} {{ $email->procesos->num_proceso }} ({{ $email->procesos->ciudad }}, {{ $email->procesos->departamento }}) - {{ $email->procesos->radicado ?? 'Sin radicado' }}</span>
                                        </div>
                                    @endif

                                    @isset ($email->mensajes[0])
                                        <span class="message">{!! Str::limit($email->mensajes[0]->mensaje, 50) !!}</span>
                                    @else
                                        <span class="message">ERROR LEYENDO MENSAJES</span>
                                    @endisset
                                    {{-- @foreach($email->mensajes as $mensajes)
                                        <span class="message">{!!Str::limit($mensajes->mensaje,50)!!}</span>
                                    @endforeach --}}
                                </div>
                            </a>
                        </li>
                        @endforeach
                    @endif
                </ul>
            </div>
            <div class="inbox_content">
                @if(count($emails)==0)
                <div class="card inbox">
                    <div class="card-body detail">
                        <div class="detail-header">
                            <div class="media">
                                <div class="media-body">
                                    <p class="mb-0 text-center">No tiene consultas pendientes por revisar.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="card inbox" id="contenido_email">

                </div>
                @endif
                <div class="card">
                    <div class="card-body mail_compose" id="formulario_respuesta" style="display:none;">
                        <form action="/clientes/cosultas/store_mensaje" id="form_enviar_Consulta" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="card-header">
                                <h3 class="card-title">Responder</h3>
                            </div>

                            <div class="card-body">
                                @if (session()->has('mostrar_alerta') && session('mostrar_alerta') == 1)
                                    <div class="alert alert-{{ session('tipo') }}" role="alert">
                                        <strong>{{ session('mensaje') }}</strong>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label for="correos_notificar" class="form-label">Notificar a: </label>
                                    <select id="correos_notificar" name="correos_notificar[]" multiple="multiple" class="w-100" style="width: 100% !important" required>
                                        <option value="{{ $cliente->correo }}">{{ $cliente->correo }}</option>
                                        @foreach ($emailsList as $row)
                                            <option value="{{ $row->email }}">{{ $row->email }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <label class="form-label">Mensaje</label>

                                <input type="hidden" name="mensaje" id="mensaje" required/>

                                <div class="summernote"></div>

                                <div class="col-md-12 mt-2">
                                    <div class="form-group">
                                        <label class="form-label">Adjunto</label>
                                        <input type="file" class="form-control" accept="application/pdf,image/png,image/jpg,image/jpeg" name="adjunto_correo[]" id="adjunto_correo" multiple />
                                    </div>
                                </div>

                                <input type="hidden" name="mensaje_id" id="mensaje_id" value="">
                                <input type="hidden" name="cliente_id" id="cliente_id" value="{{ $cliente->id }}">

                                <div class="row justify-content-center">
                                    <button type="submit" class="btn btn-success btn-lg mt-3" id="btn_enviar_mensaje">Responder</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
