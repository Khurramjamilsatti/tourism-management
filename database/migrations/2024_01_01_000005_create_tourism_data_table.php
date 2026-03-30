<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tourism_data', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_name')->nullable();
            $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
            $table->string('city_visited');
            $table->foreignId('purpose_id')->constrained('visit_purposes')->onDelete('cascade');
            $table->date('visit_date');
            $table->text('feedback')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tourism_data');
    }
};
