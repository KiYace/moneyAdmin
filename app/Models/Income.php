<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'incomes';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];
    protected $casts = [
      'tags_id' => 'json',
      'source' => 'string'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function user()
    {
      return $this->belongsTo('App\Models\Appuser', 'user_id', 'id');
    }

    public function source()
    {
      return $this->belongsTo('App\Models\Source', 'source', 'id');
    }

    public function bill()
    {
      return $this->belongsTo('App\Models\Bill', 'bill_id', 'id');
    }

    public function goal()
    {
      return $this->belongsTo('App\Models\Goal', 'goal_id', 'id');
    }

    public function tag()
    {
      return $this->hasMany('App\Models\Tag', 'tags_id', 'id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
