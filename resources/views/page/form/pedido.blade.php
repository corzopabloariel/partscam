<!DOCTYPE html>
<html>
<body>
	<h2>PARTSCAM</h2>
    @if(isset($cancelado))
        <h3>Cancelación de pedido</h3> 
        <br>
        <br>
        <h3>Código de transacción: {{$transaccion["codigo"]}}</h3>

    @else
        <h3>Pedido</h3> 
        <p>Enviado desde la web</p>
        <br>
        <br>
        <h3>Código de transacción: {{$transaccion["codigo"]}}</h3>
        @if($transaccion["tipopago"] == "MP")
            <p>Estado: Pendiente de pago electrónico</p>
        @elseif($transaccion["tipopago"] == "TB")
            <p>Estado: Pendiente de transferencia</p>
        @else
            <p>Estado: Pendiente - El cliente se acerca al local</p>
        @endif
    @endif
	<br>
	<h3>Persona</h3>
	<ul>
        <li><strong>Nombre:</strong> {{$persona["nombre"]}} {{$persona["apellido"]}}</li>
        @if(!empty($persona["telefono"]))
        <li><strong>Télefono:</strong> {{$persona["telefono"]}}</li>
        @endif
        @if(!empty($persona["cuit"]))
        <li><strong>CUIT:</strong> {{$persona["cuit"]}}</li>
        @endif
        <li><strong>Condición frente al IVA:</strong> {{$persona->iva["nombre"]}}</li>
        <li><strong>Domicilio:</strong> {{$persona["domicilio"]}}. {{$persona->provincia["nombre"]}}, {{$persona->localidad["nombre"]}}</li>
		<li><strong>Email:</strong> <a href="mailto:{{$persona['email']}}">{{$persona['email']}}</a></li>
	</ul>
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