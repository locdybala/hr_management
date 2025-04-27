<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kpi_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('performance_review_id');
            $table->unsignedBigInteger('kpi_id');
            $table->decimal('target_value', 10, 2);
            $table->decimal('actual_value', 10, 2);
            $table->decimal('score', 5, 2);
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('performance_review_id')
                  ->references('id')
                  ->on('performance_reviews')
                  ->onDelete('cascade');

            $table->foreign('kpi_id')
                  ->references('id')
                  ->on('kpis')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('kpi_results');
    }
};
