<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Pedido extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($transaccion, $persona, $productos)
    {
        $this->transaccion = $transaccion;
        $this->persona = $persona;
        $this->productos = $productos;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Pedido de producto')->view('page.form.pedido')->with([
            'transaccion' => $this->transaccion,
            'persona' => $this->persona,
            'productos' => $this->productos
        ]);
    }
}
