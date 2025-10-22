<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'company_name',
        'location',
        'salary',
        'type',
        'employer_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'employer_id');
    }
}
