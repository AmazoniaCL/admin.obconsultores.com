@extends('layouts.emails')

@section('title_content') Email @endsection

@section('myScripts')
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
                            <a href="javascript:;" onclick="contenido_media({{ $email->id }})" class="media">
                                <div class="media-body">
                                    <div class="d-flex justify-content-between mb-1"><small>{{$email->estado}}</small> <small>{{$email->created_at->isoFormat('MMMM Do YYYY, h:mm a')}}</small></div>
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
                                    <span class="message">{!!Str::limit($mensajes->mensaje,50)!!}</span>
                                    @endforeach
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
            </div>
        </div>
    </div>
@endsection
