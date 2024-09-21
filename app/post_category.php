<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Cviebrock\EloquentSluggable\Sluggable;

class post_category extends Model
{
    use Sluggable;
    use LogsActivity;

    protected $table = 'post_category';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'icon',
        'parent_category_id' 
    ];

    protected static $logAttributes = [
        'name',
        'description',
        'icon',
        'parent_category_id' 
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function hasPosts()
    {
        return $this->hasMany('App\post', 'post_category_id');
    }

    public function hasParentCategory()
    {
        return $this->belongsTo('App\post_category', 'parent_category_id');
    }
}
