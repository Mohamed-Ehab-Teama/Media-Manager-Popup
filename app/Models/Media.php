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
    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    
    public function getURL()
    {
        if ($this->disk == 'S3' || $this->disk == 's3') {
            return $this->url;
        }
        return config('app.url') . '/' . $this->path;
        // return $this->url ?? config('app.url') . '/' . $this->path;
    }
}
