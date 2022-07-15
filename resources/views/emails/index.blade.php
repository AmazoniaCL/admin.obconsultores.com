@extends('layouts.emails')

@section('title_content') Email @endsection

@section('myScripts')
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
                <div class="card inbox">
                    <div class="d-flex justify-content-between action_bar">
                        <div>
                            <button type="button" class="btn btn-icon btn-primary"><i class="fe fe-rotate-cw"></i></button>
                        </div>
                        <div>
                            <a href="#" class="btn btn-outline-primary"><i class="fe fe-arrow-left"></i></a>
                            <a href="#" class="btn btn-outline-primary"><i class="fe fe-arrow-right"></i></a>
                            <button type="button" class="btn btn-outline-primary"><i class="fe fe-settings mr-2"></i>Setting</button>
                        </div>
                    </div>
                    <div class="card-body detail">
                        <div class="detail-header">
                            <div class="media">
                                <div class="media-body">
                                    <p class="mb-0"><strong class="text-muted mr-1">From:</strong><a href="javascript:void(0);">info@example.com</a><span class="text-muted text-sm float-right">12:48, 23.06.2018</span></p>
                                    <p class="mb-0"><strong class="text-muted mr-1">To:</strong>Me <small class="float-right"><i class="fe fe-paperclip mr-1"></i>(2 files, 89.2 KB)</small></p>                                        
                                </div>
                            </div>
                        </div>
                        <div class="mail-cnt">
                            <p>Hello <strong>Marshall Nichols</strong>,</p><br>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
                            <ul>
                                <li>standard dummy text ever since the 1500s, when an unknown printer</li>
                                <li>Lorem Ipsum has been the industry's standard dummy</li>
                            </ul>
                            <p>printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrnturies, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
                            <br>
                            <div class="file_folder">
                                <a href="javascript:void(0);">
                                    <div class="icon">
                                        <i class="fa fa-file-excel-o text-success"></i>
                                    </div>
                                    <div class="file-name">
                                        <p class="mb-0 text-muted">Report2017.xls</p>
                                        <small>Size: 68KB</small>
                                    </div>
                                </a>
                                <a href="javascript:void(0);">
                                    <div class="icon">
                                        <i class="fa fa-file-word-o text-primary"></i>
                                    </div>
                                    <div class="file-name">
                                        <p class="mb-0 text-muted">Report2017.doc</p>
                                        <small>Size: 68KB</small>
                                    </div>
                                </a>
                                <a href="javascript:void(0);">
                                    <div class="icon">
                                        <i class="fa fa-file-pdf-o text-danger"></i>
                                    </div>
                                    <div class="file-name">
                                        <p class="mb-0 text-muted">Report2017.pdf</p>
                                        <small>Size: 68KB</small>
                                    </div>
                                </a>
                            </div>

                            <p>Thank youm,<br><strong>Wendy Abbott</strong></p>
                            <hr>
                            <a class="btn btn-default" href="app-compose.html">Reply</a>
                            <a class="btn btn-default" href="app-compose.html">Forward</a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection
