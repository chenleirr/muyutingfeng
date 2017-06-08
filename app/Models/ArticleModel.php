<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleModel extends Model
{
    /**
     *  The table associated with the model.
     *
     *  @var string
     */
    protected $table = 'articles';

    protected $primaryKey = 'id';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'content',

        'read',
        'group',
        'status',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
