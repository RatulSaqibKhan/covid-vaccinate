<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaccineCenter extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'daily_capacity',
        'available_date'
    ];

    public function users() {
        return $this->hasMany(User::class, 'vacchine_center_id',  'id');
    }

    public function vaccineSchedules() {
        return $this->hasMany(VaccineSchedule::class, 'vacchine_center_id',  'id');
    }
}
