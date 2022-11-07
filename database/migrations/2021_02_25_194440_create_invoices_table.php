<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number');
            $table->date('invoice_date');
            $table->date('due_date');
            $table->unsignedBigInteger('department_id');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->decimal('collection_amount')->nullable();
            $table->string('commission_rate');
            $table->decimal('commission_amount');
            $table->string('discount_rate');
            $table->decimal('discount');
            $table->decimal('sub_total');
            $table->string('rate_vat');
            $table->decimal('value_vat');
            $table->decimal('total');
            $table->string('status_ar',50);
            $table->string('status_en',50);
            $table->integer('value_status')->default(3);
            $table->text('notes_ar')->nullable();
            $table->text('notes_en')->nullable();
            $table->decimal('payment_amount')->nullable();
            $table->date('partial_payment_date')->nullable();
            $table->decimal('remaining_amount')->nullable();
            $table->date('total_payment_date')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('invoices');
    }
}
