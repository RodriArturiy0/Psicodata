<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Modelo de Usuario Institucional PsicoData.
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $rol
 * @property string|null $cargo
 * @property bool $activo
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @method bool esPsicopedagogia()
 * @method bool esDireccion()
 * @method bool esDocente()
 * @method bool esAdmin()
 * @method bool puedeVerExpedientes()
 * @method bool puedeEditarExpedientes()
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
        'cargo',
        'activo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'activo' => 'boolean',
        ];
    }

    /**
     * Comprueba si el usuario tiene rol de Psicopedagogía.
     */
    public function esPsicopedagogia(): bool
    {
        return $this->rol === 'PSICOPEDAGOGIA';
    }

    /**
     * Comprueba si el usuario tiene rol de Dirección Escolar.
     */
    public function esDireccion(): bool
    {
        return $this->rol === 'DIRECCION';
    }

    /**
     * Comprueba si el usuario pertenece a la plantilla docente.
     */
    public function esDocente(): bool
    {
        return $this->rol === 'DOCENTE';
    }

    /**
     * Comprueba si el usuario tiene rol de Administrador de TI.
     */
    public function esAdmin(): bool
    {
        return $this->rol === 'ADMIN';
    }

    /**
     * Regla de negocio estricta:
     * Únicamente Psicopedagogía, Dirección y Administrador pueden consultar expedientes.
     * La plantilla docente queda estrictamente bloqueada por confidencialidad clínica.
     */
    public function puedeVerExpedientes(): bool
    {
        return in_array($this->rol, ['PSICOPEDAGOGIA', 'DIRECCION', 'ADMIN']);
    }

    /**
     * Solo Psicopedagogía y Administrador pueden alterar notas y diagnósticos clínicos.
     */
    public function puedeEditarExpedientes(): bool
    {
        return in_array($this->rol, ['PSICOPEDAGOGIA', 'ADMIN']);
    }

    /**
     * Permite gestionar ciclos escolares y brincado de grado.
     */
    public function puedeGestionarCiclos(): bool
    {
        return in_array($this->rol, ['PSICOPEDAGOGIA', 'DIRECCION', 'ADMIN']);
    }

    /**
     * Permite gestionar copias de seguridad locales (mysqldump).
     */
    public function puedeGenerarRespaldos(): bool
    {
        return in_array($this->rol, ['PSICOPEDAGOGIA', 'DIRECCION', 'ADMIN']);
    }
}
