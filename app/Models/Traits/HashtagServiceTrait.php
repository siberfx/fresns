<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace App\Models\Traits;

use App\Helpers\ConfigHelper;
use App\Helpers\FileHelper;
use App\Helpers\StrHelper;

trait HashtagServiceTrait
{
    public function getHashtagInfo(?string $langTag = null): array
    {
        $hashtagData = $this;

        $configKeys = ConfigHelper::fresnsConfigByItemKeys([
            'site_url',
            'website_hashtag_detail_path',
            'hashtag_like_public_count',
            'hashtag_dislike_public_count',
            'hashtag_follow_public_count',
            'hashtag_block_public_count',
        ]);

        // https://example.com/hashtag/{htid}
        $hashtagUrl = $configKeys['site_url'].'/'.$configKeys['website_hashtag_detail_path'].'/'.$hashtagData->slug;

        $info['htid'] = $hashtagData->slug;
        $info['url'] = $configKeys['site_url'] ? $hashtagUrl : null;
        $info['name'] = $hashtagData->name;
        $info['cover'] = FileHelper::fresnsFileUrlByTableColumn($hashtagData->cover_file_id, $hashtagData->cover_file_url);
        $info['description'] = StrHelper::languageContent($hashtagData->description, $langTag);
        $info['viewCount'] = $hashtagData->view_count;
        $info['likeCount'] = $configKeys['hashtag_like_public_count'] ? $hashtagData->like_count : null;
        $info['dislikeCount'] = $configKeys['hashtag_dislike_public_count'] ? $hashtagData->dislike_count : null;
        $info['followCount'] = $configKeys['hashtag_follow_public_count'] ? $hashtagData->follow_count : null;
        $info['blockCount'] = $configKeys['hashtag_block_public_count'] ? $hashtagData->block_count : null;
        $info['postCount'] = $hashtagData->post_count;
        $info['postDigestCount'] = $hashtagData->post_digest_count;
        $info['commentCount'] = $hashtagData->comment_count;
        $info['commentDigestCount'] = $hashtagData->comment_digest_count;
        $info['createdDatetime'] = $hashtagData->created_at;
        $info['createdTimeAgo'] = null;
        $info['lastPublishPostDateTime'] = $hashtagData->last_post_at;
        $info['lastPublishPostTimeAgo'] = null;
        $info['lastPublishCommentDateTime'] = $hashtagData->last_comment_at;
        $info['lastPublishCommentTimeAgo'] = null;
        $info['moreInfo'] = $hashtagData->more_info;

        return $info;
    }

    public function getCoverUrl(): ?string
    {
        return FileHelper::fresnsFileUrlByTableColumn($this->cover_file_id, $this->cover_file_url);
    }
}
