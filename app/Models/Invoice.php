<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $guarded = [];
    protected $dates = ['deleted_at'];

    public function department() {
        return $this->belongsTo(Department::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function attachments (){
        return $this->hasMany(Invoice_attatchments::class);
    }
}
