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
        Schema::table('contact_messages', function (Blueprint $table) {
            if (!Schema::hasColumn('contact_messages', 'name')) {
                $table->string('name')->after('id');
            }
            if (!Schema::hasColumn('contact_messages', 'email')) {
                $table->string('email')->after('name');
            }
            if (!Schema::hasColumn('contact_messages', 'service_interest')) {
                $table->string('service_interest')->nullable()->after('email');
            }
            if (!Schema::hasColumn('contact_messages', 'message')) {
                $table->text('message')->after('service_interest');
            }
            if (!Schema::hasColumn('contact_messages', 'is_read')) {
                $table->boolean('is_read')->default(false)->after('message');
            }
        });
    }

    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropColumn(['is_read', 'message', 'service_interest', 'email', 'name']);
        });
    }
};
