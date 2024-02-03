<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrRoute extends Model
{
    use HasFactory;
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'id');
    }
    public function stopage()
    {
        return $this->belongsTo(TransStopage::class, 'stopage_id', 'id');
    }
}
