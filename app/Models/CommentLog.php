<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace App\Models;

class CommentLog extends Model
{
    use Traits\CommentLogServiceTrait;
    use Traits\IsEnabledTrait;
    use Traits\FsidTrait;

    const STATE_DRAFT = 1;
    const STATE_UNDER_REVIEW = 2;
    const STATE_SUCCESS = 3;
    const STATE_FAILURE = 4;

    protected $casts = [
        'location_info' => 'json',
        'more_info' => 'json',
        'permissions' => 'json',
    ];

    protected $dates = [
        'submit_at',
    ];

    public function getFsidKey()
    {
        return 'hcid';
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class, 'comment_id', 'id');
    }

    public function parentComment()
    {
        return $this->belongsTo(Comment::class, 'parent_comment_id', 'id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }

    public function geotag()
    {
        return $this->belongsTo(Geotag::class, 'geotag_id', 'id');
    }

    public function fileUsages()
    {
        return $this->hasMany(FileUsage::class, 'table_id', 'id')->where('table_name', 'comment_logs')->where('table_column', 'id');
    }

    public function extendUsages()
    {
        return $this->hasMany(ExtendUsage::class, 'usage_id', 'id')->where('usage_type', ExtendUsage::TYPE_COMMENT_LOG);
    }
}
