<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
 
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'string',
        'html',
        'user_id'
    ];
    
    public function user(){
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function labels(){
        return $this->belongsToMany('App\Models\Label', 'labelnotes');
    }


    use HasFactory;
}
