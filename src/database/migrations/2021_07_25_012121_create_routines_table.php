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
            $table->string('name', 255);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('total_days')->default(0);
            $table->unsignedBigInteger('continuous_days')->default(0);
            $table->unsignedBigInteger('recovery_count')->default(0);
            $table->foreignId('total_rank_id')->constrained('ranks');
            $table->foreignId('continuous_rank_id')->constrained('ranks');
            $table->foreignId('recovery_rank_id')->constrained();
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
