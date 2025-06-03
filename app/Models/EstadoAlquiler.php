<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     schema="EstadoAlquiler",
 *     type="object",
 *     title="EstadoAlquiler",
 *     required={"contrato_id", "usuario_id", "estado_pago", "fecha_reporte"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="contrato_id", type="integer", example=5),
 *     @OA\Property(property="usuario_id", type="integer", example=2),
 *     @OA\Property(property="estado_pago", type="string", example="pendiente"),
 *     @OA\Property(property="fecha_reporte", type="string", format="date", example="2025-04-08")
 * )
 */

class EstadoAlquiler extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'estado_alquiler';
    protected $fillable = ['contrato_id', 'usuario_id', 'estado_pago', 'fecha_reporte'];

    protected $casts = [
        'fecha_reporte' => 'date',
    ];

    public $timestamps = true;

    /**
     * Relación de un estado de alquiler con un contrato.
     */
    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'contrato_id');
    }

    /**
     * Relación de un estado de alquiler con un usuario (administrador).
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    /**
     * Verifica si el estado está pagado.
     */
    public function isPagado()
    {
        return $this->estado_pago === 'pagado';
    }
}
