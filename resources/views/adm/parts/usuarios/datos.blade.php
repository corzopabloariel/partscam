<h3 class="title">{{$title}}</h3>

<section class="mt-3">
    <div class="container">
        <div class="mt-3 row justify-content-center">
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Usuario: {{$usuario["username"]}}</h5>
                        <form id="form" class="pt-2" action="{{ url('/adm/empresa/usuarios/update/' . $usuario['id']) }}" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <input type="hidden" name="is_admin" value="{{ $usuario['is_admin'] }}">
                            <input type="hidden" name="username" value="{{ $usuario['username'] }}">
                            <div class="form-group">
                                <label for="name">Nombre</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Nombre completo" value="{{$usuario['name']}}">
                            </div>
                            <div class="form-group">
                                <label for="password">Contraseña</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="Contraseña">
                            </div>
                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-block btn-success text-center text-uppercase">cambiar</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="mt-3 alert alert-warning" role="alert">Si no desea cambiar la clave, deje el campo vacío.</div>
            </div>
        </div>
    </div>
</section>