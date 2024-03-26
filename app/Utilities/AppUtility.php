<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace App\Utilities;

use App\Helpers\AppHelper;
use App\Helpers\CacheHelper;
use App\Helpers\ConfigHelper;
use App\Helpers\StrHelper;
use App\Models\Account;
use App\Models\App as AppModel;
use App\Models\Config;
use App\Models\UserRole;
use GuzzleHttp\Client;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;

class AppUtility
{
    const BASE_URL = 'https://app.fresns.org';
    const WEBSITE_URL = 'https://fresns.org';
    const WEBSITE_ZH_HANS_URL = 'https://fresns.org/zh-Hans';
    const COMMUNITY_URL = 'https://discuss.fresns.org';
    const MARKETPLACE_URL = 'https://marketplace.fresns.com';

    public static function currentVersion(): array
    {
        $cacheKey = 'fresns_current_version';
        $cacheTag = 'fresnsSystems';
        $currentVersion = CacheHelper::get($cacheKey, $cacheTag);

        if (empty($currentVersion)) {
            $fresnsJson = file_get_contents(
                base_path('fresns.json')
            );

            $currentVersion = json_decode($fresnsJson, true);

            CacheHelper::put($currentVersion, $cacheKey, $cacheTag, 1, now()->addDays());
        }

        return $currentVersion;
    }

    public static function newVersion(): array
    {
        $cacheKey = 'fresns_new_version';
        $cacheTag = 'fresnsSystems';

        $newVersion = CacheHelper::get($cacheKey, $cacheTag);

        if (empty($newVersion)) {
            $baseUrl = AppUtility::BASE_URL;
            $fileUrl = $baseUrl.'/v3/version-1.json';

            $httpProxy = config('app.http_proxy');

            $options = [
                'verify' => false,
                'proxy' => [
                    'http' => $httpProxy,
                    'https' => $httpProxy,
                ],
            ];

            try {
                $client = new Client($options);
                $response = $client->request('GET', $fileUrl);

                $versionInfo = json_decode($response->getBody(), true);

                $buildType = ConfigHelper::fresnsConfigByItemKey('build_type');
                if ($buildType == 1) {
                    $newVersion = $versionInfo['stableBuild'];
                } else {
                    $newVersion = $versionInfo['betaBuild'];
                }
            } catch (\Exception $e) {
                $newVersion = [
                    'version' => AppHelper::VERSION,
                    'releaseDate' => null,
                    'changeIntro' => 'https://fresns.org/intro/changelog.html',
                    'upgradeAuto' => false,
                    'upgradeIntro' => 'https://fresns.org/intro/changelog.html',
                    'upgradePackage' => null,
                ];
            }

            CacheHelper::put($newVersion, $cacheKey, $cacheTag, 10, now()->addHours(6));
        }

        return $newVersion;
    }

    public static function fresnsNews(): ?array
    {
        $cacheKey = 'fresns_news';
        $cacheTag = 'fresnsSystems';

        $news = CacheHelper::get($cacheKey, $cacheTag);

        if (empty($news)) {
            $baseUrl = AppUtility::BASE_URL;
            $fileUrl = $baseUrl.'/v3/news.json';

            $httpProxy = config('app.http_proxy');

            $options = [
                'verify' => false,
                'proxy' => [
                    'http' => $httpProxy,
                    'https' => $httpProxy,
                ],
            ];

            try {
                $client = new Client($options);
                $response = $client->request('GET', $fileUrl);

                $news = json_decode($response->getBody(), true);
            } catch (\Exception $e) {
                $news = [];
            }

            CacheHelper::put($news, $cacheKey, $cacheTag, 5, now()->addHours(3));
        }

        $newsList = [
            [
                'date' => '03/28/2024',
                'title' => '[Must Read] Help/Questions',
                'link' => 'https://fresns.org/guide/faq.html',
                'color' => '#f40',
            ],
            [
                'date' => '03/28/2024',
                'title' => '[Must Read] Guide to give feedback to Fresns official questions',
                'link' => 'https://fresns.org/guide/feedback.html',
                'color' => '#f40',
            ],
            [
                'date' => '03/28/2024',
                'title' => 'Introduction of 16 excellent features of Fresns product',
                'link' => 'https://fresns.org/intro/features.html',
                'color' => null,
            ],
            [
                'date' => '03/28/2024',
                'title' => 'The design concept of Fresns social networking service software',
                'link' => 'https://docs.fresns.com/open-source/guide/idea.html',
                'color' => null,
            ]
        ];
        if ($news) {
            $newsList = StrHelper::languageContent($news, App::getLocale());
        }

        return $newsList;
    }

    public static function writeEnvironment(array $dbConfig, ?string $appUrl = null): void
    {
        // Get the config file template
        $envExamplePath = app_path('Fresns/Install/Config/.env.template');
        $envPath = base_path('.env');

        $envTemp = file_get_contents($envExamplePath);

        $appKey = Encrypter::generateKey(config('app.cipher'));
        $appKey = sprintf('base64:%s', base64_encode($appKey));

        if (empty($appUrl)) {
            $appUrl = str_replace(\request()->getRequestUri(), '', \request()->getUri());
        }

        $driver = $dbConfig['DB_CONNECTION'];

        // Temp write key
        $template = [
            'APP_KEY' => $appKey,
            'APP_URL' => $appUrl,
            'APP_TIMEZONE' => $dbConfig['DB_TIMEZONE'],
            'DB_CONNECTION' => $driver,
            'DB_HOST' => ($driver == 'sqlite') ? '' : $dbConfig['DB_HOST'],
            'DB_PORT' => ($driver == 'sqlite') ? '' : $dbConfig['DB_PORT'],
            'DB_DATABASE' => $dbConfig['DB_DATABASE'],
            'DB_USERNAME' => ($driver == 'sqlite') ? '' : $dbConfig['DB_USERNAME'],
            'DB_PASSWORD' => ($driver == 'sqlite') ? '' : $dbConfig['DB_PASSWORD'],
            'DB_PREFIX' => $dbConfig['DB_PREFIX'],
        ];

        foreach ($template as $key => $value) {
            $envTemp = str_replace('{'.$key.'}', $value, $envTemp);
        }

        // Write config
        file_put_contents($envPath, $envTemp);
    }

    public static function writeInstallTime(): void
    {
        Config::updateOrCreate([
            'item_key' => 'installed_datetime',
        ], [
            'item_value' => now(),
            'item_type' => 'string',
        ]);

        // install.lock
        $installLock = base_path('install.lock');

        file_put_contents($installLock, now()->toDateTimeString());
    }

    public static function makeAdminAccount(string $email, string $password): void
    {
        $fresnsResp = \FresnsCmdWord::plugin('Fresns')->createAccount([
            'type' => Account::CREATE_TYPE_EMAIL,
            'account' => $email,
            'password' => $password,
            'createUser' => true,
            'userInfo' => [
                'nickname' => 'Admin',
                'username' => 'admin',
            ],
        ]);

        $aid = $fresnsResp->getData('aid');

        // set account to admin
        Account::where('aid', $aid)->update([
            'type' => Account::TYPE_SYSTEM_ADMIN,
        ]);

        UserRole::where('user_id', 1)->where('is_main', 1)->update([
            'role_id' => 1,
        ]);
    }

    public static function checkVersion(): bool
    {
        $currentVersion = AppUtility::currentVersion()['version'];
        $newVersion = AppUtility::newVersion()['version'];

        if (version_compare($currentVersion, $newVersion) == -1) {
            return true; // There is a new version
        }

        return false; // No new version
    }

    public static function editVersion(string $version): bool
    {
        $fresnsJsonPath = base_path('fresns.json');
        $statusJsonPath = public_path('status.json');

        // fresns.json
        try {
            $fresnsJsonContents = file_get_contents($fresnsJsonPath);
            $fresnsJson = json_decode($fresnsJsonContents, true);
        } catch (\Exception $e) {
            $fresnsJson = [
                'name' => 'Fresns',
                'version' => $version,
                'license' => 'Apache-2.0',
                'homepage' => 'https://fresns.org',
                'plugins' => [],
            ];
        }

        // status.json
        try {
            $statusJsonContents = file_get_contents($statusJsonPath);
            $statusJson = json_decode($statusJsonContents, true);
        } catch (\Exception $e) {
            $statusJson = [
                'name' => 'Fresns',
                'version' => $version,
                'activate' => true,
                'deactivateDescribe' => [
                    'default' => '',
                ],
            ];
        }

        $fresnsJson['version'] = $version;
        $statusJson['version'] = $version;

        $editFresnsContent = json_encode($fresnsJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
        file_put_contents($fresnsJsonPath, $editFresnsContent);

        $editStatusContent = json_encode($statusJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
        file_put_contents($statusJsonPath, $editStatusContent);

        return true;
    }

    public static function checkPluginsStatus(): void
    {
        $fresnsJsonFile = file_get_contents(config('plugins.manager.default.file'));

        $fresnsJson = json_decode($fresnsJsonFile, true);

        $plugins = $fresnsJson['plugins'] ?? null;

        $pluginModels = AppModel::type(AppModel::TYPE_PLUGIN)->get();

        foreach ($pluginModels as $plugin) {
            $status = $plugins[$plugin->fskey] ?? false;

            $plugin->is_enabled = $status;
            $plugin->save();
        }
    }

    public static function macroMarketHeaders(): void
    {
        $marketplaceUrl = AppUtility::MARKETPLACE_URL;

        Http::macro('market', function () use ($marketplaceUrl) {
            $httpProxy = config('app.http_proxy');

            return Http::withHeaders(AppUtility::getMarketHeaders())
                ->baseUrl($marketplaceUrl)
                ->withHeaders([
                    'accept' => 'application/json',
                ])
                ->withOptions([
                    'proxy' => [
                        'http' => $httpProxy,
                        'https' => $httpProxy,
                    ],
                ]);
        });
    }

    public static function getMarketHeaders(): array
    {
        // config keys
        $configKeys = [
            'installed_datetime',
            'build_type',
            'site_url',
            'site_name',
            'site_desc',
            'site_copyright_name',
            'language_status',
            'language_menus',
            'default_language',
        ];

        $configs = Config::whereIn('item_key', $configKeys)->get();

        $installedDatetime = $configs->where('item_key', 'installed_datetime')->first()?->item_value;
        $buildType = $configs->where('item_key', 'build_type')->first()?->item_value;

        $isHttps = request()->getScheme() == 'https';

        $siteUrl = $configs->where('item_key', 'site_url')->first()?->item_value;

        $siteName = $configs->where('item_key', 'site_name')->first()?->item_value;
        $siteNameStringify = $siteName ? base64_encode(json_encode($siteName)) : null;

        $siteDesc = $configs->where('item_key', 'site_desc')->first()?->item_value;
        $siteDescStringify = $siteName ? base64_encode(json_encode($siteDesc)) : null;

        $siteCopyrightName = $configs->where('item_key', 'site_copyright_name')->first()?->item_value;
        $siteCopyrightNameStringify = $siteName ? base64_encode($siteCopyrightName) : null;

        $languageStatus = $configs->where('item_key', 'language_status')->first()?->item_value;
        $defaultLanguage = $configs->where('item_key', 'default_language')->first()?->item_value;

        $languageMenus = $configs->where('item_key', 'language_menus')->first()?->item_value;
        $languageMenusStringify = $siteName ? base64_encode(json_encode($languageMenus)) : null;

        return [
            'X-Fresns-Panel-Lang-Tag' => App::getLocale(),
            'X-Fresns-Installed-Datetime' => $installedDatetime,
            'X-Fresns-Build-Type' => $buildType,
            'X-Fresns-Version' => AppHelper::VERSION,
            'X-Fresns-Database' => config('database.default'),
            'X-Fresns-Http-Ssl' => $isHttps ? 1 : 0,
            'X-Fresns-Http-Host' => request()->getHost(),
            'X-Fresns-Http-Port' => request()->getPort(),
            'X-Fresns-System-Url' => config('app.url'),
            'X-Fresns-Site-Url' => $siteUrl,
            'X-Fresns-Site-Name' => $siteNameStringify,
            'X-Fresns-Site-Desc' => $siteDescStringify,
            'X-Fresns-Site-Copyright-Name' => $siteCopyrightNameStringify,
            'X-Fresns-Site-Timezone' => config('app.timezone'),
            'X-Fresns-Site-Language-Status' => $languageStatus ? 1 : 0,
            'X-Fresns-Site-Language-Menus' => $languageMenusStringify,
            'X-Fresns-Site-Language-Default' => $defaultLanguage,
        ];
    }
}
