<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     schema="Evaluacion",
 *     type="object",
 *     title="Evaluacion",
 *     required={"usuario_id", "apartamento_id", "calificacion", "comentario"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="usuario_id", type="integer", example=3),
 *     @OA\Property(property="apartamento_id", type="integer", example=2),
 *     @OA\Property(property="calificacion", type="integer", format="int32", example=5),
 *     @OA\Property(property="comentario", type="string", example="Muy buen apartamento, limpio y bien ubicado."),
 *     @OA\Property(property="fecha_evaluacion", type="string", format="datetime", example="2025-04-08 12:00:00")
 * )
 */

class Evaluacion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'evaluacion';
    protected $fillable = ['usuario_id', 'apartamento_id', 'calificacion', 'comentario'];

    public $timestamps = false; // Evitamos los timestamps automáticos de Laravel

    protected $casts = [
        'fecha_evaluacion' => 'datetime',
    ];

    /**
     * Relación de una evaluación con un usuario (inquilino).
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    /**
     * Relación de una evaluación con un apartamento.
     */
    public function apartamento()
    {
        return $this->belongsTo(Apartamento::class, 'apartamento_id');
    }
}
