<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            if (! Schema::hasColumn('schools', 'district')) {
                $table->string('district')->nullable()->after('code')->index();
            }

            if (! Schema::hasColumn('schools', 'category')) {
                $table->string('category')->nullable()->after('district')->index();
            }
        });

        Schema::table('user_roles', function (Blueprint $table) {
            if (! Schema::hasColumn('user_roles', 'state')) {
                $table->string('state')->nullable()->after('role');
            }

            if (! Schema::hasColumn('user_roles', 'district')) {
                $table->string('district')->nullable()->after('state');
            }
        });

        Schema::table('payments', function (Blueprint $table) {
            if (! Schema::hasColumn('payments', 'method')) {
                $table->string('method')->nullable()->after('status');
            }

            if (! Schema::hasColumn('payments', 'gateway_provider')) {
                $table->string('gateway_provider')->nullable()->after('method');
            }

            if (! Schema::hasColumn('payments', 'gateway_reference')) {
                $table->string('gateway_reference')->nullable()->unique()->after('gateway_provider');
            }

            if (! Schema::hasColumn('payments', 'checkout_url')) {
                $table->text('checkout_url')->nullable()->after('gateway_transaction_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            foreach (['checkout_url', 'gateway_reference', 'gateway_provider', 'method'] as $column) {
                if (Schema::hasColumn('payments', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('user_roles', function (Blueprint $table) {
            foreach (['district', 'state'] as $column) {
                if (Schema::hasColumn('user_roles', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('schools', function (Blueprint $table) {
            foreach (['category', 'district'] as $column) {
                if (Schema::hasColumn('schools', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
