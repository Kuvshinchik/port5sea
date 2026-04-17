<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  // Требует логина для доступа
    }

    public function index()
    {
        return view('profile');  // Рендерит profile.blade.php
    }
}