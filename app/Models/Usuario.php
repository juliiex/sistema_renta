<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $guard_name = 'web';
    protected $table = 'usuario';
    protected $fillable = ['nombre', 'correo', 'telefono', 'contraseña', 'avatar'];
    protected $hidden = ['contraseña', 'remember_token'];
    public $timestamps = true;

    /**
     * Sobrescribe la columna usada para autenticación (por defecto es 'email').
     */
    public function getAuthIdentifierName()
    {
        return 'id'; // Aseguramos que use la clave primaria como identificador
    }

    /**
     * Devuelve la contraseña cifrada desde la columna personalizada.
     */
    public function getAuthPassword()
    {
        return $this->contraseña;
    }

    /**
     * Especifica la columna de email para restablecer la contraseña.
     */
    public function getEmailForPasswordReset()
    {
        return $this->correo;
    }

    // Relaciones
    public function contratos() { return $this->hasMany(Contrato::class, 'usuario_id'); }
    public function quejas() { return $this->hasMany(Queja::class, 'usuario_id'); }
    public function reportesProblema() { return $this->hasMany(ReporteProblema::class, 'usuario_id'); }
    public function evaluaciones() { return $this->hasMany(Evaluacion::class, 'usuario_id'); }
    public function solicitudesAlquiler() { return $this->hasMany(SolicitudAlquiler::class, 'usuario_id'); }
    public function estadosAlquiler() { return $this->hasMany(EstadoAlquiler::class, 'usuario_id'); }
    public function recordatoriosPago() { return $this->hasMany(RecordatorioPago::class, 'usuario_id'); }

    // Sobrescribe los métodos del trait HasRoles para usar tus nombres de tablas personalizados
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Rol::class, 'usuarios_roles', 'usuario_id', 'rol_id');
    }

    /**
     * Esta relación no se usará ya que la tabla no existe,
     * pero se mantiene como un método dummy para evitar errores en el código que la llama
     */
    public function permissions(): BelongsToMany
    {
        // Relación dummy que retornará siempre una colección vacía
        return $this->belongsToMany(Permiso::class, 'usuarios_roles', 'usuario_id', 'rol_id')
            ->whereRaw('1=0'); // Condición que siempre retorna falso
    }

    /**
     * Método personalizado para verificar roles
     */
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles()->where('nombre', $role)->exists();
        }

        if (is_array($role)) {
            return $this->roles()->whereIn('nombre', $role)->exists();
        }

        $roleCollection = collect($role);
        return $this->roles()->whereIn('nombre', $roleCollection->pluck('nombre'))->exists();
    }

    /**
     * Método personalizado para verificar permisos
     */
    public function hasPermissionTo($permission)
    {
        return $this->roles()->whereHas('permisos', function($query) use ($permission) {
            $query->where('nombre', $permission);
        })->exists();
    }

    /**
     * Sobrescribe el método can para compatibilidad con nuestro sistema personalizado
     */
    public function can($abilities, $arguments = [])
    {
        if (is_string($abilities)) {
            return $this->hasPermissionTo($abilities);
        }
        return parent::can($abilities, $arguments);
    }
}
