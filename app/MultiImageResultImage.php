<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MultiImageResultImage extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'multi_image_result_images';

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
        'multi_image_result_id',
        'image_id',
        'selected',
        'duration'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the image of the multi image result image.
     */
    public function image()
    {
        return $this->belongsTo('App\Image');
    }

    /**
     * Get the multi image result of the multi image result image.
     */
    public function multiImageResult()
    {
        return $this->belongsTo('App\MultiImageResult');
    }
}
