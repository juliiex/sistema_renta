<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     schema="RolPermiso",
 *     title="RolPermiso",
 *     description="Relación entre un rol y un permiso",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="rol_id", type="integer", example=2),
 *     @OA\Property(property="permiso_id", type="integer", example=3)
 * )
 */
class RolPermiso extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'roles_permisos';
    protected $fillable = ['rol_id', 'permiso_id'];
    public $timestamps = false;

    /**
     * Relación con el modelo Rol.
     */
    public function rol()
    {
        return $this->belongsTo(Rol::class);
    }

    /**
     * Relación con el modelo Permiso.
     */
    public function permiso()
    {
        return $this->belongsTo(Permiso::class);
    }
}
