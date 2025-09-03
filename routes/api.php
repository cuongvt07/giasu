<?php 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Helpers\CurrencyHelper;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route API cho việc định dạng tiền tệ
Route::get('/format-currency', function (Request $request) {
    $amount = $request->input('amount', 0);
    return response()->json([
        'amount' => $amount,
        'formatted' => CurrencyHelper::formatVND($amount)
    ]);
}); 