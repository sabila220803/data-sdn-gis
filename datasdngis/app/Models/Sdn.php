<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Sdn extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'sdns';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'slug',
        'nama',
        'alamat',
        'latitude',
        'longitude',
        'image',
    ];
}
