<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('web3forms_key')->default('')->after('title');
        });

        DB::table('site_settings')->update(['web3forms_key' => '']);

        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn('mailer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('mailer')->default('')->after('title');
        });

        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn('web3forms_key');
        });
    }
};
