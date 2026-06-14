<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compliance_reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('site_id')->constrained('sites')->onDelete('cascade');
            $table->foreignUuid('generated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('title', 200);
            $table->text('ai_narrative')->nullable();
            $table->string('period', 50);
            $table->enum('status', ['draft', 'final', 'submitted'])->default('draft');
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compliance_reports');
    }
};
