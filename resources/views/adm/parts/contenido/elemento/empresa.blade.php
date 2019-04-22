<div class="alert alert-warning" role="alert">
    <h4 class="alert-heading">Contenido {{strtoupper($seccion)}}</h4>
    <p>Desde esta sección puede asignar qué elementos mostrar en la vista. Cada vista tiene características y elementos únicos. Seleccione estos en el selector SECCIONES.</p>
    <hr>
    <p class="mb-0"><strong>Elementos:</strong> slider</p>
</div>
<div id="wrapper-form" class="mt-2">
    <div class="card">
        <div class="card-body">
            <form id="form" novalidate class="pt-2" action="{{ url('/adm/contenido/' . $seccion . '/update') }}" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <div class="container-form"></div>
            </form>
        </div>
    </div>
</div>

@push('scripts_distribuidor')
<script src="//cdn.ckeditor.com/4.7.3/full/ckeditor.js"></script>
<script>
    $(document).on("ready",function() {
        $(".ckeditor").each(function () {
            CKEDITOR.replace( $(this).attr("name") );
        });
    });

    const src = "{{ asset('images/general/no-img.png') }}";
    window.pyrus = new Pyrus("empresa", null, src);
    window.contenido = @json($contenido);
    /** ------------------------------------- */
    readURL = function(input, target) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $(`#${target}`).attr(`src`,`${e.target.result}`);
            };
            reader.readAsDataURL(input.files[0]);
        }
    };
    /** ------------------------------------- */
    init = function(callbackOK) {
        console.log("CONSTRUYENDO FORMULARIO Y TABLA");
        /** */
        $("#form .container-form").html(window.pyrus.formulario());

        $('#page').val(window.contenido.data.PAGE).trigger("change");
        setTimeout(() => {
            callbackOK.call(this);
        }, 50);
    }
    /** */
    init(function() {
        CKEDITOR.instances[`texto_empresa_es`].setData(window.contenido.data.CONTENIDO.empresa.texto.es);
        CKEDITOR.instances[`texto_filosofia_es`].setData(window.contenido.data.CONTENIDO.filosofia.texto.es);
        $(`[name="titulo_empresa_es"]`).val(window.contenido.data.CONTENIDO.empresa.titulo.es);
        $(`[name="titulo_filosofia_es"]`).val(window.contenido.data.CONTENIDO.filosofia.titulo.es);

        date = new Date();
        img = `{{ asset('${window.contenido.data.CONTENIDO.image}') }}?t=${date.getTime()}`;
        $(`#src-image`).attr("src",img);
    });
</script>
@endpush