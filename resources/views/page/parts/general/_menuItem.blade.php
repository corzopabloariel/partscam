<li class="list-group-item @if($dato['activo']) active-menu @endif">
    @if(isset($dato["modelos"])) 
        <span class="d-block position-relative">
            <a class="d-block" href="{{ URL::to('productos/familia/' . $id) }}">{{$dato["nombre"]}}</a><i class="fas fa-angle-down position-absolute"></i><i class="fas fa-angle-right position-absolute"></i>
        </span>
        @if(count($dato["modelos"]) > 0)
            <ul class="list-group @if($dato['activo']) active-submenu @endif">
            @foreach ($dato["modelos"] AS $id => $dato)
                @include('page.parts.general._menuItem', [ 'id' => $id,'dato' => $dato ])
            @endforeach
            </ul>
        @endif
    @endif
</li>