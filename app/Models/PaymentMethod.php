<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentMethod extends Model
{
     use HasFactory;
    protected $fillable = ['user_id', 'name', 'is_system_method'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
