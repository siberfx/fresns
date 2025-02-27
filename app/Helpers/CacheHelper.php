<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace App\Helpers;

use App\Models\App;
use App\Models\Config;
use App\Models\File;
use App\Models\FileUsage;
use App\Models\Group;
use App\Models\Seo;
use App\Models\TempCallbackContent;
use App\Models\TempVerifyCode;
use App\Utilities\InteractionUtility;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CacheHelper
{
    const NULL_CACHE_KEY_PREFIX = 'null_key_';
    const NULL_CACHE_COUNT = 2;

    // cache time
    public static function fresnsCacheTimeByFileType(?int $fileType = null, ?int $specificMinutes = null, ?int $subtractMinutes = 1): Carbon
    {
        if (empty($fileType)) {
            $digital = rand(12, 72);

            return now()->addHours($digital);
        }

        if ($fileType != File::TYPE_ALL) {
            $fileConfig = FileHelper::fresnsFileStorageConfigByType($fileType);

            if (! $fileConfig['temporaryUrlStatus']) {
                if ($specificMinutes) {
                    return now()->addMinutes($specificMinutes);
                }

                $digital = rand(72, 168);

                return now()->addHours($digital);
            }

            $cacheTime = now()->addMinutes($fileConfig['temporaryUrlExpiration'] - $subtractMinutes);

            return $cacheTime;
        }

        $imageConfig = FileHelper::fresnsFileStorageConfigByType(File::TYPE_IMAGE);
        $videoConfig = FileHelper::fresnsFileStorageConfigByType(File::TYPE_VIDEO);
        $audioConfig = FileHelper::fresnsFileStorageConfigByType(File::TYPE_AUDIO);
        $documentConfig = FileHelper::fresnsFileStorageConfigByType(File::TYPE_DOCUMENT);

        $temporaryUrlExpiration = [
            $imageConfig['temporaryUrlStatus'] ? $imageConfig['temporaryUrlExpiration'] : 0,
            $videoConfig['temporaryUrlStatus'] ? $videoConfig['temporaryUrlExpiration'] : 0,
            $audioConfig['temporaryUrlStatus'] ? $audioConfig['temporaryUrlExpiration'] : 0,
            $documentConfig['temporaryUrlStatus'] ? $documentConfig['temporaryUrlExpiration'] : 0,
        ];

        $newTemporaryUrlExpiration = array_filter($temporaryUrlExpiration);

        if (empty($newTemporaryUrlExpiration)) {
            if ($specificMinutes) {
                return now()->addMinutes($specificMinutes);
            }

            $digital = rand(6, 72);

            return now()->addHours($digital);
        }

        $minTemporaryUrlExpiration = min($newTemporaryUrlExpiration);

        $cacheTime = now()->addMinutes($minTemporaryUrlExpiration - $subtractMinutes);

        return $cacheTime;
    }

    // get null cache key
    public static function getNullCacheKey(string $cacheKey): string
    {
        return CacheHelper::NULL_CACHE_KEY_PREFIX.$cacheKey;
    }

    // put null cache count
    public static function putNullCacheCount(string $cacheKey, ?int $cacheMinutes = 1): void
    {
        CacheHelper::forgetFresnsKey($cacheKey);

        $nullCacheKey = CacheHelper::getNullCacheKey($cacheKey);
        $cacheTag = 'fresnsNullCount';

        $currentCacheKeyNullNum = (int) CacheHelper::get($nullCacheKey, $cacheTag) ?? 0;

        $now = $cacheMinutes ? now()->addMinutes($cacheMinutes) : CacheHelper::fresnsCacheTimeByFileType();

        if (Cache::supportsTags()) {
            $cacheTags = (array) $cacheTag;

            Cache::tags($cacheTags)->put($nullCacheKey, ++$currentCacheKeyNullNum, $now);
        } else {
            Cache::put($nullCacheKey, ++$currentCacheKeyNullNum, $now);
        }

        CacheHelper::addCacheItems($cacheKey, $cacheTag);
    }

    // is known to be empty
    public static function isKnownEmpty(string $cacheKey): bool
    {
        $nullCacheKey = CacheHelper::getNullCacheKey($cacheKey);

        $nullCacheCount = CacheHelper::get($nullCacheKey, 'fresnsNullCount');

        // null cache count
        if ($nullCacheCount > CacheHelper::NULL_CACHE_COUNT) {
            return true;
        }

        return false;
    }

    // cache get
    public static function get(string $cacheKey, mixed $cacheTags = null): mixed
    {
        $cacheTags = (array) $cacheTags;

        if (Cache::supportsTags() && $cacheTags) {
            return Cache::tags($cacheTags)->get($cacheKey);
        }

        return Cache::get($cacheKey);
    }

    // cache put
    public static function put(mixed $cacheData, string $cacheKey, string|array $cacheTags, Carbon|int|null $cacheTime = null, ?int $nullCacheMinutes = null): void
    {
        $cacheTags = (array) $cacheTags;

        // null cache count
        if (empty($cacheData)) {
            CacheHelper::putNullCacheCount($cacheKey, $nullCacheMinutes);

            return;
        }

        if (is_int($cacheTime)) {
            $cacheTimeCarbon = now()->addMinutes($cacheTime);
        } elseif ($cacheTime instanceof Carbon) {
            $cacheTimeCarbon = $cacheTime;
        } else {
            $cacheTimeCarbon = CacheHelper::fresnsCacheTimeByFileType();
        }

        if (Cache::supportsTags() && $cacheTags) {
            Cache::tags($cacheTags)->put($cacheKey, $cacheData, $cacheTimeCarbon);
        } else {
            Cache::put($cacheKey, $cacheData, $cacheTimeCarbon);
        }

        CacheHelper::addCacheItems($cacheKey, $cacheTags);

        $cacheTagList = Cache::get('fresns_cache_tags') ?? [];
        foreach ($cacheTags as $tag) {
            $datetime = date('Y-m-d H:i:s');

            $newTagList = Arr::add($cacheTagList, $tag, $datetime);

            Cache::forever('fresns_cache_tags', $newTagList);
        }
    }

    // add cache items
    public static function addCacheItems(string $cacheKey, mixed $cacheTags = null): void
    {
        if (empty($cacheTags)) {
            return;
        }

        $cacheTags = (array) $cacheTags;
        $tags = [
            'fresnsNullCount',
            'fresnsSystems',
            'fresnsConfigs',
            'fresnsExtensions',
            'fresnsWebConfigs',
        ];

        foreach ($cacheTags as $tag) {
            if (in_array($tag, $tags)) {
                $cacheItems = Cache::get($tag) ?? [];

                $datetime = date('Y-m-d H:i:s');

                $newCacheItems = Arr::add($cacheItems, $cacheKey, $datetime);

                Cache::forever($tag, $newCacheItems);
            }
        }
    }

    /**
     * clear all cache.
     */
    public static function clearAllCache(): void
    {
        Artisan::call('cache:clear');
        Cache::flush();

        Artisan::call('optimize:clear');
        Artisan::call('config:cache'); // config:clear and cache
        Artisan::call('event:cache'); // event:clear and cache
        Artisan::call('view:cache'); // view:clear and cache
        Artisan::call('route:cache'); // route:clear and cache
        Artisan::call('schedule:clear-cache');

        DB::table('failed_jobs')->delete();

        // time of the latest cache
        Config::updateOrCreate([
            'item_key' => 'cache_datetime',
        ], [
            'item_value' => now(),
            'item_type' => 'string',
            'is_multilingual' => 0,
            'is_custom' => 0,
            'is_api' => 1,
        ]);
    }

    /**
     * clear config cache.
     */
    public static function clearConfigCache(string $cacheType): void
    {
        // system
        if ($cacheType == 'fresnsSystem') {
            CacheHelper::forgetFresnsTag('fresnsSystems');

            Artisan::call('optimize:clear');

            Artisan::call('config:cache'); // config:clear and cache
            Artisan::call('event:cache'); // event:clear and cache
            Artisan::call('view:cache'); // view:clear and cache
            Artisan::call('route:cache'); // route:clear and cache
        }

        // config
        if ($cacheType == 'fresnsConfig') {
            CacheHelper::forgetFresnsTag('fresnsConfigs');
            CacheHelper::forgetFresnsTag('fresnsWebConfigs');

            // time of the latest cache
            Config::updateOrCreate([
                'item_key' => 'cache_datetime',
            ], [
                'item_value' => now(),
                'item_type' => 'string',
                'is_multilingual' => 0,
                'is_custom' => 0,
                'is_api' => 1,
            ]);
        }

        // extend
        if ($cacheType == 'fresnsExtend') {
            CacheHelper::forgetFresnsTag('fresnsExtensions');

            $apps = App::all();
            foreach ($apps as $app) {
                $fskey = $app->fskey;

                CacheHelper::forgetFresnsKey("fresns_plugin_host_{$fskey}", 'fresnsConfigs');
                CacheHelper::forgetFresnsKey("fresns_plugin_url_{$fskey}", 'fresnsConfigs');
                CacheHelper::forgetFresnsKey("fresns_plugin_version_{$fskey}", 'fresnsConfigs');
            }
        }

        // view
        if ($cacheType == 'fresnsView') {
            Artisan::call('view:cache'); // view:clear and cache
        }

        // route
        if ($cacheType == 'fresnsRoute') {
            Artisan::call('route:cache'); // route:clear and cache
        }

        // event
        if ($cacheType == 'fresnsEvent') {
            Artisan::call('event:cache'); // event:clear and cache
        }

        // schedule
        if ($cacheType == 'fresnsSchedule') {
            Artisan::call('schedule:clear-cache');

            DB::table('failed_jobs')->delete();
        }

        // temporary data
        if ($cacheType == 'fresnsTemporaryData') {
            $aDayAgo = now()->subDay();

            TempCallbackContent::where('created_at', '<', $aDayAgo)->chunk(100, function ($contents) {
                foreach ($contents as $content) {
                    $expiryDate = $content->created_at->addDays($content->retention_days);

                    if (now()->greaterThan($expiryDate)) {
                        $content->forceDelete();
                    }
                }
            });

            TempVerifyCode::where('created_at', '<', $aDayAgo)->chunk(100, function ($contents) {
                foreach ($contents as $content) {
                    $content->forceDelete();
                }
            });
        }
    }

    /**
     * clear data cache.
     */
    public static function clearDataCache(string $cacheType, int|string $fsid): void
    {
        $model = PrimaryHelper::fresnsModelByFsid($cacheType, $fsid);
        if (empty($model)) {
            return;
        }

        $id = $model->id;

        $usageType = match ($cacheType) {
            'user' => Seo::TYPE_USER,
            'group' => Seo::TYPE_GROUP,
            'hashtag' => Seo::TYPE_HASHTAG,
            'geotag' => Seo::TYPE_GEOTAG,
            'post' => Seo::TYPE_POST,
            'comment' => Seo::TYPE_COMMENT,
            default => null,
        };

        $cacheTag = match ($cacheType) {
            'user' => 'fresnsUsers',
            'group' => 'fresnsGroups',
            'hashtag' => 'fresnsHashtags',
            'geotag' => 'fresnsGeotags',
            'post' => 'fresnsPosts',
            'comment' => 'fresnsComments',
            default => null,
        };

        CacheHelper::forgetFresnsModel($cacheType, $fsid);
        CacheHelper::forgetFresnsKey("fresns_model_seo_{$usageType}_{$id}", 'fresnsSeo');
        CacheHelper::forgetFresnsMultilingual("fresns_detail_{$cacheType}_{$id}", $cacheTag);

        switch ($cacheType) {
            case 'user':
                CacheHelper::forgetFresnsUser($id, $model->uid);

                $account = PrimaryHelper::fresnsModelById('account', $model->account_id);
                CacheHelper::forgetFresnsAccount($account->aid);
                break;

            case 'group':
                CacheHelper::forgetFresnsKeys([
                    'fresns_group_private_ids',
                    "fresns_group_subgroups_ids_{$id}",
                    "fresns_group_subgroups_ids_{$fsid}",
                ], 'fresnsGroups');

                $types = Group::distinct()->pluck('type');
                foreach ($types as $type) {
                    CacheHelper::forgetFresnsKey("fresns_group_{$type}_tree_by_guest", 'fresnsGroups');
                }
                CacheHelper::forgetFresnsKey('fresns_group_all_tree_by_guest', 'fresnsGroups');

                CacheHelper::forgetFresnsMultilingual("fresns_group_{$id}_extends_by_everyone", ['fresnsExtensions', 'fresnsGroups']);
                CacheHelper::forgetFresnsMultilingual("fresns_group_{$id}_extends_by_role", ['fresnsExtensions', 'fresnsGroups']);
                CacheHelper::forgetFresnsMultilingual("fresns_group_{$id}_extends_by_group_admin", ['fresnsExtensions', 'fresnsGroups']);
                break;

            case 'post':
                CacheHelper::forgetFresnsMultilingual("fresns_detail_post_{$id}_preview_like_users", 'fresnsPosts');
                CacheHelper::forgetFresnsMultilingual("fresns_detail_post_{$id}_preview_comments", 'fresnsPosts');
                break;

            case 'comment':
                CacheHelper::forgetFresnsMultilingual("fresns_detail_comment_{$id}_preview_like_users", 'fresnsComments');
                CacheHelper::forgetFresnsMultilingual("fresns_detail_comment_{$id}_preview_comments", 'fresnsComments');
                break;

            case 'file':
                CacheHelper::forgetFresnsKey("fresns_file_info_{$id}", 'fresnsFiles');
                CacheHelper::forgetFresnsKey("fresns_file_info_{$fsid}", 'fresnsFiles');
                CacheHelper::forgetFresnsFileUsage($fsid);
                break;
        }
    }

    /**
     * forget fresns tag.
     */
    public static function forgetFresnsTag(string $tag): void
    {
        if (Cache::supportsTags()) {
            Cache::tags($tag)->flush();

            return;
        }

        $tags = [
            'fresnsNullCount',
            'fresnsSystems',
            'fresnsConfigs',
            'fresnsExtensions',
            'fresnsWebConfigs',
        ];

        if (in_array($tag, $tags)) {
            $keyArr = Cache::get($tag) ?? [];

            foreach ($keyArr as $key => $datetime) {
                Cache::forget($key);
            }

            Cache::forget($tag);
        }
    }

    /**
     * forget fresns key.
     */
    public static function forgetFresnsKey(string $cacheKey, mixed $cacheTags = null): void
    {
        $nullCacheKey = CacheHelper::getNullCacheKey($cacheKey);
        $nullCacheTag = ['fresnsNullCount'];

        if (Cache::supportsTags() && $cacheTags) {
            $cacheTags = (array) $cacheTags;

            Cache::tags($cacheTags)->forget($cacheKey);
            Cache::tags($nullCacheTag)->forget($nullCacheKey);
        } else {
            Cache::forget($cacheKey);
            Cache::forget($nullCacheKey);
        }
    }

    /**
     * forget fresns keys.
     */
    public static function forgetFresnsKeys(array $cacheKeys, mixed $cacheTags = null): void
    {
        $nullCacheTag = ['fresnsNullCount'];

        if (Cache::supportsTags()) {
            $cacheTags = (array) $cacheTags;

            foreach ($cacheKeys as $key) {
                $nullCacheKey = CacheHelper::getNullCacheKey($key);

                Cache::tags($cacheTags)->forget($key);
                Cache::tags($nullCacheTag)->forget($nullCacheKey);
            }
        } else {
            foreach ($cacheKeys as $key) {
                $nullCacheKey = CacheHelper::getNullCacheKey($key);

                Cache::forget($key);
                Cache::forget($nullCacheKey);
            }
        }
    }

    /**
     * forget fresns multilingual info.
     */
    public static function forgetFresnsMultilingual(string $cacheName, mixed $cacheTags = null): void
    {
        $langTagArr = ConfigHelper::fresnsConfigLangTags();

        foreach ($langTagArr as $langTag) {
            $cacheKey = "{$cacheName}_{$langTag}";

            CacheHelper::forgetFresnsKey($cacheKey, $cacheTags);
        }
    }

    /**
     * forget fresns config keys.
     */
    public static function forgetFresnsConfigs(mixed $itemKeys): void
    {
        $itemKeys = (array) $itemKeys;

        foreach ($itemKeys as $key) {
            CacheHelper::forgetFresnsModel('config', $key);
        }

        CacheHelper::forgetFresnsMultilingual('fresns_api_configs', 'fresnsConfigs');
    }

    /**
     * forget fresns model.
     */
    public static function forgetFresnsModel(string $modelName, int|string $fsid): void
    {
        $cacheTag = match ($modelName) {
            'config' => 'fresnsConfigs',
            'key' => 'fresnsSystems',
            'account' => 'fresnsAccounts',
            'user' => 'fresnsUsers',
            'group' => 'fresnsGroups',
            'hashtag' => 'fresnsHashtags',
            'geotag' => 'fresnsGeotags',
            'post' => 'fresnsPosts',
            'comment' => 'fresnsComments',
            'file' => 'fresnsFiles',
            'extend' => 'fresnsExtends',
            'archive' => 'fresnsArchives',
            'postLog' => 'fresnsPosts',
            'commentLog' => 'fresnsComments',
            'operation' => 'fresnsOperations',
            default => 'fresnsModels',
        };

        // user model
        if ($modelName == 'user') {
            if (StrHelper::isPureInt($fsid)) {
                $model = PrimaryHelper::fresnsModelById('user', $fsid);

                $fsidModel = PrimaryHelper::fresnsModelByFsid('user', $fsid);
            } else {
                $model = PrimaryHelper::fresnsModelByFsid('user', $fsid);

                $fsidModel = null;
            }

            CacheHelper::forgetFresnsKeys([
                "fresns_model_user_{$model?->id}",
                "fresns_model_user_{$model?->uid}_by_fsid",
                "fresns_model_user_{$model?->username}_by_fsid",
                "fresns_model_user_{$fsidModel?->id}",
                "fresns_model_user_{$fsidModel?->uid}_by_fsid",
                "fresns_model_user_{$fsidModel?->username}_by_fsid",
            ], $cacheTag);

            return;
        }

        // others
        if (StrHelper::isPureInt($fsid)) {
            $model = PrimaryHelper::fresnsModelById($modelName, $fsid);

            $modelFsid = match ($modelName) {
                'config' => $model?->item_key,
                'key' => $model?->app_id,
                'account' => $model?->aid,
                'user' => $model?->uid,
                'group' => $model?->aid,
                'hashtag' => $model?->slug,
                'geotag' => $model?->gtid,
                'post' => $model?->pid,
                'comment' => $model?->cid,
                'file' => $model?->fid,
                'extend' => $model?->eid,
                'archive' => $model?->code,

                default => null,
            };

            $fsidCacheKey = "fresns_model_{$modelName}_{$modelFsid}";
            $idCacheKey = "fresns_model_{$modelName}_{$fsid}";
        } else {
            $model = PrimaryHelper::fresnsModelByFsid($modelName, $fsid);

            $fsidCacheKey = "fresns_model_{$modelName}_{$fsid}";
            $idCacheKey = "fresns_model_{$modelName}_{$model?->id}";
        }

        CacheHelper::forgetFresnsKeys([$fsidCacheKey, $idCacheKey], $cacheTag);
    }

    /**
     * forget fresns file usage.
     */
    public static function forgetFresnsFileUsage(int|string $fileIdOrFid): void
    {
        if (StrHelper::isPureInt($fileIdOrFid)) {
            $fileId = (int) $fileIdOrFid;
        } else {
            $fileId = PrimaryHelper::fresnsPrimaryId('file', $fileIdOrFid);
        }

        if (empty($fileId)) {
            return;
        }

        CacheHelper::forgetFresnsModel('file', $fileId);

        $fileUsages = FileUsage::where('file_id', $fileId)->get();

        foreach ($fileUsages as $usage) {
            switch ($usage->usage_type) {
                case FileUsage::TYPE_POST:
                    $post = PrimaryHelper::fresnsModelById('post', $usage->table_id);

                    CacheHelper::forgetFresnsMultilingual("fresns_detail_post_{$post?->id}", 'fresnsPosts');
                    break;

                case FileUsage::TYPE_COMMENT:
                    $comment = PrimaryHelper::fresnsModelById('comment', $usage->table_id);

                    CacheHelper::forgetFresnsMultilingual("fresns_detail_comment_{$comment?->id}", 'fresnsComments');
                    break;
            }
        }
    }

    // forget fresns account
    public static function forgetFresnsAccount(?string $aid = null): void
    {
        if (empty($aid)) {
            return;
        }

        $id = PrimaryHelper::fresnsPrimaryId('account', $aid);

        CacheHelper::forgetFresnsModel('account', $aid);

        CacheHelper::forgetFresnsMultilingual("fresns_detail_account_{$id}", 'fresnsAccounts');

        CacheHelper::forgetFresnsMultilingual("fresns_web_account_{$aid}", 'fresnsWeb');
    }

    // forget fresns user
    public static function forgetFresnsUser(?int $userId = null, ?int $uid = null): void
    {
        if (empty($userId) && empty($uid)) {
            return;
        }

        $id = $userId ?? PrimaryHelper::fresnsPrimaryId('user', $uid);

        CacheHelper::forgetFresnsModel('user', $uid);

        CacheHelper::forgetFresnsMultilingual("fresns_detail_user_{$id}", 'fresnsUsers');
        CacheHelper::forgetFresnsKey("fresns_detail_user_stats_{$id}", 'fresnsUsers');

        $usageType = Seo::TYPE_USER;
        CacheHelper::forgetFresnsKey("fresns_model_seo_{$usageType}_{$id}", 'fresnsSeo');

        $types = Group::distinct()->pluck('type');
        foreach ($types as $type) {
            CacheHelper::forgetFresnsKey("fresns_group_{$type}_tree_by_user_{$id}", ['fresnsGroups', 'fresnsUsers']);
        }
        CacheHelper::forgetFresnsKey("fresns_group_all_tree_by_user_{$id}", ['fresnsGroups', 'fresnsUsers']);

        CacheHelper::forgetFresnsKey("fresns_group_list_filter_ids_by_user_{$id}", ['fresnsGroups', 'fresnsUsers']);
        CacheHelper::forgetFresnsKey("fresns_group_content_filter_ids_by_user_{$id}", ['fresnsGroups', 'fresnsUsers']);

        CacheHelper::forgetFresnsKey("fresns_user_activity_{$uid}", 'fresnsUsers');
        CacheHelper::forgetFresnsKey("fresns_user_{$id}_main_role", 'fresnsUsers');
        CacheHelper::forgetFresnsKey("fresns_user_{$id}_roles", 'fresnsUsers');

        CacheHelper::forgetFresnsKey("fresns_user_overview_conversations_{$uid}", 'fresnsUsers');
        CacheHelper::forgetFresnsKey("fresns_user_overview_notifications_{$uid}", 'fresnsUsers');
        CacheHelper::forgetFresnsKey("fresns_user_overview_drafts_{$uid}", 'fresnsUsers');

        $apps = App::all();
        foreach ($apps as $app) {
            CacheHelper::forgetFresnsKey("fresns_app_badge_{$app->fskey}_{$id}", 'fresnsUsers');
        }

        CacheHelper::forgetFresnsMultilingual("fresns_publish_post_config_{$id}", 'fresnsUsers');
        CacheHelper::forgetFresnsMultilingual("fresns_publish_comment_config_{$id}", 'fresnsUsers');

        CacheHelper::forgetFresnsMultilingual("fresns_web_user_{$uid}", 'fresnsWeb');
        CacheHelper::forgetFresnsMultilingual("fresns_web_user_overview_{$uid}", 'fresnsWeb');
        CacheHelper::forgetFresnsMultilingual("fresns_web_channels_{$uid}", 'fresnsWeb');
    }

    /**
     * forget fresns interaction.
     */
    public static function forgetFresnsInteraction(int $type, int $id, int $userId): void
    {
        $cacheTag = match ($type) {
            InteractionUtility::TYPE_USER => 'fresnsUsers',
            InteractionUtility::TYPE_GROUP => 'fresnsGroups',
            InteractionUtility::TYPE_HASHTAG => 'fresnsHashtags',
            InteractionUtility::TYPE_GEOTAG => 'fresnsGeotags',
            InteractionUtility::TYPE_POST => 'fresnsPosts',
            InteractionUtility::TYPE_COMMENT => 'fresnsComments',
        };

        CacheHelper::forgetFresnsKey("fresns_user_follow_{$type}_ids_by_{$userId}", $cacheTag);
        CacheHelper::forgetFresnsKey("fresns_user_block_{$type}_ids_by_{$userId}", $cacheTag);

        CacheHelper::forgetFresnsKeys([
            CacheHelper::getNullCacheKey("fresns_user_follow_{$type}_ids_by_{$userId}"),
            CacheHelper::getNullCacheKey("fresns_user_block_{$type}_ids_by_{$userId}"),
        ], 'fresnsNullCount');

        CacheHelper::forgetFresnsKey("fresns_interaction_status_{$type}_{$id}_{$userId}", 'fresnsUsers');

        if ($type == InteractionUtility::TYPE_GROUP) {
            $types = Group::distinct()->pluck('type');
            foreach ($types as $type) {
                CacheHelper::forgetFresnsKey("fresns_group_{$type}_tree_by_user_{$userId}", ['fresnsGroups', 'fresnsUsers']);
            }
            CacheHelper::forgetFresnsKey("fresns_group_all_tree_by_user_{$id}", ['fresnsGroups', 'fresnsUsers']);

            CacheHelper::forgetFresnsKey("fresns_group_list_filter_ids_by_user_{$userId}", ['fresnsGroups', 'fresnsUsers']);
            CacheHelper::forgetFresnsKey("fresns_group_content_filter_ids_by_user_{$userId}", ['fresnsGroups', 'fresnsUsers']);
        }
    }

    /**
     * no tag.
     */
    // fresns_cache_tags
    // fresns_cache_config_keys
    // install_{$step}
    // autoUpgradeStep
    // autoUpgradeTip
    // manualUpgradeStep
    // manualUpgradeTip

    /**
     * tag: fresnsSystems.
     */
    // fresns_current_version
    // fresns_new_version
    // fresns_news
    // fresns_database_timezone
    // fresns_database_datetime
    // fresns_panel_translation_{$locale}
    // fresns_plugin_check_status

    /**
     * tag: fresnsConfigs.
     */
    // fresns_config_file_accept
    // fresns_config_file_url_expire
    // fresns_role_{$id}
    // fresns_code_messages_{$fskey}
    // fresns_plugin_url_{$fskey}
    // fresns_plugin_host_{$fskey}
    // fresns_plugin_version_{$fskey}

    /**
     * fresns model.
     */
    // fresns_model_config_{$itemKey}                               // tag: fresnsConfigs
    // fresns_model_key_{$appId}                                    // tag: fresnsSystems
    // fresns_model_account_{$aid}                                  // tag: fresnsAccounts
    // fresns_model_user_{$uidOrUsername}_by_fsid                   // tag: fresnsUsers
    // fresns_model_user_{$userId}                                  // tag: fresnsUsers
    // fresns_model_group_{$gid}                                    // tag: fresnsGroups
    // fresns_model_group_{$groupId}                                // tag: fresnsGroups
    // fresns_model_hashtag_{$htid}                                 // tag: fresnsHashtags
    // fresns_model_hashtag_{$hashtagId}                            // tag: fresnsHashtags
    // fresns_model_geotag_{$gtid}                                  // tag: fresnsGeotags
    // fresns_model_geotag_{$geotagId}                              // tag: fresnsGeotags
    // fresns_model_post_{$pid}                                     // tag: fresnsPosts
    // fresns_model_post_{$postId}                                  // tag: fresnsPosts
    // fresns_model_comment_{$cid}                                  // tag: fresnsComments
    // fresns_model_comment_{$commentId}                            // tag: fresnsComments
    // fresns_model_file_{$fid}                                     // tag: fresnsFiles
    // fresns_model_file_{$fileId}                                  // tag: fresnsFiles
    // fresns_model_extend_{$eid}                                   // tag: fresnsExtends
    // fresns_model_archive_{$code}                                 // tag: fresnsArchives
    // fresns_model_operation_{$operationId}                        // tag: fresnsOperations
    // fresns_model_post_log_{$hpid}                                // tag: fresnsPosts
    // fresns_model_post_log_{$postLogId}                           // tag: fresnsPosts
    // fresns_model_comment_log_{$hcid}                             // tag: fresnsComments
    // fresns_model_comment_log_{$commentLogId}                     // tag: fresnsComments
    // fresns_model_seo_{$usageType}_{$usageId}                     // tag: fresnsSeo
    // fresns_model_conversation_{$userId}_{$conversationUserId}    // tag: fresnsUsers

    /**
     * fresns file.
     */
    // fresns_file_info_{$id}
    // fresns_file_info_{$fid}

    /**
     * fresns detail.
     */
    // fresns_detail_account_{$id}_{$langTag}                       // tag: fresnsAccounts
    // fresns_detail_user_{$id}_{$langTag}                          // tag: fresnsUsers
    // fresns_detail_user_stats_{$id}                               // tag: fresnsUsers
    // fresns_detail_group_{$id}_{$langTag}                         // tag: fresnsGroups
    // fresns_detail_hashtag_{$id}_{$langTag}                       // tag: fresnsHashtags
    // fresns_detail_geotag_{$id}_{$langTag}                        // tag: fresnsGeotags
    // fresns_detail_post_{$id}_{$langTag}                          // tag: fresnsPosts
    // fresns_detail_post_{$id}_preview_like_users_{$langTag}       // tag: fresnsPosts
    // fresns_detail_post_{$id}_preview_comments_{$langTag}         // tag: fresnsPosts
    // fresns_detail_comment_{$id}_{$langTag}                       // tag: fresnsComments
    // fresns_detail_comment_{$id}_preview_like_users_{$langTag}    // tag: fresnsComments
    // fresns_detail_comment_{$id}_preview_comments_{$langTag}      // tag: fresnsComments
    // fresns_detail_post_history_{$id}_{$langTag}                  // tag: fresnsPosts
    // fresns_detail_comment_history_{$id}_{$langTag}               // tag: fresnsComments

    /**
     * fresns token.
     */
    // fresns_token_account_{$accountId}_{$token}                   // tag: fresnsAccounts
    // fresns_token_user_{$userId}_{$token}                         // tag: fresnsUsers

    /**
     * fresns interaction.
     */
    // fresns_user_follow_{$type}_ids_by_{$userId}
    // fresns_user_follow_1_ids_by_{$userId}                        // tag: fresnsUsers
    // fresns_user_follow_2_ids_by_{$userId}                        // tag: fresnsGroups
    // fresns_user_follow_3_ids_by_{$userId}                        // tag: fresnsHashtags
    // fresns_user_follow_4_ids_by_{$userId}                        // tag: fresnsGeotags
    // fresns_user_follow_5_ids_by_{$userId}                        // tag: fresnsPosts
    // fresns_user_follow_6_ids_by_{$userId}                        // tag: fresnsComments
    // fresns_user_block_{$type}_ids_by_{$userId}
    // fresns_user_block_1_ids_by_{$userId}                         // tag: fresnsUsers
    // fresns_user_block_2_ids_by_{$userId}                         // tag: fresnsGroups
    // fresns_user_block_3_ids_by_{$userId}                         // tag: fresnsHashtags
    // fresns_user_block_4_ids_by_{$userId}                         // tag: fresnsGeotags
    // fresns_user_block_5_ids_by_{$userId}                         // tag: fresnsPosts
    // fresns_user_block_6_ids_by_{$userId}                         // tag: fresnsComments

    /**
     * fresns api.
     */
    // fresns_api_configs_{$langTag}                                // tag: fresnsConfigs
    // fresns_api_language_pack_{$langTag}                          // tag: fresnsConfigs
    // fresns_api_archives_{$type}_{$gid}_{$langTag}                // tag: fresnsConfigs
    // fresns_api_stickers_{$langTag}                               // tag: fresnsConfigs
    // fresns_api_list_{$crc32}_role_ids                            // tag: fresnsConfigs
    // fresns_api_list_{$crc32}_user_ids                            // tag: fresnsConfigs
    // fresns_api_list_{$crc32}_group_ids                           // tag: fresnsConfigs
    // fresns_api_list_{$crc32}_hashtag_ids                         // tag: fresnsConfigs
    // fresns_api_list_{$crc32}_geotag_ids                          // tag: fresnsConfigs
    // fresns_api_user_list_{$crc32}_guest                          // tag: fresnsList
    // fresns_api_group_list_{$crc32}_guest                         // tag: fresnsList
    // fresns_api_hashtag_list_{$crc32}_guest                       // tag: fresnsList
    // fresns_api_geotag_list_{$crc32}_guest                        // tag: fresnsList
    // fresns_api_post_list_{$crc32}_guest                          // tag: fresnsList
    // fresns_api_comment_list_{$crc32}_guest                       // tag: fresnsList

    /**
     * tag: fresnsUsers.
     */
    // fresns_user_activity_{$uid}
    // fresns_user_{$userId}_main_role
    // fresns_user_{$userId}_roles
    // fresns_user_overview_conversations_{$uid}
    // fresns_user_overview_notifications_{$uid}
    // fresns_user_overview_drafts_{$uid}
    // fresns_user_post_auth_{$postId}_{$userId}
    // fresns_app_badge_{$fskey}_{$userId}
    // fresns_interaction_status_{$markType}_{$markId}_{$userId}
    // fresns_publish_{$type}_config_{$userId}_{$langTag}

    /**
     * tag: fresnsGroups.
     */
    // fresns_group_private_ids
    // fresns_group_subgroups_ids_{$idOrGid}
    // fresns_group_{type}_tree_by_guest
    // fresns_group_{type}_tree_by_user_{$userId}                   // +tag: fresnsUsers
    // fresns_group_list_filter_ids_by_user_{$userId}               // +tag: fresnsUsers
    // fresns_group_content_filter_ids_by_user_{$userId}            // +tag: fresnsUsers

    /**
     * tag: fresnsExtensions.
     */
    // fresns_wallet_recharge_extends_by_everyone_{$langTag}
    // fresns_wallet_withdraw_extends_by_everyone_{$langTag}
    // fresns_post_content_types_by_{$typeName}_{$langTag}              // +tag: fresnsConfigs
    // fresns_comment_content_types_by_{$typeName}_{$langTag}           // +tag: fresnsConfigs

    // fresns_editor_post_extends_by_everyone_{$langTag}
    // fresns_editor_comment_extends_by_everyone_{$langTag}
    // fresns_manage_post_extends_by_everyone_{$langTag}
    // fresns_manage_comment_extends_by_everyone_{$langTag}
    // fresns_manage_user_extends_by_everyone_{$langTag}
    // fresns_group_{$groupId}_extends_by_everyone_{$langTag}           // +tag: fresnsGroups
    // fresns_feature_extends_by_everyone_{$langTag}
    // fresns_profile_extends_by_everyone_{$langTag}
    // fresns_channel_extends_by_everyone_{$langTag}

    // fresns_editor_post_extends_by_role_{$roleId}_{$langTag}
    // fresns_editor_comment_extends_by_role_{$roleId}_{$langTag}
    // fresns_manage_post_extends_by_role_{$roleId}_{$langTag}
    // fresns_manage_comment_extends_by_role_{$roleId}_{$langTag}
    // fresns_manage_user_extends_by_role_{$roleId}_{$langTag}
    // fresns_group_{$groupId}_extends_by_role_{$roleId}_{$langTag}     // +tag: fresnsGroups
    // fresns_feature_extends_by_role_{$roleId}_{$langTag}
    // fresns_profile_extends_by_role_{$roleId}_{$langTag}

    // fresns_manage_post_extends_by_group_admin_{$langTag}
    // fresns_manage_comment_extends_by_group_admin_{$langTag}
    // fresns_group_{$groupId}_extends_by_group_admin_{$langTag}        // +tag: fresnsGroups

    /**
     * tag: fresnsWeb.
     */
    // fresns_web_middleware                                            // +tag: fresnsWebConfigs
    // fresns_web_status                                                // +tag: fresnsWebConfigs
    // fresns_web_api_host                                              // +tag: fresnsWebConfigs
    // fresns_web_api_key                                               // +tag: fresnsWebConfigs
    // fresns_web_configs_{$langTag}                                    // +tag: fresnsWebConfigs
    // fresns_web_languages_{$langTag}                                  // +tag: fresnsWebConfigs
    // fresns_web_post_content_types_{$langTag}                         // +tag: fresnsWebConfigs
    // fresns_web_comment_content_types_{$langTag}                      // +tag: fresnsWebConfigs
    // fresns_web_editor_post_configs_{$uid}_{$langTag}                 // +tag: fresnsWebConfigs
    // fresns_web_editor_comment_configs_{$uid}_{$langTag}              // +tag: fresnsWebConfigs
    // fresns_web_editor_stickers_{$langTag}                            // +tag: fresnsWebConfigs
    // fresns_web_channels_guest_{$langTag}
    // fresns_web_channels_{$uid}_{$langTag}
    // fresns_web_account_{$aid}_{$langTag}
    // fresns_web_user_{$uid}_{$langTag}
    // fresns_web_user_overview_{$uid}_{$langTag}
    // fresns_web_content_{$channel}_{$type}_by_{$uid}_{$langTag}
    // fresns_web_content_{$channel}_{$type}_by_guest_{$langTag}
    // fresns_web_sticky_posts_by_global_{$langTag}
    // fresns_web_sticky_posts_by_group_{$gid}_{$langTag}
    // fresns_web_sticky_comments_by_{$pid}_{$langTag}
}
