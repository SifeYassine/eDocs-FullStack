<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'description',
    ];

    // Permission can have many users
    public function users()
    {
        return $this->hasMany(User::class, 'permission_user', 'user_id', 'permission_id');
    }
}