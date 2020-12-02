<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('stock', function ($query) {
            return $query->where('quantity', '>', 0);
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'quantity',
        'user_id',
        'status'
    ];

    /**
     * Seller of the product
     */
    public function seller()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Transactions of the product
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
