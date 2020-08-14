<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyInformation extends Model
{
    protected $table = 'company_informations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'cin', 'name','status','date_of_incorporation', 'reg_number',  'category',  'subcategory',  'class',  'roc_code','members','email','registered_office','listed', 'last_agm_date','balance_sheet_date'];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
}
