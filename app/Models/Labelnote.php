<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Labelnote extends Model
{
    use HasFactory;
    protected $fillable = [
        'note_id',
        'label_id'
    ];
    
    public function note(){
        return $this->belongsTo('App\Models\Note','note_id');
    }
    public function label(){
        return $this->belongsTo('App\Models\Label','label_id');
    }
}
