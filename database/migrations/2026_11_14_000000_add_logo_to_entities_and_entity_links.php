<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * An entity (or sub-entity) shows either the logo of an existing client
 * (`client_id`, the usual case: the escudos already live in Clientes) or an
 * image uploaded just for it (`logo`, a path on the public disk).
 */
return new class extends Migration
{
    public function up(): void
    {
        foreach (['entities' => 'name', 'entity_links' => 'href'] as $table => $after) {
            Schema::table($table, function (Blueprint $table) use ($after) {
                $table->foreignId('client_id')->nullable()->after($after)->constrained()->nullOnDelete();
                $table->string('logo')->nullable()->after('client_id');
            });
        }
    }

    public function down(): void
    {
        foreach (['entity_links', 'entities'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropConstrainedForeignId('client_id');
                $table->dropColumn('logo');
            });
        }
    }
};
