<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Cviebrock\EloquentSluggable\Sluggable;

class post extends Model
{
    use LogsActivity;
    use Sluggable;

    protected $table = 'post';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'post',
        'tags',
        'resume',
        'cover_image',
        'published_date',
        'unpublished_date',
        'post_category_id',
        'post_status_id',
        'author_id',
        'uuid',
        'gallery_path'
    ];

    protected static $logAttributes = [
        'title',
        'slug',
        'post',
        'tags',
        'resume',
        'cover_image',
        'published_date',
        'unpublished_date',
        'post_category_id',
        'post_status_id',
        'author_id',
        'uuid',
        'gallery_path'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['published_date', 'unpublished_date', 'created_at', 'updated_at'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function hasCategoria()
    {
        return $this->belongsTo('App\post_category', 'post_category_id');
    }

    public function hasEstado()
    {
        return $this->belongsTo('App\post_status', 'post_status_id');
    }

    public function hasAutor()
    {
        return $this->belongsTo('App\User', 'author_id');
    }
}
