<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyDirector extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id', 'din', 'name','designation','date_of_appointment'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
}