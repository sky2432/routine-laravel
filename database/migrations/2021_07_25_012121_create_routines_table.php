<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('total_days')->default(0);
            $table->unsignedBigInteger('continuous_days')->default(0);
            $table->unsignedBigInteger('highest_continuous_days')->default(0);
            $table->unsignedBigInteger('recovery_count')->default(0);
            $table->foreignId('total_rank_id')->constrained('ranks');
            $table->foreignId('highest_continuous_rank_id')->constrained('ranks');
            $table->foreignId('recovery_rank_id')->constrained('ranks');
            $table->boolean('is_archive')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('routines');
    }
}
