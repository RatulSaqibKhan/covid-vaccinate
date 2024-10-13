<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaccineSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'vaccine_center_id',
        'scheduled_date',
        'slots_filled'
    ];

    public function vaccineCenter()
    {
        return $this->belongsTo(VaccineCenter::class, 'vaccine_center_id', 'id')->withDefault();
    }
}
