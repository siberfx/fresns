<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run fresns migrations.
     */
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fid', 32)->unique('fid');
            $table->unsignedTinyInteger('type')->index('file_type');
            $table->string('name', 128);
            $table->string('mime', 128);
            $table->string('extension', 32);
            $table->unsignedInteger('size');
            $table->unsignedSmallInteger('width')->nullable();
            $table->unsignedSmallInteger('height')->nullable();
            $table->unsignedSmallInteger('duration')->nullable();
            $table->string('sha', 128)->nullable();
            $table->string('sha_type', 16)->nullable();
            $table->unsignedTinyInteger('warning_type')->default(1);
            $table->string('path')->unique('file_path');
            $table->unsignedTinyInteger('transcoding_state')->default(1);
            $table->string('transcoding_reason')->nullable();
            $table->string('video_poster_path')->nullable();
            $table->string('original_path')->nullable();
            $table->boolean('is_long_image')->default(0)->index('file_long_image');
            $table->boolean('is_uploaded')->default(1)->index('file_is_uploaded');
            $table->boolean('is_enabled')->default(1)->index('file_is_enabled');
            $table->boolean('physical_deletion')->default(0)->index('file_physical_deletion');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->softDeletes();

            $table->index(['sha', 'sha_type'], 'file_sha');
        });

        Schema::create('file_usages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('file_id')->index('file_usage_file_id');
            $table->unsignedTinyInteger('file_type')->index('file_usage_file_type');
            $table->unsignedTinyInteger('usage_type')->index('file_usage_type');
            $table->unsignedTinyInteger('platform_id');
            $table->string('table_name', 64);
            $table->string('table_column', 64);
            $table->unsignedBigInteger('table_id')->nullable();
            $table->string('table_key', 64)->nullable();
            $table->unsignedSmallInteger('sort_order')->nullable();
            switch (config('database.default')) {
                case 'pgsql':
                    $table->jsonb('more_info')->nullable();
                    break;

                default:
                    $table->json('more_info')->nullable();
            }
            $table->unsignedBigInteger('account_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('remark')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->softDeletes();

            $table->index(['table_name', 'table_column'], 'file_usage_table_column');
        });

        Schema::create('file_downloads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('file_id')->index('file_download_file_id');
            $table->unsignedTinyInteger('file_type')->index('file_download_file_type');
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('user_id')->nullable()->index('file_download_user_id');
            $table->string('app_fskey', 64)->nullable();
            $table->unsignedTinyInteger('target_type')->index('file_download_target_type');
            $table->unsignedBigInteger('target_id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse fresns migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
        Schema::dropIfExists('file_usages');
        Schema::dropIfExists('file_downloads');
    }
};
