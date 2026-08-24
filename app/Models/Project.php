<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['title', 'slug', 'description', 'client', 'completed_at', 'image', 'is_featured', 'sort_order'];
}
