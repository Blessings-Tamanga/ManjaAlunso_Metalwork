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
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'title')) {
                $table->string('title')->after('id');
            }
            if (!Schema::hasColumn('projects', 'slug')) {
                $table->string('slug')->unique()->after('title');
            }
            if (!Schema::hasColumn('projects', 'description')) {
                $table->text('description')->nullable()->after('slug');
            }
            if (!Schema::hasColumn('projects', 'client')) {
                $table->string('client')->nullable()->after('description');
            }
            if (!Schema::hasColumn('projects', 'completed_at')) {
                $table->date('completed_at')->nullable()->after('client');
            }
            if (!Schema::hasColumn('projects', 'image')) {
                $table->string('image')->nullable()->after('completed_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['image', 'completed_at', 'client', 'description', 'slug', 'title']);
        });
    }
};
