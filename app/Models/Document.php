<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    protected $fillable = [
        'original_name',
        'stored_name',
        'folder',
        'mime_type',
        'size',
    ];

    protected $appends = ['url', 'download_url', 'view_url'];

    public function getDisk(): string
    {
        return 'public';
    }

    public function getPathAttribute(): string
    {
        return "{$this->folder}/{$this->stored_name}";
    }

    public function getUrlAttribute(): string
    {
        // public URL (requires storage:link)
        return Storage::disk($this->getDisk())->url($this->path);
    }

    public function getDownloadUrlAttribute(): string
    {
        return route('documents.download', $this->id);
    }

    public function getViewUrlAttribute(): string
    {
        return route('documents.view', $this->id);
    }
}
