<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Contracts\Role as RoleContract;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Spatie\Permission\Guard;

class Rol extends Model implements RoleContract
{
    use HasFactory, HasPermissions;

    protected $guard_name = 'web';
    protected $table = 'rol';
    protected $fillable = ['nombre', 'guard_name'];
    public $timestamps = false;

    /**
     * Relación de un rol con muchos usuarios.
     */
    public function usuarios(): BelongsToMany
    {
        // Corregido para usar la tabla correcta
        return $this->belongsToMany(Usuario::class, 'usuarios_roles', 'rol_id', 'usuario_id');
    }

    /**
     * Relación de un rol con muchos permisos.
     */
    public function permisos(): BelongsToMany
    {
        // Corregido para usar la tabla correcta
        return $this->belongsToMany(Permiso::class, 'roles_permisos', 'rol_id', 'permiso_id');
    }

    /**
     * Métodos requeridos por la interfaz RoleContract
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
     * Método para compatibilidad con Spatie - alias de permisos()
     */
    public function permissions(): BelongsToMany
    {
        return $this->permisos();
    }

    /**
     * Encuentra un rol por su nombre.
     *
     * @param string $name
     * @param string|null $guardName
     * @return \Spatie\Permission\Contracts\Role
     * @throws \Spatie\Permission\Exceptions\RoleDoesNotExist
     */
    public static function findByName(string $name, $guardName = null): RoleContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $role = static::where('nombre', $name)->where('guard_name', $guardName)->first();

        if (! $role) {
            throw RoleDoesNotExist::named($name);
        }

        return $role;
    }

    /**
     * Encuentra un rol por su ID.
     *
     * @param string|int $id
     * @param string|null $guardName
     * @return \Spatie\Permission\Contracts\Role
     * @throws \Spatie\Permission\Exceptions\RoleDoesNotExist
     */
    public static function findById(string|int $id, ?string $guardName = null): RoleContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $role = static::where('id', $id)->where('guard_name', $guardName)->first();

        if (! $role) {
            throw RoleDoesNotExist::withId($id);
        }

        return $role;
    }

    /**
     * Encuentra o crea un rol.
     *
     * @param string $name
     * @param string|null $guardName
     * @return \Spatie\Permission\Contracts\Role
     */
    public static function findOrCreate(string $name, $guardName = null): RoleContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $role = static::where('nombre', $name)
            ->where('guard_name', $guardName)
            ->first();

        if (! $role) {
            return static::create(['nombre' => $name, 'guard_name' => $guardName]);
        }

        return $role;
    }
}
