<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the Apache-2.0 License.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostLogsTable extends Migration
{
    /**
     * Run fresns migrations.
     */
    public function up(): void
    {
        Schema::create('post_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('post_id')->nullable();
            $table->unsignedTinyInteger('create_type')->default(1);
            $table->unsignedTinyInteger('is_plugin_editor')->default(0);
            $table->string('editor_unikey', 64)->nullable();
            $table->unsignedInteger('group_id')->nullable();
            $table->string('title')->nullable();
            $table->longText('content')->nullable();
            $table->unsignedTinyInteger('is_markdown')->default(0);
            $table->unsignedTinyInteger('is_anonymous')->default(0);
            $table->unsignedTinyInteger('is_comment')->default(1);
            $table->unsignedTinyInteger('is_comment_public')->default(1);
            $table->json('map_json')->nullable();
            $table->json('allow_json')->nullable();
            $table->json('user_list_json')->nullable();
            $table->json('comment_btn_json')->nullable();
            $table->unsignedTinyInteger('state')->default(1);
            $table->string('reason')->nullable();
            $table->timestamp('submit_at')->nullable();
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
        Schema::dropIfExists('post_logs');
    }
}
