<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAttendence extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'employee_attendances';
    public static function storeAttendance($data)
    {
        EmployeeAttendence::insert($data);
    }


    public function user()
    {
        return $this->belongsTo(User::class,'employee_id','id')
            ->select('id','name','salary');
    }
}
