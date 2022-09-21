<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapArea extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'paperId',
        'pageNo',
        'description',
        'connection',
        'x1',
        'y1',
        'x2',
        'y2',
        'edition'
   ];
}
