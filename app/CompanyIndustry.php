<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyIndustry extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id', 'section', 'division','main_group','main_class'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];//
}
