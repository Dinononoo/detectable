<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('day'); // วัน เช่น Monday, Tuesday
            $table->time('start_time'); // เวลาที่เริ่มว่าง
            $table->time('end_time'); // เวลาที่สิ้นสุดว่าง
            $table->unsignedBigInteger('image_id'); // เชื่อมกับ uploaded_images.id
            $table->timestamps(); // created_at, updated_at

            // Foreign Key Constraints
            $table->foreign('image_id')->references('id')->on('uploaded_images')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('schedules');
    }
}
