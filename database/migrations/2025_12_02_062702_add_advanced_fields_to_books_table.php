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
        Schema::table('books', function (Blueprint $table) {
            $table->string('barcode')->unique()->nullable()->after('id');
            $table->string('call_number')->nullable()->after('barcode');
            $table->string('ddc')->nullable()->after('call_number'); // Dewey Decimal Classification
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null')->after('ddc');
            $table->foreignId('publisher_id')->nullable()->constrained()->onDelete('set null')->after('category_id');
            $table->foreignId('location_id')->nullable()->constrained()->onDelete('set null')->after('publisher_id');
            $table->string('language')->default('Indonesian')->after('publication_year');
            $table->string('edition')->nullable()->after('language');
            $table->integer('pages')->nullable()->after('edition');
            $table->string('series')->nullable()->after('pages');
            $table->text('subjects')->nullable()->after('series'); // Keywords/subjects
            $table->string('physical_description')->nullable()->after('subjects');
            $table->enum('collection_type', ['reference', 'circulation', 'reserve', 'digital'])->default('circulation')->after('status');
            $table->decimal('price', 10, 2)->nullable()->after('collection_type');
            $table->string('source')->nullable()->after('price'); // Purchase/donation source
            $table->date('acquisition_date')->nullable()->after('source');
            $table->integer('shelf_position')->nullable()->after('location_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['publisher_id']);
            $table->dropForeign(['location_id']);
            $table->dropColumn([
                'barcode', 'call_number', 'ddc', 'category_id', 'publisher_id', 
                'location_id', 'language', 'edition', 'pages', 'series', 'subjects',
                'physical_description', 'collection_type', 'price', 'source', 
                'acquisition_date', 'shelf_position'
            ]);
        });
    }
};
