<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{

    protected $fillable = ['user_id', 'amount', 'source', 'date', 'frequency'];

    protected $casts = [
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
