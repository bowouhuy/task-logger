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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->date('task_date');

            $table->foreignId('project_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            $table->longText('activity');

            $table->string('pic', 100)->nullable();

            $table->enum('status', ['todo', 'waiting', 'doing', 'done'])
                ->default('todo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
