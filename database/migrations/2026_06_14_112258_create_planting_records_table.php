<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planting_records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('plot_id')->constrained('plots')->onDelete('cascade');
            $table->foreignUuid('species_id')->constrained('species');
            $table->foreignUuid('recorded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('seedling_count')->default(0);
            $table->date('planted_at');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planting_records');
    }
};
