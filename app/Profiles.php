<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profiles extends Model
{


    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['mediator',
                           'resume',
                           'credentials',
                           'especialties'];

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
    protected $casts = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];
}
