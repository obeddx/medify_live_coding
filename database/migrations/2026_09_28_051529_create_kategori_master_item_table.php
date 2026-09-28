<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

public function up()
{
    Schema::create('kategori_master_item', function (Blueprint $table) {
        $table->foreignId('kategori_id')->constrained('kategoris')->cascadeOnDelete();
        $table->foreignId('master_item_id')->constrained('master_items')->cascadeOnDelete();
        $table->primary(['kategori_id', 'master_item_id']);
    });
}

public function down()
{
    Schema::dropIfExists('kategori_master_item');
}
};