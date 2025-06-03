<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     schema="SolicitudAlquiler",
 *     type="object",
 *     title="SolicitudAlquiler",
 *     required={"usuario_id", "apartamento_id", "estado_solicitud"},
 *     @OA\Property(property="id", type="integer", readOnly=true, example=1),
 *     @OA\Property(property="fecha_solicitud", type="string", format="datetime", example="2024-04-08 12:00:00"),
 *     @OA\Property(property="usuario_id", type="integer", example=2),
 *     @OA\Property(property="apartamento_id", type="integer", example=3),
 *     @OA\Property(property="estado_solicitud", type="string", example="pendiente")
 * )
 */
class SolicitudAlquiler extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'solicitud_alquiler';
    protected $fillable = ['usuario_id', 'apartamento_id', 'estado_solicitud'];
    public $timestamps = false;

    protected $casts = [
        'fecha_solicitud' => 'datetime',
    ];

    /**
     * Relación de una solicitud de alquiler con un usuario.
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    /**
     * Relación de una solicitud de alquiler con un apartamento.
     */
    public function apartamento()
    {
        return $this->belongsTo(Apartamento::class, 'apartamento_id');
    }
}
