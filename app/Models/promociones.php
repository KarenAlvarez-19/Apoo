<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class promociones extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $connection = 'mysql'; //tipo de conexion

    protected $table = 'promociones';

    protected $casts = [ 
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date'
    ];
}
