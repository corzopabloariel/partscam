<!DOCTYPE html>
<html>
<body>
	<h2>PARTSCAM</h2>
    @if(isset($cancelado))
        <h3>Cancelación de compra</h3>
        <p>Hola {{$persona["nombre"]}}</p>
        <br>
        <h3>Código de transacción: {{$transaccion["codigo"]}}</h3>

    @else
        <h3>Compra</h3> 
        <p>Hola {{$persona["nombre"]}}</p>
        <p>Detalles de la compra en <a href="https://partscam.com.ar/" target="blank">partscam.com.ar</a></p>
        <br>
        <h3>Código de transacción: {{$transaccion["codigo"]}}</h3>
        @if($transaccion["tipopago"] == "MP")
            {!! $textos["mp"] !!}
        @elseif($transaccion["tipopago"] == "TB")
            <p><strong>BANCO:</strong> {{$textos["tb"]["banco"]}}</p>
            <p><strong>TIPO:</strong> {{$textos["tb"]["tipo"]}}</p>
            <p><strong>NRO:</strong> {{$textos["tb"]["nro"]}}</p>
            <p><strong>SUC.:</strong> {{$textos["tb"]["suc"]}}</p>
            <p><strong>NOMBRE DE LA CUENTA:</strong> {{$textos["tb"]["nombre"]}}</p>
            <p><strong>CBU:</strong> {{$textos["tb"]["cbu"]}}</p>
            <p><strong>CUIT:</strong> {{$textos["tb"]["cuit"]}}</p>
            <br/>
            <p>Enviar comprobante a <a href="mailto:{{$textos['tb']['emailpago']}}">{{$textos['tb']['emailpago']}}</a></p>
        @else
            {!! $textos['pl'] !!}
        @endif
    @endif
    <br>
    <h4>Productos:</h4>
    <table style="width:100%">
        <thead>
            <th style="background: #dedede; text-align:left; padding: 5px;">Código</th>
            <th style="background: #dedede; text-align:left; padding: 5px;">Nombre</th>
            <th style="background: #dedede; text-align:left; padding: 5px;">Familia</th>
            <th style="background: #dedede; text-align:left; padding: 5px;">Cantidad</th>
            <th style="background: #dedede; text-align:left; padding: 5px;">Precio</th>
        </thead>
        <tbody>
        @foreach($productos AS $p)
        <tr>
            <td style="border-bottom: 1px solid; padding: 5px;">{{$p->producto["codigo"]}}</td>
            <td style="border-bottom: 1px solid; padding: 5px;">{{$p->producto["nombre"]}}</td>
            <td style="border-bottom: 1px solid; padding: 5px;">{{$p->producto->familia["nombre"]}}</td>
            <td style="border-bottom: 1px solid; padding: 5px;">{{$p->cantidad}}</td>
            <td style="border-bottom: 1px solid; padding: 5px;">$ {{number_format($p["precio"],2,",",".")}}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    <h3 style="text-align:right;padding: 5px">Total: $ {{number_format($transaccion["total"],2,",",".")}}</h3>
</body>
</html>