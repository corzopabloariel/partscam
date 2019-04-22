<div class="sidebar-header">
    <i class="fas fa-bars"></i>
    <h3 class="m-0">Menu</h3>
</div>
<div class="position-relative" style="height: calc(100% - 73px); overflow-y:auto; overflow-x: hidden;">
    <div class="w-100 position-absolute">
        <ul class="list-unstyled components m-0 p-0">
            <li class="">
                <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false">
                    <i class="fas fa-home"></i>
                    <span class="nav-label">Home</span>
                </a>
                <ul class="collapse list-unstyled" id="homeSubmenu">
                    <li>
                        <a data-link="u" href="{{ route('contenido.edit', ['seccion' => 'home']) }}">
                            <i class="fas fa-file-contract"></i>
                            <span class="nav-label">Contenido</span>
                        </a>
                    </li>
                    <li>
                        <a data-link="u" href="{{ route('slider.index', ['seccion' => 'home']) }}">
                            <i class="fas fa-spider"></i>
                            <span class="nav-label">Slider</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#empresaSubmenu" data-toggle="collapse" aria-expanded="false">
                    <i class="fas fa-building"></i>
                    <span class="nav-label">Empresa</span>
                </a>
                <ul class="collapse list-unstyled" id="empresaSubmenu">
                    <li>
                        <a data-link="u" href="{{ route('contenido.edit', ['seccion' => 'empresa']) }}">
                            <i class="fas fa-file-contract"></i>
                            <span class="nav-label">Contenido</span>
                        </a>
                    </li>
                    <li>
                        <a data-link="u" href="{{ route('slider.index', ['seccion' => 'empresa']) }}">
                            <i class="fas fa-spider"></i>
                            <span class="nav-label">Slider</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#productoSubmenu" data-toggle="collapse" aria-expanded="false">
                    <i class="fas fa-industry"></i>
                    <span class="nav-label">Productos</span>
                </a>
                <ul class="collapse list-unstyled" id="productoSubmenu">
                    <li>
                        <a data-link="u" href="{{-- route('contenido.edit', ['seccion' => 'calidad']) --}}">
                            <i class="fas fa-tasks"></i>
                            <span class="nav-label">Familia de productos</span>
                        </a>
                    </li>
                    <li>
                        <a data-link="u" href="{{-- route('archivo.index', ['seccion' => 'calidad']) --}}">
                            <i class="fas fa-clipboard-list"></i>
                            <span class="nav-label">Todos los productos</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#ofertasSubmenu" data-toggle="collapse" aria-expanded="false">
                    <i class="fas fa-hand-holding-usd"></i>
                    <span class="nav-label">Ofertas</span>
                </a>
                <ul class="collapse list-unstyled" id="ofertasSubmenu">
                    <li>
                        <a data-link="u" href="{{-- route('contenido.edit', ['seccion' => 'home']) --}}">
                            <i class="fas fa-stream"></i>
                            <span class="nav-label">Todas las ofertas</span>
                        </a>
                    </li>
                    <li>
                        <a data-link="u" href="{{ route('slider.index', ['seccion' => 'oferta']) }}">
                            <i class="fas fa-spider"></i>
                            <span class="nav-label">Slider</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#pagosSubmenu" data-toggle="collapse" aria-expanded="false">
                    <i class="fas fa-building"></i>
                    <span class="nav-label">Pagos y envios</span>
                </a>
                <ul class="collapse list-unstyled" id="pagosSubmenu">
                    <li>
                        <a data-link="u" href="{{ route('contenido.edit', ['seccion' => 'pagos']) }}">
                            <i class="fas fa-file-contract"></i>
                            <span class="nav-label">Contenido</span>
                        </a>
                    </li>
                    <li>
                        <a data-link="u" href="{{ route('slider.index', ['seccion' => 'pagos']) }}">
                            <i class="fas fa-spider"></i>
                            <span class="nav-label">Slider</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li><hr/></li>
            <li>
                <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false">
                    <i class="fas fa-compass"></i>
                    <span class="nav-label">PARTSCAM</span>
                </a>
                <ul class="collapse list-unstyled" id="pageSubmenu">
                    <li>
                        <a data-link="u" href="{{ route('empresa.datos') }}">
                            <span>Datos</span>
                        </a>
                    </li>
                    <li>
                        <a data-link="u" href="{{ route('empresa.metadatos.index') }}">
                            <i class="fas fa-bullhorn"></i>
                            <span class="nav-label">Metadatos</span>
                        </a>
                    </li>
                    @if(Auth::user()["is_admin"])
                    <li>
                        <a data-link="u" href="{{-- route('empresa.usuarios') --}}">
                            <i class="fas fa-users-cog"></i>
                            <span class="nav-label">Usuarios</span>
                        </a>
                    </li>
                    @else
                    <li>
                        <a data-link="u" href="{{-- route('empresa.mis_datos') --}}">
                            <i class="fas fa-user-cog"></i>
                            <span class="nav-label">Mis datos</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            <li><hr/></li>
            <li>
                <a href="https://osole.freshdesk.com/support/home" target="_blank">
                <i class="fas fa-ticket-alt"></i>
                    <span class="nav-label">Soporte</span>
                </a>
            </li>
            <li>
                <a class="bg-danger text-white" href="{{--route('adm.logout') --}}">
                    <i class="fas fa-power-off text-white"></i>
                    <span class="nav-label">Salir</span>
                </a>
            </li>
        </ul>
    </div>
</div>