<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
        {
            Schema::table('projects', function (Blueprint $table) {
                if (!Schema::hasColumn('projects', 'is_featured')) {
                    $table->boolean('is_featured')->default(false);
                    }
                if (!Schema::hasColumn('projects', 'sort_order')) {
                    $table->integer('sort_order')->default(0);
                    }
});
    }

    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};