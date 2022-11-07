<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->string('invoice_number');
            $table->string('product_id');
            $table->string('department_id');
            $table->string('status_ar',50);
            $table->string('status_en',50);
            $table->integer('value_status')->default(3);
            $table->decimal('payment_amount')->nullable();
            $table->date('partial_payment_date')->nullable();
            $table->decimal('remaining_amount')->nullable();
            $table->date('total_payment_date')->nullable();
            $table->text('notes_ar')->nullable();
            $table->text('notes_en')->nullable();
            $table->string('user');
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
        Schema::dropIfExists('invoice_details');
    }
}
