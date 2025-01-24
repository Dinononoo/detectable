<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadedImagesTable extends Migration
{
    public function up()
    {
        Schema::create('uploaded_images', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('filename'); // ชื่อไฟล์
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('uploaded_images');
    }
}
