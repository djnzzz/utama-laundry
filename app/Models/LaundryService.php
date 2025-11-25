<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaundryService extends Model
{
    protected $fillable = ['code','name','price_reguler','price_express'];
    protected $table = 'laundry_services';
    public $timestamps = true;
}
