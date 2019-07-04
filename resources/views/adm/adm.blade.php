<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('headTitle', 'PARTSCAM :: ' . $title)</title>
        <!-- <Fonts> -->
        <link href="https://fonts.googleapis.com/css?family=Titillium+Web:400,400i,600,700,900" rel="stylesheet">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.18/af-2.3.2/b-1.5.4/b-colvis-1.5.4/b-html5-1.5.4/b-print-1.5.4/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-1.5.0/sl-1.2.6/datatables.min.css"/>

        <!-- </Fonts> -->
        <!-- <Styles> -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
        <link href="https://select2.github.io/select2-bootstrap-theme/css/select2-bootstrap.css" rel="stylesheet">
        <link href="{{ asset('css/alertifyjs/alertify.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/alertifyjs/themes/bootstrap.min.css') }}" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="https://csshake.surge.sh/csshake.min.css">
        <link href="{{ asset('css/css.css') }}" rel="stylesheet">
        <link href="{{ asset('css/adm.css') }}" rel="stylesheet">
        <link href="{{ asset('css/nav.css') }}" rel="stylesheet">
        @stack('styles')
        <!-- </Styles> -->
    </head>
    <body>
        @if(session('success'))
            <div class="position-fixed w-100 text-center" style="z-index:9999;">
                <div class="alert alert-success" style="display: inline-block;">
                    {!! session('success')["mssg"] !!}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <i class="far fa-times-circle"></i>
                    </button>
                </div>
            </div>
        @endif
        @if($errors->any())
            <div class="position-fixed w-100 text-center" style="z-index:9999;">
                <div class="alert alert-danger alert-dismissible fade show d-inline-block">
                    {!! $errors->first('mssg') !!}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <i class="far fa-times-circle"></i>
                    </button>
                </div>
            </div>
        @endif
        <div class="modal fade bd-example-modal-sm" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="modal">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="far fa-times-circle"></i>
                        </button>
                    </div>{{-- event.preventDefault(); submitModal(this); --}}
                    <form action="" method="post" onsubmit="" id="form_modal">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="modal-body">
                        </div>
                        <div class="modal-footer bg-light">
                            <button class="btn btn-success text-uppercase" type="submit">cambiar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="wrapper">
            <!-- Sidebar -->
            <nav id="sidebar" class="position-fixed h-100">
                @include('adm.general.menu')
            </nav>

            <!-- Page Content -->
            <div id="content">
                @yield('body')
            </div>
        </div>
        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>

        <script src="{{ asset('js/jquery.maskMoney.js') }}"></script>
        <script src="{{ asset('js/alertify.min.js') }}"></script>
        <script src="{{ asset('js/declaration.js') }}"></script>
        <script src="{{ asset('js/pyrus.min.js') }}"></script>
        <script>
            window.url = "{{ url()->current() }}";console.log(window.url);
            alertify.defaults.transition = "slide";
            alertify.defaults.theme.ok = "btn btn-primary";
            alertify.defaults.theme.cancel = "btn btn-danger";
            alertify.defaults.theme.input = "form-control";
        </script>
        @stack('scripts')
        {{--<script src="{{ asset('js/adm.js') }}"></script>--}}
    </body>
</html>