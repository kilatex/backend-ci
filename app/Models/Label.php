<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'content',
        'user_id'
    ];
    
    public function user(){
        return $this->belongsTo('App\Models\User','user_id');
    }
    
    public function notes(){
        return $this->belongsToMany('App\Models\Note','labelnotes');
    }
}
