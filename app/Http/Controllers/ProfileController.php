<?php
namespace App\Http\Controllers;

use App\Models\UserPrizePoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  // Требует логина для доступа
    }

    public function index()
    {
        $user = Auth::user();

        $prizePoints = UserPrizePoint::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0]
        );

        return view('profile', compact('prizePoints'));  // Рендерит profile.blade.php
    }
}
