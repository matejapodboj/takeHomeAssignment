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
        Schema::create('real_estate_entries', function (Blueprint $table) {
            $table->integer('real_estate_entry_id', true);
            $table->enum('real_estate_entries_type', ['apartment', 'house']);
            $table->string('real_estate_entries_address', 255);
            $table->integer('real_estate_entries_size');
            $table->integer('real_estate_entries_number_of_bedrooms');
            $table->float('real_estate_entries_price');
            $table->double('real_estate_entries_latitude')->nullable();
            $table->double('real_estate_entries_longitude')->nullable();
            $table->timestamps();
            $table->softDeletes('real_estate_entries_deleted_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('real_estate_entries');
    }
};
