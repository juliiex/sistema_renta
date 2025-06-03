<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     schema="UsuarioRol",
 *     title="UsuarioRol",
 *     description="Relación entre un usuario y un rol",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="usuario_id", type="integer", example=5),
 *     @OA\Property(property="rol_id", type="integer", example=2)
 * )
 */
class UsuarioRol extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'usuarios_roles';
    protected $fillable = ['usuario_id', 'rol_id'];
    public $timestamps = false;

    /**
     * Relación con el modelo Usuario.
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    /**
     * Relación con el modelo Rol.
     */
    public function rol()
    {
        return $this->belongsTo(Rol::class);
    }
}
