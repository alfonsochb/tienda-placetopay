<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    public $timestamps = true;

    protected $casts = [];

    protected $fillable = [
        'id',
		'client_id',
		'product_id',
		'customer_name',
		'customer_email',
		'customer_mobile',
		'product_name',
		'product_cost',
		'reference',
		'request_id',
		'pass_message',
		'process_url',
		'status',
        'create_at',
        'update_at'
    ];
}
