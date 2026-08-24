<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = ['client_name', 'client_company', 'content', 'rating', 'is_approved'];
}
