<?php
// Пространство имен - указывает где находится класс
namespace App\Http\Controllers;

use App\Models\Room; // Импортируем модель Room
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Главная страница - список всех номеров
     */
    public function index()
    {
        // Получаем все номера из базы данных
        $rooms = Room::all();
        
        // Возвращаем представление index.blade.php 
        // и передаем туда переменную $rooms
        return view('index', compact('rooms'));
    }
}