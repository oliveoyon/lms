<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    public function vtype()
    {
        return $this->belongsTo(TransVehicleType::class, 'vehicle_type_id', 'id');
    }

}
