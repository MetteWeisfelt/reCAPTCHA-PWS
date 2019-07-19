<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ControlImage extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'control_images';

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
        'path',
        'name',
        'height',
        'width'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the category of the image.
     */
    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    /**
     * Get the multi image result images of the image.
     */
    public function multiImageResultImages()
    {
        return $this->hasMany('App\MultiImageResultImage');
    }


}
