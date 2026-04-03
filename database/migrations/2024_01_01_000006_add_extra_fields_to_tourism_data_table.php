<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tourism_data', function (Blueprint $table) {
            $table->unsignedTinyInteger('month')->nullable()->after('visit_date');
            $table->string('age_group')->nullable()->after('month');
            $table->string('travel_type')->nullable()->after('age_group');
            $table->string('budget')->nullable()->after('travel_type');
            $table->unsignedInteger('duration')->nullable()->after('budget');
            $table->unsignedTinyInteger('satisfaction')->nullable()->after('duration');
            $table->unsignedInteger('previous_visits')->nullable()->after('satisfaction');
            $table->decimal('spending', 10, 2)->nullable()->after('previous_visits');
            $table->boolean('will_return')->nullable()->after('spending');
        });
    }

    public function down(): void
    {
        Schema::table('tourism_data', function (Blueprint $table) {
            $table->dropColumn([
                'month',
                'age_group',
                'travel_type',
                'budget',
                'duration',
                'satisfaction',
                'previous_visits',
                'spending',
                'will_return',
            ]);
        });
    }
};
