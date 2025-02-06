<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;

    // Specify the table name if it's not "classes"
    protected $table = 'classes';

    // Specify the fillable fields for mass assignment
    protected $fillable = [
        'Descriptive Title',
        'Instructor',
    ];

    // You can also define relationships if needed (optional)
    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
