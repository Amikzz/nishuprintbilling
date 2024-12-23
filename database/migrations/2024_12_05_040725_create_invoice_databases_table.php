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
        Schema::create('invoice_databases', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('invoice_no')->unique();
            $table->unsignedBigInteger('customer_id');
            $table->string('po_number');
            $table->string('reference_no');
            $table->string('delivery_note_no')->nullable();
            $table->integer('no_of_items');
            $table->string('status')->default('Pending');
            $table->string('artwork_sent_by')->nullable();
            $table->string('artwork_approved_by')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_databases');
    }
};
