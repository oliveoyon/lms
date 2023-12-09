<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    public function event_type()
    {
        return $this->belongsTo(EventType::class, 'event_type_id');
    }
}
