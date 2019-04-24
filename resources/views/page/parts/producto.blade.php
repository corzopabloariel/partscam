<div class="wrapper-producto py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="row justify-content-center">
                    @foreach($datos["prodfamilias"] AS $f)
                    <a href="{{ URL::to('productos/familia/' . $f['id']) }}" class="col-12 col-lg-5 my-2">
                        <img onError="this.src='{{ asset('images/general/no-img.png') }}'" class="w-100" src="{{ $f['image'] }}" alt="{{ $f['nombre'] }}">
                        <p class="title nombre mb-0">{{ $f["nombre"] }}</p>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>