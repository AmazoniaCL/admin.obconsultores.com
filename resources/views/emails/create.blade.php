@extends('layouts.app')

@section('title_content') Email @endsection

@section('myScripts')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="inbox_body">
            <div class="inbox_list" id="users">
                <ul class="right_chat list-unstyled list">
                    <li class="offline">
                        <a href="javascript:void(0);" class="media">
                            <img class="media-object" src="../assets/images/xs/avatar1.jpg" alt="">
                            <div class="media-body">
                                <div class="d-flex justify-content-between mb-1"><small>Phillip Smith</small> <small>5:15PM</small></div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="name text_ellipsis">Need Support</span>
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
                                <span class="message">it is a long established fact that a reader will be distracted by the readable content</span>
                                <span class="badge badge-outline status"></span>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="inbox_content">
                <div class="card">
                    <div class="card-body mail_compose">
                        <form class="mt-4" method="post" action="" enctype="multipart/form-data">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="To">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Subject">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="CC">
                            </div>
                        </form>
                        <div class="summernote">
                            Hello there,
                            <br/>
                            <p>The toolbar can be customized and it also supports various callbacks such as <code>oninit</code>, <code>onfocus</code>, <code>onpaste</code> and many more.</p>
                            <p>Please try <b>paste some texts</b> here</p>
                        </div>
                        <div class="mt-4 text-right">
                            <button type="button" class="btn btn-success">Send Message</button>
                            <button type="button" class="btn btn-outline-secondary">Draft</button>
                            <a href="app-email.html" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
