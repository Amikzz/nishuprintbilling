<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_databases', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number');
            $table->string('delivery_note_no');
            $table->string('new_dnote_no')->nullable();
            $table->string('po_no');
            $table->string('item_code');
            $table->string('color_name');
            $table->string('color_no');
            $table->string('size');
            $table->string('style');
            $table->string('upc_no');
            $table->integer('po_qty');
            $table->decimal('price', 10, 2); // Adjust precision as needed
            $table->string('more1')->nullable();
            $table->string('more2')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('return_databases');
    }
};
