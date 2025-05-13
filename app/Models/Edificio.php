<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Edificio",
 *     type="object",
 *     title="Edificio",
 *     required={"nombre", "direccion"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nombre", type="string", example="Edificio Central"),
 *     @OA\Property(property="direccion", type="string", example="Av. Siempre Viva 742"),
 *     @OA\Property(property="cantidad_pisos", type="integer", example=5),
 *     @OA\Property(property="imagen", type="string", example="edificio.jpg"),
 *     @OA\Property(property="descripcion", type="string", example="Edificio moderno con amplias instalaciones")
 * )
 */
class Edificio extends Model
{
    use HasFactory;

    protected $table = 'edificio';
    protected $fillable = ['nombre', 'direccion', 'cantidad_pisos', 'imagen', 'descripcion'];
    public $timestamps = true;

    /**
     * Relación de un edificio con muchos apartamentos.
     */
    public function apartamentos()
    {
        return $this->hasMany(Apartamento::class, 'edificio_id');
    }
}

