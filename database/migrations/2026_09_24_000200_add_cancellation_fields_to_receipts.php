<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('receipts', function (Blueprint $table) {
            if (! Schema::hasColumn('receipts', 'status')) $table->string('status')->default('ISSUED')->after('issued_at');
            if (! Schema::hasColumn('receipts', 'cancelled_at')) $table->timestamp('cancelled_at')->nullable()->after('status');
            if (! Schema::hasColumn('receipts', 'cancelled_by')) $table->foreignId('cancelled_by')->nullable()->after('cancelled_at')->constrained('users')->nullOnDelete();
            if (! Schema::hasColumn('receipts', 'cancellation_reason')) $table->text('cancellation_reason')->nullable()->after('cancelled_by');
        });
    }

    public function down(): void
    {
        Schema::table('receipts', function (Blueprint $table) {
            $table->dropForeign(['cancelled_by']);
            $table->dropColumn(['status', 'cancelled_at', 'cancelled_by', 'cancellation_reason']);
        });
    }
};
