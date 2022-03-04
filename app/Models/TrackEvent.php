<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\Cast\Object_;
use stdClass;

class TrackEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'track_id',
        'date_event',
        'event',
        'unit'
    ];

    protected $casts = [
        'unit' => 'array',
        'date_event' => 'datetime:d/m/Y h:m'
    ];

}
