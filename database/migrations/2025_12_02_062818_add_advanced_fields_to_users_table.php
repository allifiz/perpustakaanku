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
        Schema::table('users', function (Blueprint $table) {
            $table->string('member_card_number')->unique()->nullable()->after('id');
            $table->enum('member_type', ['student', 'teacher', 'staff', 'public'])->default('public')->after('role');
            $table->integer('max_loan')->default(3)->after('member_type'); // Max books can borrow
            $table->integer('loan_period_days')->default(14)->after('max_loan'); // Default loan period
            $table->date('member_since')->nullable()->after('loan_period_days');
            $table->date('member_expired_at')->nullable()->after('member_since');
            $table->string('institution')->nullable()->after('member_expired_at'); // School/organization
            $table->string('student_id')->nullable()->after('institution');
            $table->string('occupation')->nullable()->after('student_id');
            $table->date('birth_date')->nullable()->after('occupation');
            $table->enum('gender', ['male', 'female'])->nullable()->after('birth_date');
            $table->string('photo')->nullable()->after('id_card');
            $table->text('notes')->nullable()->after('photo'); // Admin notes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'member_card_number', 'member_type', 'max_loan', 'loan_period_days',
                'member_since', 'member_expired_at', 'institution', 'student_id',
                'occupation', 'birth_date', 'gender', 'photo', 'notes'
            ]);
        });
    }
};
