<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SingleImageResult extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'single_image_results';

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
        'single_image_question_id',
        'image_id',
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
     * Get the profile of the single image result.
     */
    public function profile()
    {
        return $this->belongsTo('App\Profile');
    }

    /**
     * Get the subcategory of the single image result.
     */
    public function subcategory()
    {
        return $this->belongsTo('App\Subcategory');
    }

    /**
     * Get the single image result coords of the single image result.
     */
    public function singleImageResultCoords()
    {
        return $this->hasMany('App\SingleImageResultCoord');
    }
}
