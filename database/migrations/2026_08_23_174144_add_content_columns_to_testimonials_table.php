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
        Schema::table('testimonials', function (Blueprint $table) {
            if (!Schema::hasColumn('testimonials', 'client_name')) {
                $table->string('client_name')->after('id');
            }
            if (!Schema::hasColumn('testimonials', 'client_company')) {
                $table->string('client_company')->nullable()->after('client_name');
            }
            if (!Schema::hasColumn('testimonials', 'content')) {
                $table->text('content')->after('client_company');
            }
            if (!Schema::hasColumn('testimonials', 'rating')) {
                $table->integer('rating')->after('content');
            }
            if (!Schema::hasColumn('testimonials', 'is_approved')) {
                $table->boolean('is_approved')->default(false)->after('rating');
            }
        });
    }

    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn(['is_approved', 'rating', 'content', 'client_company', 'client_name']);
        });
    }
};
