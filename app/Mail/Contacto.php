<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Contacto extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($nombre, $telefono, $email, $mensaje, $marca, $modelo, $anio, $empresa = null)
    {
        $this->nombre = $nombre;
        $this->telefono = $telefono;
        $this->email = $email;
        $this->mensaje = $mensaje;
        $this->marca = $marca;
        $this->modelo = $modelo;
        $this->anio = $anio;
        $this->empresa = $empresa;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->replyTo($this->email,empty($this->empresa) ? $this->nombre : $this->empresa)->subject(empty($this->empresa) ? 'Contacto' : 'Consulta')->view('page.form.contacto')->with([
            'nombre' => $this->nombre,
            'telefono' => $this->telefono,
            'email' => $this->email,
            'mensaje' => $this->mensaje,
            'marca' => $this->marca,
            'modelo' => $this->modelo,
            'anio' => $this->anio,
            'empresa' => $this->empresa
        ]);
    }
}
