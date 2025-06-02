<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Contracts\Permission as PermissionContract;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
use Spatie\Permission\Guard;

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
class Permiso extends Model implements PermissionContract
{
    use HasFactory;

    protected $guard_name = 'web';
    protected $table = 'permiso';
    protected $fillable = ['nombre', 'guard_name'];
    public $timestamps = false;

    /**
     * Relación de un permiso con muchos roles.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Rol::class, 'roles_permisos', 'permiso_id', 'rol_id');
    }

    /**
     * Métodos requeridos por la interfaz PermissionContract
     */
    public function getGuardNameAttribute()
    {
        return $this->attributes['guard_name'] ?? 'web';
    }

    /**
     * Método para compatibilidad con Spatie
     */
    public function getNameAttribute()
    {
        return $this->attributes['nombre'];
    }

    /**
     * Encuentra un permiso por su nombre.
     *
     * @param string $name
     * @param string|null $guardName
     * @return \Spatie\Permission\Contracts\Permission
     * @throws \Spatie\Permission\Exceptions\PermissionDoesNotExist
     */
    public static function findByName(string $name, $guardName = null): PermissionContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $permission = static::where('nombre', $name)->where('guard_name', $guardName)->first();

        if (! $permission) {
            throw PermissionDoesNotExist::create($name, $guardName);
        }

        return $permission;
    }

    /**
     * Encuentra un permiso por su ID.
     *
     * @param string|int $id
     * @param string|null $guardName
     * @return \Spatie\Permission\Contracts\Permission
     * @throws \Spatie\Permission\Exceptions\PermissionDoesNotExist
     */
    public static function findById(string|int $id, ?string $guardName = null): PermissionContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $permission = static::where('id', $id)->where('guard_name', $guardName)->first();

        if (! $permission) {
            throw PermissionDoesNotExist::withId($id, $guardName);
        }

        return $permission;
    }

    /**
     * Encuentra o crea un permiso.
     *
     * @param string $name
     * @param string|null $guardName
     * @return \Spatie\Permission\Contracts\Permission
     */
    public static function findOrCreate(string $name, $guardName = null): PermissionContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $permission = static::where('nombre', $name)
            ->where('guard_name', $guardName)
            ->first();

        if (! $permission) {
            return static::create(['nombre' => $name, 'guard_name' => $guardName]);
        }

        return $permission;
    }
}
