<div class="position-fixed w-100 h-100 bg-light d-none justify-content-center align-items-center" style="left: 0; top: 0; z-index: 1111" id="mascara">
    <div class="d-flex align-items-center">
        <div class="spinner-grow text-primary mr-2" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        ACTUALIZANDO
        <small class="badge badge-warning shake-constant shake-chunk text-uppercase ml-2">espere</small>
    </div>
</div>
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
                            <li><strike>[E] Familia</strike></li>
                            <li><strike>[F] Modelo</strike></li>
                            <li><strike>[G] categoría (en el caso de poseer subcategoría, concatenar nombres con ==.== )</strike></li>
                        </ul>
                    </li>
                </ol>
                <p class="card-text">En el caso de no poseer algún campo, dejar vacío la celda.</p>
                <hr>
                <form onsubmit="carga()" action="{{ url('/adm/familias/carga') }}" method="post" enctype="multipart/form-data">
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
                    <li><strong>FAMILIA</strong> Por defecto :: <span class="text-dark">SIN FAMILIA</span></li>
                    <li><strong>MODELO</strong> Por defecto :: <span class="text-dark">SIN MODELO</li>
                    <li><strong>CATEGORÍA</strong> Por defecto :: <span class="text-dark">SIN CATEGORÍA</span></li>
                    <li><strong>STOCK Y PRECIO</strong> Por defecto 0</li>
                </ul>
            </div>
        </div>
    </div>
</section>

@push('scripts_distribuidor')
<script>
carga = function() {
    $("#mascara").removeClass("d-none").addClass("d-flex");
}
</script>
@endpush