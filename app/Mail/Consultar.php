<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Consultar extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($nombre, $email, $mensaje, $cantidad, $producto)
    {
        $this->nombre = $nombre;
        $this->email = $email;
        $this->mensaje = $mensaje;
        $this->cantidad = $cantidad;
        $this->producto = $producto;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Consulta de Producto')->view('page.form.consultar')->with([
            'nombre' => $this->nombre,
            'email' => $this->email,
            'mensaje' => $this->mensaje,
            'cantidad' => $this->cantidad,
            'producto' => $this->producto
        ]);
    }
}
