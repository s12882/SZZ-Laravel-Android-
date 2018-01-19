<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemWarehouseDocumentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_warehouse_document', function (Blueprint $table) {
        $table->integer('item_id')->unsigned()->nullable();
        $table->foreign('item_id')->references('id')->on('items')->onDelete('set null');
  
        $table->integer('warehouse_document_id')->unsigned()->nullable();
        $table->foreign('warehouse_document_id')->references('id')->on('warehouse_documents')->onDelete('cascade');

        $table->integer('amount');
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
        Schema::dropIfExists('item_warehouse_document');
        Schema::enableForeignKeyConstraints();
    }
}
