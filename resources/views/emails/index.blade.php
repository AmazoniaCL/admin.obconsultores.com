@extends('layouts.app')

@section('title_content') Email @endsection

@section('myScripts')
@endsection

@section('content')
    <div class="page">
        <div class="inbox_body">
            <div class="inbox_list" id="users">
                <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                        <input type="radio" name="intensity" value="low" class="selectgroup-input" checked="">
                        <span class="selectgroup-button">Primary</span>
                    </label>
                    <label class="selectgroup-item">
                        <input type="radio" name="intensity" value="medium" class="selectgroup-input">
                        <span class="selectgroup-button">Social</span>
                    </label>
                    <label class="selectgroup-item">
                        <input type="radio" name="intensity" value="high" class="selectgroup-input">
                        <span class="selectgroup-button">Updates</span>
                    </label>
                </div>
                <div class="input-icon mt-1 mb-2">
                    <input type="text" class="form-control search" placeholder="Search for...">
                    <span class="input-icon-addon">
                        <i class="fe fe-search"></i>
                    </span>
                </div>
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
                    <li class="online active">
                        <a href="javascript:void(0);" class="media">
                            <img class="media-object" src="../assets/images/xs/avatar2.jpg" alt="">
                            <div class="media-body">
                                <div class="d-flex justify-content-between mb-1"><small>Merri Diamond</small> <small>5:18PM</small></div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="name text_ellipsis">Apply for web Developer</span>
                                    <div class="d-flex align-items-center">
                                        <div class="mr-2 ml-2">
                                            <i class="icon-paper-clip mr-2 ml-2"></i>
                                            <i class="fa fa-star"></i>
                                        </div>
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input">
                                            <span class="custom-control-label"></span>
                                        </label>
                                    </div>
                                </div>
                                <span class="message">The point of using Lorem Ipsum is that it has a more-or-less normal distribution</span>
                                <span class="badge badge-outline status"></span>
                            </div>
                        </a>
                    </li>
                    <li class="offline">
                        <a href="javascript:void(0);" class="media">
                            <img class="media-object" src="../assets/images/xs/avatar3.jpg" alt="">
                            <div class="media-body">
                                <div class="d-flex justify-content-between mb-1"><small>Allen Collins</small> <small>5:18PM</small></div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="name text_ellipsis">Balance Withdrawal Failed</span>
                                    <div class="d-flex align-items-center">
                                        <div class="mr-2 ml-2">
                                            <i class="icon-paper-clip mr-2 ml-2"></i>
                                            <i class="fa fa-star text-muted"></i>
                                        </div>
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input">
                                            <span class="custom-control-label"></span>
                                        </label>
                                    </div>
                                </div>
                                <span class="message">There are many variations of passages of Lorem Ipsum available, but the majority</span>
                                <span class="badge badge-outline status"></span>
                            </div>
                        </a>
                    </li>
                    <li class="offline">
                        <a href="javascript:void(0);" class="media">
                            <img class="media-object" src="../assets/images/xs/avatar2.jpg" alt="">
                            <div class="media-body">
                                <div class="d-flex justify-content-between mb-1"><small>Andrew Patrick</small> <small>5:18PM</small></div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="name text_ellipsis">New Project</span>
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
                                <span class="message">Contrary to popular belief, Lorem Ipsum is not simply random text</span>
                                <span class="badge badge-outline status"></span>
                            </div>
                        </a>
                    </li>
                    <li class="offline">
                        <a href="javascript:void(0);" class="media">
                            <img class="media-object" src="../assets/images/xs/avatar4.jpg" alt="">
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
                    <li class="online">
                        <a href="javascript:void(0);" class="media">
                            <img class="media-object" src="../assets/images/xs/avatar5.jpg" alt="">
                            <div class="media-body">
                                <div class="d-flex justify-content-between mb-1"><small>Merri Diamond</small> <small>5:18PM</small></div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="name text_ellipsis">Apply for web Developer</span>
                                    <div class="d-flex align-items-center">
                                        <div class="mr-2 ml-2">
                                            <i class="icon-paper-clip mr-2 ml-2"></i>
                                            <i class="fa fa-star"></i>
                                        </div>
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input">
                                            <span class="custom-control-label"></span>
                                        </label>
                                    </div>
                                </div>
                                <span class="message">The point of using Lorem Ipsum is that it has a more-or-less normal distribution</span>
                                <span class="badge badge-outline status"></span>
                            </div>
                        </a>
                    </li>
                    <li class="offline">
                        <a href="javascript:void(0);" class="media">
                            <img class="media-object" src="../assets/images/xs/avatar3.jpg" alt="">
                            <div class="media-body">
                                <div class="d-flex justify-content-between mb-1"><small>Allen Collins</small> <small>5:18PM</small></div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="name text_ellipsis">Balance Withdrawal Failed</span>
                                    <div class="d-flex align-items-center">
                                        <div class="mr-2 ml-2">
                                            <i class="icon-paper-clip mr-2 ml-2"></i>
                                            <i class="fa fa-star text-muted"></i>
                                        </div>
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input">
                                            <span class="custom-control-label"></span>
                                        </label>
                                    </div>
                                </div>
                                <span class="message">There are many variations of passages of Lorem Ipsum available, but the majority</span>
                                <span class="badge badge-outline status"></span>
                            </div>
                        </a>
                    </li>
                    <li class="offline">
                        <a href="javascript:void(0);" class="media">
                            <img class="media-object" src="../assets/images/xs/avatar2.jpg" alt="">
                            <div class="media-body">
                                <div class="d-flex justify-content-between mb-1"><small>Andrew Patrick</small> <small>5:18PM</small></div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="name text_ellipsis">New Project</span>
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
                                <span class="message">Contrary to popular belief, Lorem Ipsum is not simply random text</span>
                                <span class="badge badge-outline status"></span>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="inbox_content">
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
                                <div class="float-left">
                                    <div class="mr-3"><img src="../assets/images/xs/avatar5.jpg" alt=""></div>
                                </div>
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
            </div>
        </div>
    </div>
@endsection
