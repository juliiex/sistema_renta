<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *   schema="RecordatorioPago",
 *   type="object",
 *   title="Recordatorio de Pago",
 *   required={"usuario_id", "metodo"},
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="usuario_id", type="integer", example=2),
 *   @OA\Property(property="metodo", type="string", example="Correo electrónico, SMS, Llamada"),
 *   @OA\Property(property="fecha_envio", type="string", format="date-time", example="2025-04-10 12:00:00")
 * )
 */
class RecordatorioPago extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'recordatorio_pago';
    protected $fillable = ['usuario_id', 'metodo'];
    public $timestamps = false;

    protected $casts = [
        'fecha_envio' => 'datetime',
    ];

    /**
     * Relación de un recordatorio de pago con un usuario.
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}

