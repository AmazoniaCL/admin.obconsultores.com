<!doctype html>
<html lang="es">

{{-- Include Head --}}
@include('layouts.head')

<body class="font-opensans  email_page">

<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
    </div>
</div>

<!-- Start main html -->
<div id="main_content">

    <!-- Barra lateral -->
    @include('layouts.barra')

    <!-- Notification and  Activity-->
    <!-- <div id="rightsidebar" class="right_sidebar">
        <a href="javascript:void(0)" class="p-3 settingbar float-right"><i class="fa fa-close"></i></a>
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#notification" aria-expanded="true">Notification</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#activity" aria-expanded="false">Activity</a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane   active" id="notification" aria-expanded="true">
                <ul class="list-unstyled feeds_widget">
                    <li>
                        <div class="feeds-left"><i class="fa fa-check"></i></div>
                        <div class="feeds-body">
                            <h4 class="title text-danger">Issue Fixed</h4>
                            <small>WE have fix all Design bug with Responsive</small>
                            <small class="text-muted">11:05</small>
                        </div>
                    </li>
                    <li>
                        <div class="feeds-left"><i class="fa fa-user"></i></div>
                        <div class="feeds-body">
                            <h4 class="title">New User</h4>
                            <small>I feel great! Thanks team</small>
                            <small class="text-muted">10:45</small>
                        </div>
                    </li>
                    <li>
                        <div class="feeds-left"><i class="fa fa-thumbs-o-up"></i></div>
                        <div class="feeds-body">
                            <h4 class="title">7 New Feedback</h4>
                            <small>It will give a smart finishing to your site</small>
                            <small class="text-muted">Today</small>
                        </div>
                    </li>
                    <li>
                        <div class="feeds-left"><i class="fa fa-question-circle"></i></div>
                        <div class="feeds-body">
                            <h4 class="title text-warning">Server Warning</h4>
                            <small>Your connection is not private</small>
                            <small class="text-muted">10:50</small>
                        </div>
                    </li>
                    <li>
                        <div class="feeds-left"><i class="fa fa-shopping-cart"></i></div>
                        <div class="feeds-body">
                            <h4 class="title">7 New Orders</h4>
                            <small>You received a new oder from Tina.</small>
                            <small class="text-muted">11:35</small>
                        </div>
                    </li>
                </ul>
            </div>
            <div role="tabpanel" class="tab-pane  " id="activity" aria-expanded="false">
                <ul class="new_timeline mt-3">
                    <li>
                        <div class="bullet pink"></div>
                        <div class="time">11:00am</div>
                        <div class="desc">
                            <h3>Attendance</h3>
                            <h4>Computer Class</h4>
                        </div>
                    </li>
                    <li>
                        <div class="bullet pink"></div>
                        <div class="time">11:30am</div>
                        <div class="desc">
                            <h3>Added an interest</h3>
                            <h4>“Volunteer Activities”</h4>
                        </div>
                    </li>
                    <li>
                        <div class="bullet green"></div>
                        <div class="time">12:00pm</div>
                        <div class="desc">
                            <h3>Developer Team</h3>
                            <h4>Hangouts</h4>
                            <ul class="list-unstyled team-info margin-0 p-t-5">
                                <li><img src="../assets/images/xs/avatar1.jpg" alt="Avatar"></li>
                                <li><img src="../assets/images/xs/avatar2.jpg" alt="Avatar"></li>
                                <li><img src="../assets/images/xs/avatar3.jpg" alt="Avatar"></li>
                                <li><img src="../assets/images/xs/avatar4.jpg" alt="Avatar"></li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <div class="bullet green"></div>
                        <div class="time">2:00pm</div>
                        <div class="desc">
                            <h3>Responded to need</h3>
                            <a href="javascript:void(0)">“In-Kind Opportunity”</a>
                        </div>
                    </li>
                    <li>
                        <div class="bullet orange"></div>
                        <div class="time">1:30pm</div>
                        <div class="desc">
                            <h3>Lunch Break</h3>
                        </div>
                    </li>
                    <li>
                        <div class="bullet green"></div>
                        <div class="time">2:38pm</div>
                        <div class="desc">
                            <h3>Finish</h3>
                            <h4>Go to Home</h4>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div> -->

    <!-- Perfil de Usuario -->
    @include('layouts.perfil')

    <!-- start Main menu -->
    <div id="left-sidebar" class="sidebar">
        <div class="d-flex justify-content-between brand_name">
            <h5 class="brand-name">CONSULTAS</h5>
        </div>

        @if ($isViewProcesos)
            <a href="/procesos/cosultas/create/{{$proceso->id}}" class="btn btn-primary btn-block mt-4"><i class="fe fe-edit-3"></i> Nueva consulta</a>
            <a href="/procesos/ver/{{$proceso->id}}" class="btn btn-primary btn-block mb-2"><i class="fe fe-back"></i> Volver</a>
        @else
            <a href="/clientes/cosultas/create/{{$cliente->id}}" class="btn btn-primary btn-block mt-4"><i class="fe fe-edit-3"></i> Nueva consulta</a>
            <a href="/clientes/ver/{{$cliente->id}}" class="btn btn-primary btn-block mb-2"><i class="fe fe-back"></i> Volver</a>
        @endif

        <nav class="sidebar-nav">
            <ul class="metismenu mt-2">
                <li
                    class="{{ Request::is('clientes/cosultas/inbox/'.$cliente->id.'/Sin Leer') ? 'active' : '' }}
                            {{ Request::is('clientes/cosultas/inbox/'.$cliente->id.'') ? 'active' : '' }}">
                        <a href="/clientes/cosultas/inbox/{{$cliente->id}}/Sin Leer"><i class="icon-envelope"></i><span>Sin Leer</span></a>
                </li>
                <li class="{{ Request::is('clientes/cosultas/inbox/'.$cliente->id.'/Leido') ? 'active' : '' }}"><a href="/clientes/cosultas/inbox/{{$cliente->id}}/Leido"><i class="icon-envelope-open"></i><span>Leeidos</span></a></li>
                <li class="{{ Request::is('clientes/cosultas/inbox/'.$cliente->id.'/Borrado') ? 'active' : '' }}"><a href="/clientes/cosultas/inbox/{{$cliente->id}}/Borrado"><i class="icon-trash"></i><span>Borrados</span></a></li>
            </ul>
        </nav>
    </div>

    @yield('content')

</div>

{{-- JavaScript links --}}
@include('layouts.scripts')

<script>
$(document).ready(function() {
    "use strict";

    $('.email_page .inbox_body .right_chat li a').on('click', function() {
		$('.email_page .inbox_body .inbox_content').toggleClass('open');
    });
});
</script>
</body>
</html>
