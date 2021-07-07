<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->enum('status', ['reviewing','waiting', 'active', 'done']);
            $table->integer('amount')->unsigned();
            $table->boolean('accepted')->default(false);
            $table->longText('description');
            $table->longText('agreement');
            $table->string('resolution')->nullable();
            $table->longText('notes')->nullable();
            $table->boolean('inverted')->default(false);
            $table->date('dateAcepted')->nullable()->comment('Date when case accepted');
            $table->integer('opener')->unsigned();
            $table->integer('recipient')->unsigned();
            $table->integer('mediator')->unsigned()->nullable();
            $table->string('initialData')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cases');
    }
}
