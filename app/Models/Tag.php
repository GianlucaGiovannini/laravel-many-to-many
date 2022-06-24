<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    public function tags(): BelongsToMany
    {

        return $this->belongsToMany(Post::class);
    }
}
