<div id="left-sidebar" class="sidebar">
    <div class="d-flex justify-content-between brand_name py-2">
        <h5 class="brand-name">ObConsultores</h5>
    </div>
    <div class="input-icon">
        <span class="input-icon-addon">
            <i class="fe fe-search"></i>
        </span>
        <input type="text" class="form-control" id="buscar" placeholder="Buscar...">
    </div>
    <div class="tab-content">
        <div class="tab-pane fade active show" id="all-tab">
            <nav class="sidebar-nav">
                <ul class="metismenu ci-effect-1">
                    <li class="g_heading mb-2">Modulos</li>

                    <li class="{{ Request::is('/') ? 'active' : '' }}"><a href="/"><i class="icon-home"></i><span data-hover="Inicio">Inicio</span></a></li>

                    {{-- <li class="{{ Request::is('procesos/*') ? 'active' : '' }}">
                        <a href="javascript:void(0)" class="has-arrow arrow-b"><i class="icon-drawer"></i><span data-hover="Procesos">Procesos</span></a>
                        <ul aria-expanded="false" class="collapse">

                                <li class="{{ Request::is('procesos/crear') ? 'active' : '' }}"><a href="/procesos/crear"><span data-hover="Agregar">Agregar</span></a></li>
                                <hr class="w-100">

                            @canany(['civil', 'universal'])
                                <li class="{{ Request::is('procesos/civil') ? 'active' : '' }}"><a href="/procesos/civil"><span data-hover="Civil">Civil</span></a></li>
                            @endcanany
                            @canany(['familia', 'universal'])
                                <li class="{{ Request::is('procesos/familia') ? 'active' : '' }}"><a href="/procesos/familia"><span data-hover="Familia">Familia</span></a></li>
                            @endcanany
                            @canany(['laboral', 'universal'])
                                <li class="{{ Request::is('procesos/laboral') ? 'active' : '' }}"><a href="/procesos/laboral"><span data-hover="Laboral">Laboral</span></a></li>
                            @endcanany
                            @canany(['seguridad social', 'universal'])
                                <li class="{{ Request::is('procesos/seguridad-social') ? 'active' : '' }}"><a href="/procesos/seguridad-social"><span data-hover="SeguridadSocial">Seguridad Social</span></a></li>
                            @endcanany
                            @canany(['administrativo', 'universal'])
                                <li class="{{ Request::is('procesos/administrativo') ? 'active' : '' }}"><a href="/procesos/administrativo"><span data-hover="Administrativo">Administrativo</span></a></li>
                            @endcanany
                            @canany(['penal', 'universal'])
                                <li class="{{ Request::is('procesos/penal') ? 'active' : '' }}"><a href="/procesos/penal"><span data-hover="Penal">Penal</span></a></li>
                            @endcanany
                            @canany(['otros', 'universal'])
                                <li class="{{ Request::is('procesos/otros') ? 'active' : '' }}"><a href="/procesos/otros"><span data-hover="Otros">Otros</span></a></li>
                            @endcanany
                        </ul>
                    </li> --}}

                    @canany(['clientes', 'universal'])
                        <li class="{{ Request::is('clientes/*') || Request::is('clientes') ? 'active' : '' }}"><a href="/clientes"><i class="icon-user"></i><span data-hover="Clientes">Clientes</span></a></li>
                    @endcanany

                    @canany(['demandados', 'universal'])
                        {{-- <li class="{{ Request::is('demandados/*') || Request::is('demandados') ? 'active' : '' }}"><a href="/demandados"><i class="icon-user-unfollow"></i><span data-hover="Demandados">Demandados</span></a></li> --}}
                    @endcanany

                    @canany(['consultas', 'universal'])
                        <li class="{{ Request::is('consultas/*') || Request::is('consultas') ? 'active' : '' }}"><a href="/consultas"><i class="icon-envelope-open"></i><span data-hover="Consultas">Consultas</span></a></li>
                    @endcanany

                    <li class="{{ Request::is('calendario/*') || Request::is('calendario') ? 'active' : '' }}"><a href="/calendario"><i class="icon-calendar"></i><span data-hover="Calendario">Calendario</span></a></li>

                    @role('admin')
                    <li class="g_heading mb-2">Administrador</li>

                    <li class="{{ Request::is('administrador/usuarios/*') || Request::is('administrador/usuarios') ? 'active' : '' }}"><a href="/administrador/usuarios"><i class="icon-users"></i><span data-hover="Usuarios">Usuarios</span></a></li>
                    @endrole

                    <li><a href=""><i class="fa fa-building-o"></i><span class="mr-1" data-hover="Administracion">Administración</span><i class="fa fa-angle-double-down ml-5"></i></a>
                        <ul class="sub-menu {{ Request::is('administrador/documentacion_legal') ? 'collapse in' : '' }}{{ Request::is('administrador/personal') ? 'collapse in' : '' }}{{ Request::is('administrador/personal/*') ? 'collapse in' : '' }}" aria-expanded="{{ Request::is('administrador/documentacion_legal') ? 'true' : 'false' }}{{ Request::is('administrador/personal') ? 'true' : 'false' }}{{ Request::is('administrador/personal/*') ? 'true' : 'false' }}">
                            <li class="{{ Request::is('administrador/documentacion_legal') ? 'active' : '' }}"><a href="{{route('documentacion')}}"><i class="icon-briefcase"></i><span data-hover="Documentación L.">Documentación {{ Request::is('administrador/documentacion_legal') ? 'L.' : 'Legal' }}</span></a></li>
                            <li class="{{ Request::is('administrador/personal') ? 'active' : '' }}{{ Request::is('administrador/personal/*') ? 'active' : '' }}"><a href="{{route('personal')}}"><i class="icon-users"></i><span data-hover="Personal">Personal</span></a></li>
                            <li class="{{ Request::is('administrador/pagina-web') ? 'active' : '' }}{{ Request::is('administrador/pagina-web/*') ? 'active' : '' }}"><a href="{{route('pagina-web')}}"><i class="icon-laptop"></i><span data-hover="Pagina_Web">Pagina Web</span></a></li>
                        </ul>
                    </li>

                </ul>
            </nav>
        </div>
    </div>
</div>
