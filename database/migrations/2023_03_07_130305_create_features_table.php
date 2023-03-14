<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->string('status', 10);
            $table->timestamps();
            $table->rawIndex('(
                case
                    when status = "Requested" then 1
                    when status = "Approved" then 2
                    when status = "Completed" then 3
                end
                )', 'features_status_ranking_index');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('features');
    }
};
