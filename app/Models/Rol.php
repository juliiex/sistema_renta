<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Rol",
 *     title="Rol",
 *     description="Esquema del modelo Rol",
 *     required={"nombre"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         readOnly=true,
 *         description="ID autoincremental del rol"
 *     ),
 *     @OA\Property(
 *         property="nombre",
 *         type="string",
 *         maxLength=255,
 *         description="Nombre del rol"
 *     )
 * )
 */
class Rol extends Model
{
    use HasFactory;

    protected $table = 'rol';
    protected $fillable = ['nombre'];
    public $timestamps = false;

    /**
     * Relación de un rol con muchos usuarios.
     */
    public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'usuario_roles', 'rol_id', 'usuario_id');
    }

    /**
     * Relación de un rol con muchos permisos.
     */
    public function permisos()
    {
        return $this->belongsToMany(Permiso::class, 'roles_permisos', 'rol_id', 'permiso_id');
    }
}
