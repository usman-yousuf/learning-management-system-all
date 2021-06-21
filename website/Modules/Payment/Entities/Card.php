<?php

namespace Modules\Payment\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Card extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'card_holder_id  ',
        'stripe_card_id',
        'card_holder_name',
        'brand',
        'last4',
        'exp_month',
        'exp_year',
        'country',
    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        static::created(function ($model) {
            // Do something after saving
        });
        parent::boot();

        // delete a course
        static::deleting(function ($model) {
            // $model->contents()->delete();
            // $model->handouts()->delete();
            // $model->outlines()->delete();
            // $model->slots()->delete();
            // $model->reviews()->delete();
            // $model->queries()->delete();
            // $model->enrolledStudents()->delete();
        });
    }

 
    public function cardHolder()
    {
        return $this->belongsTo(Profile::class, 'card_holder_id' , 'id')->orderBy('DESC');
    }

    public function Stripe ()
    {
        return $this->hasMany(PaymentHistory::class, 'stripe_card_id', 'id')->orderBy('id', 'DESC');
    }

 
}
