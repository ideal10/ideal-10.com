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
        Schema::table('clients', function (Blueprint $table) {
            $table->string('original_name')->nullable()->after('img');
        });

        Schema::table('interest_links', function (Blueprint $table) {
            $table->string('original_name')->nullable()->after('url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('original_name');
        });

        Schema::table('interest_links', function (Blueprint $table) {
            $table->dropColumn('original_name');
        });
    }
};
