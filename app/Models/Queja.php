<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     schema="Queja",
 *     type="object",
 *     title="Queja",
 *     required={"usuario_id", "descripcion", "tipo", "fecha_envio"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="usuario_id", type="integer", example=3),
 *     @OA\Property(property="descripcion", type="string", example="La aplicación presenta errores al registrar pagos."),
 *     @OA\Property(property="tipo", type="string", example="Aplicativo"),
 *     @OA\Property(property="fecha_envio", type="string", format="date", example="2024-04-10")
 * )
 */
class Queja extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'queja';
    protected $fillable = ['usuario_id', 'descripcion', 'tipo', 'fecha_envio'];

    protected $casts = [
        'fecha_envio' => 'date',
    ];
    public $timestamps = false;
    /**
     * Relación de una queja con un usuario.
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
