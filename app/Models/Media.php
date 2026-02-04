<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = 'media';
    protected $guarded = ['id'];


    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }






    // ========== Helpers
    public function getURL()
    {
        return config('app.url') . '/' . $this->path;
    }
}
