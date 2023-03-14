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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->index();
            $table->string('first_name_normalized')
                ->virtualAs("regexp_replace(first_name, '[^A-Za-z0-9]', '')")->index();
            $table->string('last_name')->index();
            $table->string('last_name_normalized')
                ->virtualAs("regexp_replace(last_name, '[^A-Za-z0-9]', '')")->index();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->foreignId('company_id')->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('password')->nullable();
            $table->rememberToken()->nullable();
            $table->boolean('is_owner')->default(false);
            $table->index(['last_name', 'first_name']); // compound index. The order here matters depending on the order by query order.
            $table->timestamps();
            $table->rawIndex("(date_format(birth_date, '%m-%d')), name", 'users_birthday_name_index');// compound index (when ordering
            // by more than one column). in this case we are also using an expression. it is not possible to add a normal index to an index
            // which is evaluated
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
