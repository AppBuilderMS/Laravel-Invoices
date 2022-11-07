<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class TranslatedPermission extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
