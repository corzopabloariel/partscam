<li class="list-group-item @if($dato['activo']) active-menu @endif">
    <span class="d-block position-relative">
        <a class="d-block" href="{{ URL::to('productos/categoria/'. $id) }}">{{$dato["titulo"]}}</a><i class="fas fa-angle-down position-absolute"></i><i class="fas fa-angle-right position-absolute"></i>
    </span>
    @if(count($dato["hijos"]) > 0)
        <ul class="list-group @if($dato['activo']) active-submenu @endif">
        @foreach ($dato["hijos"] AS $did => $ddato)
            @include('page.parts.general._menuItem', ['id' => $did,'dato' => $ddato])
        @endforeach
        </ul>
    @endif
</li>