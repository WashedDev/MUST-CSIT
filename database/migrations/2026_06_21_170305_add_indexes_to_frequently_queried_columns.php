<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->index('date');
            $table->index('visibility');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->index('type');
            $table->index('status');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->index('status');
        });

        Schema::table('merch_purchases', function (Blueprint $table) {
            $table->index('status');
        });

        Schema::table('merch_items', function (Blueprint $table) {
            $table->index('is_active');
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->index('status');
            $table->index('approved');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex(['date']);
            $table->dropIndex(['visibility']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropIndex(['status']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('merch_purchases', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('merch_items', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['approved']);
        });
    }
};
