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
        Schema::create('assignment_submissions', function (Blueprint $table) {
            // $table->id();
            // $table->foreignId('assignment_id')
            //     ->constrained()
            //     ->onUpdate('cascade')
            //     ->onDelete('cascade');
            // $table->foreignId('user_id')
            //     ->constrained()
            //     ->onUpdate('cascade')
            //     ->onDelete('cascade');
            // $table->string('file_path');
            // $table->dateTime('submitted_at');
            // $table->timestamps();

            $table->id();
            $table->foreignId('assignment_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('file_path');
            // $table->decimal('score', 5, 2)->nullable(); // Total 5 digit, 2 di antaranya di belakang koma (misal: 100.00)
            // $table->text('feedback')->nullable();
            $table->boolean('is_late')->default(false);
            $table->timestamps();
            $table->unique(['assignment_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_submissions');
    }
};
