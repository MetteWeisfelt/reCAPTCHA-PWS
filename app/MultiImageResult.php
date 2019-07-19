<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MultiImageResult extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'multi_image_results';

    /**
     * The primary key associated with the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'profile_id',
        'passed'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the profile of the multi image result.
     */
    public function profile()
    {
        return $this->belongsTo('App\Profile');
    }

    /**
     * Get the category of the multi image result.
     */
    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    /**
     * Get the multi image result images of the multi image result.
     */
    public function multiImageResultImages()
    {
        return $this->hasMany('App\MultiImageResultImage');
    }
}
