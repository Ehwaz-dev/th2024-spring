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
        DB::statement("CREATE INDEX event_name_trigram ON events USING gist(name gist_trgm_ops);");

        Schema::table('events', function (Blueprint $table) {
            $table->jsonb('places')->default("[]");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('places');
        });
        DB::statement('DROP INDEX  IF EXISTS event_name_trigram');
    }
};
