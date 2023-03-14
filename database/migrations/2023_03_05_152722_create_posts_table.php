<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->longText('body');
            $table->dateTime('published_at');
            $table->timestamps();
        });
        // Laravel migrations do not support fulltext indexing. Whenever we want to add custom functionality to
        // our migrations we can do it like this:
        DB::statement('
        create fulltext index post_fulltext_index
        on posts(title, body)
        with parser ngram
        '); // ngram was added in mysql 5.7.6. It leads to better e=results than standard parser
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
