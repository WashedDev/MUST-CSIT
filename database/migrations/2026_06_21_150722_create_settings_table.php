<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        DB::table('settings')->insert([
            ['key' => 'session_lifetime', 'value' => '120'],
            ['key' => 'app_name', 'value' => config('app.name')],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
