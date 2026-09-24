<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('district')->nullable()->index();
            $table->string('category')->nullable()->index();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('full_name')->nullable();
            $table->string('phone')->nullable();
        });

        Schema::create('academic_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->date('starts_on');
            $table->date('ends_on');
            $table->boolean('is_active')->default(false);
            $table->unique(['school_id', 'name']);
        });

        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role');
            $table->string('state')->nullable();
            $table->string('district')->nullable();
            $table->unique(['school_id', 'user_id', 'role']);
        });

        Schema::create('families', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->string('family_code');
            $table->string('name');
            $table->timestamps();
            $table->unique(['school_id', 'family_code']);
        });

        Schema::create('guardians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('full_name');
            $table->string('ic_number')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();
            $table->unique(['school_id', 'ic_number']);
        });

        Schema::create('family_guardians', function (Blueprint $table) {
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('family_id')->constrained()->cascadeOnDelete();
            $table->foreignId('guardian_id')->constrained()->cascadeOnDelete();
            $table->string('relationship');
            $table->primary(['family_id', 'guardian_id']);
        });

        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('family_id')->constrained()->cascadeOnDelete();
            $table->string('full_name');
            $table->string('student_identifier');
            $table->unsignedTinyInteger('year_level');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['school_id', 'student_identifier']);
        });

        Schema::create('student_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_session_id')->constrained()->cascadeOnDelete();
            $table->string('class_name');
            $table->unsignedTinyInteger('year_level');
            $table->boolean('is_active')->default(true);
            $table->unique(['school_id', 'student_id', 'academic_session_id']);
        });

        Schema::create('fee_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->string('code');
            $table->string('name');
            $table->string('applies_to');
            $table->unique(['school_id', 'code']);
        });

        Schema::create('fee_structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_session_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('status')->default('DRAFT');
            $table->decimal('approved_annual_limit', 12, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('fee_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fee_structure_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fee_category_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('status')->default('DRAFT');
        });

        Schema::create('family_fee_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('family_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fee_category_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('balance_amount', 12, 2);
            $table->date('due_date');
            $table->string('status')->default('UPCOMING');
            $table->timestamps();
        });

        Schema::create('student_fee_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fee_category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fee_rate_id')->nullable()->constrained()->nullOnDelete();
            $table->string('billing_period_type');
            $table->unsignedSmallInteger('billing_year');
            $table->unsignedTinyInteger('billing_month')->nullable();
            $table->decimal('original_amount', 12, 2);
            $table->decimal('adjustment_amount', 12, 2)->default(0);
            $table->decimal('final_amount', 12, 2);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('balance_amount', 12, 2);
            $table->date('due_date');
            $table->string('status')->default('UPCOMING');
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('family_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('status')->default('PENDING');
            $table->string('method')->nullable();
            $table->string('gateway_provider')->nullable();
            $table->string('gateway_reference')->nullable()->unique();
            $table->string('gateway_transaction_id')->nullable()->unique();
            $table->text('checkout_url')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('payment_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('family_fee_charge_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('student_fee_charge_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('amount', 12, 2);
            $table->timestamps();
        });

        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('receipt_number')->unique();
            $table->timestamp('issued_at');
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('actor_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action');
            $table->string('entity_type');
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->text('reason')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('receipts');
        Schema::dropIfExists('payment_allocations');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('student_fee_charges');
        Schema::dropIfExists('family_fee_charges');
        Schema::dropIfExists('fee_rates');
        Schema::dropIfExists('fee_structures');
        Schema::dropIfExists('fee_categories');
        Schema::dropIfExists('student_enrollments');
        Schema::dropIfExists('students');
        Schema::dropIfExists('family_guardians');
        Schema::dropIfExists('guardians');
        Schema::dropIfExists('families');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('academic_sessions');
        Schema::dropIfExists('schools');
    }
};
