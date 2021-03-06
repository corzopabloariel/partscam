<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('headTitle', 'PARTSCAM :: ' . $title)</title>
        <!-- <Fonts> -->
        <link href="https://fonts.googleapis.com/css?family=Exo+2:300,400,400i,500,600,700|Montserrat:300,400,400i,500,600,700" rel="stylesheet">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.18/af-2.3.2/b-1.5.4/b-colvis-1.5.4/b-html5-1.5.4/b-print-1.5.4/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-1.5.0/sl-1.2.6/datatables.min.css"/>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

        <!-- </Fonts> -->
        <!-- <Styles> -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="{{ asset('css/select2.css') }}" rel="stylesheet">
        <link href="https://select2.github.io/select2-bootstrap-theme/css/select2-bootstrap.css" rel="stylesheet">
        <link href="{{ asset('css/css.css') }}" rel="stylesheet">
        <link href="{{ asset('css/page.css') }}" rel="stylesheet">
        @stack('styles')
        <!-- </Styles> -->
    </head>
    <body>
        @if(session('success'))
            <div class="position-fixed w-100 text-center" style="z-index:9999;">
                <div class="alert alert-success" style="display: inline-block;">
                    {!! session('success')["mssg"] !!}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        @endif
        @if($errors->any())
            <div class="position-fixed w-100 text-center" style="z-index:9999;">
                <div class="alert alert-danger alert-dismissible fade show d-inline-block">
                    {!! $errors->first('mssg') !!}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        @endif
        <div class="wrapper">
            @include('page.parts.general.header')
            <section>@yield('body')</section>
            @include('page.parts.general.footer')
            <a href="https://wa.me/{{$datos['empresa']['telefono']['wha'][0]}}" class="position-fixed text-white rounded-circle bg-success d-flex justify-content-center align-items-center" style="font-size: 20px; right: 10px;bottom: 10px;z-index: 99;width: 55px;height: 55px;">
                <i class="fab fa-whatsapp"></i>
            </a>
            <a href="tel:{{$datos['empresa']['telefono']['tel'][0]}}" class="position-fixed text-white rounded-circle bg-primary d-flex justify-content-center align-items-center" style="font-size: 20px;left: 10px;bottom: 10px;z-index: 99;width: 55px;height: 55px;">
                <i class="fas fa-phone"></i>
            </a>
        </div>
        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="{{ asset('js/slick.js') }}"></script>
        <script src="{{ asset('js/select2.full.js') }}"></script>
        <script src="{{ asset('js/declaration.js') }}"></script>
        <script src="{{ asset('js/pyrus.min.js') }}"></script>
        <script>
            window.url = "{{ url()->current() }}";
            $(document).ready(function() {
                const hNav = $("nav").outerHeight();

                $(document).scroll(function() {
                    if($(this).scrollTop() > hNav) {
                        if(!$("#ulNavFixed.fixed").length)
                            $("#ulNavFixed").addClass("fixed");
                    } else
                        $("#ulNavFixed").removeClass("fixed");
                });

                if($("nav .menu").find(`a[href="${window.url}"]`).length)
                    $("nav .menu").find(`a[href="${window.url}"]`).addClass("active");
                
                if($("#marcas").length) {
                    slickMarcas = $("#marcas").slick({
                        dots: false,
                        infinite: true,
                        slidesToShow: 5,
                        slidesToScroll: 1,
                        autoplay: true,
                        autoplaySpeed: 2000,
                        responsive: [
                            {
                                breakpoint: 1024,
                                settings: {
                                    slidesToShow: 3,
                                    slidesToScroll: 1
                                }
                            },
                            {
                                breakpoint: 768,
                                settings: {
                                    slidesToShow: 2,
                                    slidesToScroll: 1
                                }
                            },
                            {
                                breakpoint: 425,
                                settings: {
                                    slidesToShow: 1,
                                    slidesToScroll: 1,
                                    dots: true
                                }
                            }
                        ]
                    });
                }
            });
            if(localStorage.carrito !== undefined) {
                let aux = JSON.parse(localStorage.carrito);
                let t = Object.keys(aux).length;
                if(t > 0) {
                    $("#carritoHeader").find("span").text(t);
                    $("#carritoHeader").attr("href","{{ URL::to('carrito') }}");
                } else
                    $("#carritoHeader").attr("href","{{ URL::to('productos') }}");
            } else
                $("#carritoHeader").attr("href","{{ URL::to('productos') }}");
        </script>
        @stack('scripts')
        {{--<script src="{{ asset('js/adm.js') }}"></script>--}}
    </body>
</html>