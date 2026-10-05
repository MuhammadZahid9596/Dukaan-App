<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('trial_ends_at')->nullable()->after('shop_name');
            $table->boolean('subscription_active')->default(false)->after('trial_ends_at');
            $table->timestamp('subscription_expires_at')->nullable()->after('subscription_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['trial_ends_at', 'subscription_active', 'subscription_expires_at']);
        });
    }
};