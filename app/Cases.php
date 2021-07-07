<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cases extends Model  
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cases';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['created_at',
                           'updated_at',
                           'status',
                           'amount',
                           'accepted',
                           'agreement',
                           'counter_agreement',
                           'counter_date',
                           'dateAcepted',
                           'opener',
                           'recipient',
                           'mediator',
                           'notes',
                           'inverted',
                           'initialData'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = ['accepted' => 'boolean','inverted' => 'boolean'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'dateAcepted'];

}
