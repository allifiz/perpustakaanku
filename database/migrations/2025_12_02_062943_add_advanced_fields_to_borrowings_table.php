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
        Schema::table('borrowings', function (Blueprint $table) {
            $table->integer('renewal_count')->default(0)->after('status'); // Number of times renewed
            $table->date('renewed_at')->nullable()->after('renewal_count');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->after('renewed_at');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->foreignId('returned_to')->nullable()->constrained('users')->onDelete('set null')->after('approved_at');
            $table->decimal('fine_amount', 10, 2)->default(0)->after('fine'); // Calculated fine
            $table->boolean('fine_paid')->default(false)->after('fine_amount');
            $table->text('notes')->nullable()->after('fine_paid'); // Admin notes
            $table->string('condition_on_borrow')->default('good')->after('notes'); // Book condition when borrowed
            $table->string('condition_on_return')->nullable()->after('condition_on_borrow'); // Book condition when returned
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['returned_to']);
            $table->dropColumn([
                'renewal_count', 'renewed_at', 'approved_by', 'approved_at',
                'returned_to', 'fine_amount', 'fine_paid', 'notes',
                'condition_on_borrow', 'condition_on_return'
            ]);
        });
    }
};
