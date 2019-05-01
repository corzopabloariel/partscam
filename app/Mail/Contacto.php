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
    public function __construct($nombre, $apellido, $telefono, $email, $mensaje, $marca, $modelo, $anio)
    {
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->telefono = $telefono;
        $this->email = $email;
        $this->mensaje = $mensaje;
        $this->marca = $marca;
        $this->modelo = $modelo;
        $this->anio = $anio;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('page.form.contacto')->with([
            'nombre' => $this->nombre,
            'telefono' => $this->telefono,
            'apellido' => $this->apellido,
            'email' => $this->email,
            'mensaje' => $this->mensaje,
            'marca' => $this->marca,
            'modelo' => $this->modelo,
            'anio' => $this->anio
        ]);
    }
}
