<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'professional_id',
        'account_id',
        'name',
        'modality',
    ];
}