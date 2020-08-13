<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_informations', function (Blueprint $table) {
            $table->id();
            $table->string('cin');
            $table->string('name');
            $table->string('status');
            $table->string('age');
            $table->integer('reg_number');
            $table->string('category');
            $table->string('subcategory');
            $table->string('class');
            $table->string('roc_code');
            $table->string('members')->default(0);
            $table->string('email')->nullable();
            $table->string('registered_office');
            $table->string('listed');
            $table->date('last_agm_date')->nullable();
            $table->date('balance_sheet_date')->nullable();
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
        Schema::dropIfExists('company_informations');
    }
}
