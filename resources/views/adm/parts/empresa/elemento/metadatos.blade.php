<div>
    <button disabled="true" id="btnADD" onclick="add(this)" class="btn btn-primary text-uppercase" type="button">Agregar<i class="fas fa-plus ml-2"></i></button>
</div>
<div style="display: none;" id="wrapper-form" class="mt-2">
    <div class="card">
        <div class="card-body">
            <button onclick="remove(this)" type="button" class="close position-absolute" aria-label="Close">
                <span aria-hidden="true"><i class="fas fa-times"></i></span>
            </button>
            <form id="form" novalidate class="pt-2" action="{{ url('/adm/familia/store') }}" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <div class="container-form"></div>
            </form>
        </div>
    </div>
</div>
<div class="card mt-2" id="wrapper-tabla">
    <div class="card-body">
        <table class="table mb-0" id="tabla"></table>
    </div>
</div>

@push('scripts_distribuidor')
<script>
    window.pyrus = new Pyrus("metadatos");
    window.metadatos = @json($metadatos)
    
    
    /** ------------------------------------- */
    add = function(t, seccion = "", data = null) {
        let btn = $(t);

        $("#wrapper-form").toggle(800,"swing");

        $("#wrapper-tabla").toggle("fast");

        if(seccion != "")
            action = `{{ url('/adm/empresa/${window.pyrus.entidad}/update/${seccion}') }}`;
        else
            action = `{{ url('/adm/empresa/${window.pyrus.entidad}/' . strtolower($seccion) . '/store') }}`;
        if(data !== null) {
            $(`[name="seccion"]`).val(seccion);
            for(let x in window.pyrus.especificacion) {
                if(x == "seccion") continue;
                if(window.pyrus.especificacion[x].EDITOR !== undefined) {
                    CKEDITOR.instances[`${x}_es`].setData(data[x]);
                    continue;
                }
                if(window.pyrus.especificacion[x].TIPO == "TP_FILE") {
                    date = new Date();
                    img = `{{ asset('${data[x]}') }}?t=${date.getTime()}`;
                    $(`#src-${x}`).attr("src",img);
                    continue;
                }
                $(`[name="${x}"]`).val(data[x]);
            }
        }
        elmnt = document.getElementById("form");
        elmnt.scrollIntoView();
        $("#form").attr("action",action);
    };
    /** ------------------------------------- */
    remove = function(t) {
        add($("#btnADD"));

        for(let x in window.pyrus.especificacion) {
            if(window.pyrus.especificacion[x].EDITOR !== undefined) {
                CKEDITOR.instances[x].setData('');
                continue;
            }
            if(window.pyrus.especificacion[x].TIPO == "TP_FILE")
                $(`#src-${x}`).attr("src","");
            $(`[name="${x}"]`).val("");
        }
    };
    /** ------------------------------------- */
    edit = function(t, seccion) {
        //$(t).attr("disabled",true);
        add($("#btnADD"),seccion,window.metadatos[seccion]);
    };
    /** ------------------------------------- */
    init = function() {
        console.log("CONSTRUYENDO FORMULARIO Y TABLA");
        /** */
        $("#form .container-form").html(window.pyrus.formulario());

        let columnas = window.pyrus.columnas();
        let table = $("#tabla");
        columnas.forEach(function(e) {
            if(!table.find("thead").length) 
                table.append("<thead></thead>");
            table.find("thead").append(`<th class="${e.CLASS}" style="width:${e.WIDTH}">${e.NAME}</th>`);
        });
        table.find("thead").append(`<th class="text-uppercase text-center" style="width:150px">acción</th>`);

        for(let x in window.metadatos) {
            let tr = "";
            if(!table.find("tbody").length) 
                table.append("<tbody></tbody>");
            tr += `<td class="text-uppercase text-left">${x}</td>`;
            tr += `<td>${window.metadatos[x].metas === null ? "-" : window.metadatos[x].metas}</td>`;
            tr += `<td>${window.metadatos[x].descripcion === null ? "-" : window.metadatos[x].descripcion}</td>`;
            tr += `<td class="text-center"><button onclick="edit(this,'${x}')" class="btn btn-warning"><i class="fas fa-pencil-alt"></i></td>`;
            table.find("tbody").append(`<tr data-id="${x}">${tr}</tr>`);
        }
    }
    /** */
    init();
</script>
@endpush