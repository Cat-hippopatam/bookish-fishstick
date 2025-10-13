<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // up() - что делать при применении миграции
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id(); // Автоинкрементный первичный ключ
            $table->string('category'); // Поле типа VARCHAR
            $table->decimal('price_per_person', 8, 2); // DECIMAL(8,2)
            $table->text('characteristics'); // TEXT поле
            $table->string('image_path'); // Путь к изображению
            $table->boolean('is_available')->default(true); // BOOLEAN с значением по умолчанию
            $table->timestamps(); // created_at и updated_at
        });
    }

    // down() - что делать при откате миграции  
    public function down()
    {
        Schema::dropIfExists('rooms'); // Удалить таблицу
    }
};