<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('loan_status', ['Running', 'Closed'])->default('Running');

            $table->bigIncrements('loan_amount');
            $table->bigIncrements('loan_tenure');
            $table->bigIncrements('emi_amount');
            $table->bigIncrements('user_id');
            $table->integer('user_id')->nullable();

            $table->enum('is_deleted', ['True', 'False'])->default('False');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan');
    }
}
            