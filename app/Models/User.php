<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
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

    protected $guard_name = 'api';
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(related: Role::class);
    }

    // Add these relationships to the existing User model
public function expenses()
{
    return $this->hasMany(Expense::class);
}

public function categories()
{
    return $this->hasMany(Category::class);
}

public function paymentMethods()
{
    return $this->hasMany(PaymentMethod::class);
}

public function budgets()
{
    return $this->hasMany(Budget::class);
}

public function incomes()
{
    return $this->hasMany(Income::class);
}

}
