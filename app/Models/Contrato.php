<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Contrato",
 *     required={"usuario_id", "apartamento_id", "fecha_inicio", "fecha_fin", "firma_digital", "estado"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="usuario_id", type="integer", example=3),
 *     @OA\Property(property="apartamento_id", type="integer", example=5),
 *     @OA\Property(property="fecha_inicio", type="string", format="date", example="2025-05-01"),
 *     @OA\Property(property="fecha_fin", type="string", format="date", example="2026-05-01"),
 *     @OA\Property(property="firma_digital", type="string", example="firmado_digitalmente_123"),
 *     @OA\Property(property="estado", type="string", example="activo")
 * )
 */
class Contrato extends Model
{
    use HasFactory;

    protected $table = 'contrato';
    protected $fillable = ['usuario_id', 'apartamento_id', 'fecha_inicio', 'fecha_fin', 'firma_digital', 'estado'];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    public $timestamps = true;

    /**
     * Relación de un contrato con un usuario (inquilino).
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    /**
     * Relación de un contrato con un apartamento.
     */
    public function apartamento()
    {
        return $this->belongsTo(Apartamento::class, 'apartamento_id');
    }

    /**
     * Relación de un contrato con muchos estados de alquiler.
     */
    public function estadosAlquiler()
    {
        return $this->hasMany(EstadoAlquiler::class, 'contrato_id');
    }
}
