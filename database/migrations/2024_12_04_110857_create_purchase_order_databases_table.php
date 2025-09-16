<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('purchase_orders', static function (Blueprint $table) {
            $table->id(); // Primary key
            $table->date('date'); // Purchase order date
            $table->string('po_no'); // Purchase order number
            $table->unsignedBigInteger('customer_id'); // Foreign key to customers' table
            $table->string('item_code'); // Foreign key to item table
            $table->integer('po_qty'); // Purchase order quantity
            $table->string('color_no')->nullable(); // Color number
            $table->string('color_name')->nullable(); // Color name
            $table->string('size')->nullable(); // Size
            $table->string('style')->nullable(); // Style
            $table->string('upc_no')->nullable(); // UPC number
            $table->string('reference_no'); // Reference number
            $table->string('status')->default('Pending'); // Purchase order status
            $table->string('more1')->nullable(); // Additional information
            $table->string('more2')->nullable(); // Additional information
            $table->timestamps(); // Created_at and updated_at timestamps

            // Foreign key constraints
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('item_code')->references('reference_no')->on('items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
