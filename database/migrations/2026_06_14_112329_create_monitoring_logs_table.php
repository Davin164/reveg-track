<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monitoring_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('planting_record_id')->constrained('planting_records')->onDelete('cascade');
            $table->foreignUuid('logged_by')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('alive_count')->default(0);
            $table->integer('dead_count')->default(0);
            $table->decimal('survival_rate', 5, 2)->default(0);
            $table->enum('ai_condition', ['healthy', 'wilting', 'dead', 'unknown'])->nullable();
            $table->decimal('ai_health_score', 4, 2)->nullable();
            $table->text('ai_notes')->nullable();
            $table->date('monitored_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monitoring_logs');
    }
};
