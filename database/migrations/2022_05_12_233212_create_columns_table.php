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
        Schema::create('columns', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('name');
            $table->enum('type', ['id', 'text', 'number', 'checkbox' , 'tel', 'email' ,'url' ,'date'  ,'relation']);
            $table->string('relationColumnName')->nullable(); //if type is relation, this is column that will be viewed when adding relation for UX purposes

            $table->foreignId('table_id')->nullable()->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('columns');
    }
};
