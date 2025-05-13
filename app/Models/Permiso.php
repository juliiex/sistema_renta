<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Permiso",
 *     title="Permiso",
 *     description="Modelo de permiso del sistema",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1,
 *         description="ID único del permiso"
 *     ),
 *     @OA\Property(
 *         property="nombre",
 *         type="string",
 *         example="Crear usuarios",
 *         description="Nombre del permiso"
 *     )
 * )
 */
class Permiso extends Model
{
    use HasFactory;

    protected $table = 'permiso';
    protected $fillable = ['nombre'];
    public $timestamps = false;
    /**
     * Relación de un permiso con muchos roles.
     */
    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'roles_permisos', 'permiso_id', 'rol_id');
    }
}
