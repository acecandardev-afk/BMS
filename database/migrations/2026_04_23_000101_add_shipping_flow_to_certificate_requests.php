<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('certificate_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('certificate_requests', 'ship_delay_minutes')) {
                $table->unsignedSmallInteger('ship_delay_minutes')->nullable()->after('approved_at');
            }
            if (!Schema::hasColumn('certificate_requests', 'shipped_at')) {
                $table->timestamp('shipped_at')->nullable()->after('ship_delay_minutes');
            }
            if (!Schema::hasColumn('certificate_requests', 'on_delivery_at')) {
                $table->timestamp('on_delivery_at')->nullable()->after('shipped_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('certificate_requests', function (Blueprint $table) {
            if (Schema::hasColumn('certificate_requests', 'on_delivery_at')) {
                $table->dropColumn('on_delivery_at');
            }
            if (Schema::hasColumn('certificate_requests', 'shipped_at')) {
                $table->dropColumn('shipped_at');
            }
            if (Schema::hasColumn('certificate_requests', 'ship_delay_minutes')) {
                $table->dropColumn('ship_delay_minutes');
            }
        });
    }
};
