<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category_service extends Model
{
    use HasFactory;
    protected $table = 'category_service';
    protected $fillable = ['name'];

    public function services()
    {
        return $this->hasMany(Service::class, 'category_id_sv'); // Định nghĩa quan hệ với model Service
    }
}
