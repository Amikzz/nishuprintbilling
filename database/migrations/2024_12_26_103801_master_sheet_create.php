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
    public function up(): void
    {
        Schema::create('master_sheet', static function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('our_ref')->nullable();
            $table->date('mail_date')->nullable();
            $table->date('required_date')->nullable();
            $table->string('created_by')->nullable();
            $table->date('art_sent_date')->nullable();
            $table->date('art_approved_date')->nullable();
            $table->date('print_date')->nullable();
            $table->date('invoice_date')->nullable();
            $table->string('invoice_no')->nullable();
            $table->string('cust_ref')->nullable();
            $table->string('description')->nullable();
            $table->string('size')->nullable();
            $table->string('dn')->nullable();
            $table->date('dn_date')->nullable();
            $table->integer('pcs')->nullable();
            $table->decimal('invoice_value', 10)->nullable();
            $table->string('status')->nullable();
            $table->timestamps(); // Created at & Updated at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('master_sheet');
    }
};
