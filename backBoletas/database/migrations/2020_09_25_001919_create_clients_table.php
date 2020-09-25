<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id('client_id');
            $table->string('firstname')->nullable(false);
            $table->string('lastname')->nullable(false);
            $table->enum('type_document', ['CC', 'CE', 'PE'])->nullable(false);
            $table->string('document')->nullable(false);
            $table->string('celphone')->nullable(false);
            $table->timestamps();
            $table->unique(['type_document', 'document']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('clients');
        Schema::enableForeignKeyConstraints();
    }
}
