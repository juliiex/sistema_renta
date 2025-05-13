<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @OA\Schema(
 *     schema="Usuario",
 *     title="Usuario",
 *     type="object",
 *     required={"nombre", "correo", "telefono", "contraseña"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nombre", type="string", example="Juan Pérez"),
 *     @OA\Property(property="correo", type="string", format="email", example="juan@example.com"),
 *     @OA\Property(property="telefono", type="string", example="123456789"),
 *     @OA\Property(property="contraseña", type="string", example="********"),
 *     @OA\Property(property="avatar", type="string", example="avatar.jpg"),
 * )
 */
class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuario'; // Tabla personalizada
    protected $fillable = ['nombre', 'correo', 'telefono', 'contraseña', 'avatar'];
    public $timestamps = true; // Timestamps activados

    /**
     * Sobrescribe la columna usada para autenticación (por defecto es 'email').
     */
    public function getAuthIdentifierName()
    {
        return 'correo';
    }

    /**
     * Devuelve la contraseña cifrada desde la columna personalizada.
     */
    public function getAuthPassword()
    {
        return $this->contraseña;
    }

    // Relaciones (sin cambios)
    public function contratos() { return $this->hasMany(Contrato::class, 'usuario_id'); }
    public function quejas() { return $this->hasMany(Queja::class, 'usuario_id'); }
    public function reportesProblema() { return $this->hasMany(ReporteProblema::class, 'usuario_id'); }
    public function evaluaciones() { return $this->hasMany(Evaluacion::class, 'usuario_id'); }
    public function solicitudesAlquiler() { return $this->hasMany(SolicitudAlquiler::class, 'usuario_id'); }
    public function estadosAlquiler() { return $this->hasMany(EstadoAlquiler::class, 'usuario_id'); }
    public function recordatoriosPago() { return $this->hasMany(RecordatorioPago::class, 'usuario_id'); }

    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'usuario_roles', 'usuario_id', 'rol_id');
    }
}
