<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    protected $table = 'admin_logs';
    protected $fillable = ['performed_by','action','meta'];
}
