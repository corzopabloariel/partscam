<h3 class="title">{{$title}}</h3>

<section class="mt-3">
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Atención</h5>
                <p class="card-text">Para hacer la carga de productos mediante un archivo Excel, deberá cumplir lo siguiente:</p>
                <ol>
                    <li>El archivo debe guardarlo en formato xls</li>
                    <li>No tener CABECERA en la tabla</li>
                    <li>Este archivo debe estar estructurado por columnas, las cuales deberan ser cada campo de la tabla. Deberán ser 7 columnas.
                        <ul>
                            <li>[A] Código</li>
                            <li>[B] Nombre</li>
                            <li>[C] Stock / Existencia</li>
                            <li>[D] Precio ($ x.xxx,xx)</li>
                            <li>[E] Familia</li>
                            <li>[F] Modelo</li>
                            <li>[G] categoría (en el caso de poseer subcategoría, concatenar nombres con ==.== )</li>
                        </ul>
                    </li>
                </ol>
                <p class="card-text">En el caso de no poseer algún campo, dejar vacío la celda.</p>
                <hr>
                <form action="{{ url('/adm/familias/carga') }}" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroupFileAddon01">Carga</span>
                                </div>
                                <div class="custom-file">
                                    <input name="archivo" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required type="file" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                                    <label class="custom-file-label" data-browser="Buscar" for="inputGroupFile01">Seleccione archivo</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success d-block mx-auto text-uppercase">cargar</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer text-muted">
                <h5>Campos / celdas sin información:</h5>
                <ul class="mb-0">
                    <li><strong>NOMBRE</strong> Pasa a la siguiente fila</li>
                    <li><strong>FAMILIA</strong> Por defecto :: <span class="text-dark">{{ App\Familia::first()["nombre"] }}</span></li>
                    <li><strong>MODELO</strong> Por defecto :: <span class="text-dark">{{ App\Familia::first()["nombre"] }} > {{ App\Familia::first()->categorias->where("tipo",1)->first()["nombre"] }}</span></li>
                    @php
                    $aux = App\Familia::first()->categorias->where("tipo",3)->first();
                    $name = "";
                    if(!empty($aux))
                        $name = " > {$aux["nombre"]}";
                    @endphp
                    <li><strong>CATEGORÍA</strong> Por defecto :: <span class="text-dark">{{ App\Familia::first()["nombre"] }} > {{ App\Familia::first()->categorias->where("tipo",1)->first()["nombre"] }} > {{ App\Familia::first()->categorias->where("tipo",2)->first()["nombre"].$name }}</span></li>
                    <li><strong>STOCK Y PRECIO</strong> Por defecto 0</li>
                </ul>
            </div>
        </div>
    </div>
</section>