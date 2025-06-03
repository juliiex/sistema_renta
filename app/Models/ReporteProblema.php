<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     schema="ReporteProblema",
 *     type="object",
 *     title="Reporte de Problema",
 *     required={"apartamento_id", "usuario_id", "descripcion", "estado"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="apartamento_id", type="integer", example=5),
 *     @OA\Property(property="usuario_id", type="integer", example=3),
 *     @OA\Property(property="descripcion", type="string", example="La puerta del baño está dañada."),
 *     @OA\Property(property="tipo", type="string", example="plomería"),
 *     @OA\Property(property="estado", type="string", enum={"pendiente", "atendido", "cerrado"}, example="pendiente"),
 *     @OA\Property(property="fecha_reporte", type="string", format="date-time", example="2025-04-08 12:00:00")
 * )
 */
class ReporteProblema extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'reporte_problema';
    protected $fillable = ['apartamento_id', 'usuario_id', 'descripcion', 'tipo', 'estado'];

    protected $casts = [
        'fecha_reporte' => 'datetime',
    ];
    public $timestamps = false;

    /**
     * Relación de un reporte de problema con un apartamento.
     */
    public function apartamento()
    {
        return $this->belongsTo(Apartamento::class, 'apartamento_id');
    }

    /**
     * Relación de un reporte de problema con un usuario.
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}

