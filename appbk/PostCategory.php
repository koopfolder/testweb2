<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PostCategory extends Pivot
{
    protected $table = 'category_post';
}
