<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'nid',
        'phone',
        'vaccine_center_id',
        'registered_at',
        'status'
    ];

    public function vaccineCenter()
    {
        return $this->belongsTo(VaccineCenter::class, 'vaccine_center_id', 'id')->withDefault();
    }
}
