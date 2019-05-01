<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PedidoCliente extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($transaccion, $persona, $productos, $textos, $cancelado = 0)
    {
        $this->transaccion = $transaccion;
        $this->persona = $persona;
        $this->productos = $productos;
        $this->textos = $textos;
        $this->cancelado = $cancelado;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->cancelado ? 'Compra cancelada' : 'Compra en PARTSCAM')->view('page.form.compra')->with([
            'transaccion' => $this->transaccion,
            'persona' => $this->persona,
            'productos' => $this->productos,
            'textos' => $this->textos,
            'cancelado' => $this->cancelado
        ]);
    }
}
