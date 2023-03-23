<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the Apache-2.0 License.
 */

namespace App\Models;

class Archive extends Model
{
    use Traits\ArchiveServiceTrait;

    const TYPE_USER = 1;
    const TYPE_GROUP = 2;
    const TYPE_HASHTAG = 3;
    const TYPE_POST = 4;
    const TYPE_COMMENT = 5;

    use Traits\IsEnableTrait;

    protected $casts = [
        'options' => 'array',
    ];

    public function scopeType($query, int $type)
    {
        return $query->where('usage_type', $type);
    }
}
