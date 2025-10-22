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

    protected $hidden = [
        'employer_id',
        'created_at',
        'updated_at'
    ];

    public function employer()
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function applications(){
        return $this->hasMany(Application::class, 'offer_id');
    }
}
