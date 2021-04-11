<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoUsuario extends Model
{
    use HasFactory;

    public const COMUM = 1;
    public const LOGISTA = 2;

    protected $table = 'tb_tipo_usuario';

    protected $primary_key = 'iTipo_usuario_id';
}
