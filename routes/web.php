<?php

use App\Models\User;
use App\Mail\TestIsmail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthContoller;
use App\Http\Controllers\Cobacontroller;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('belajar',function(){
    return 'Hello Laravel';
});

//route with parameter
Route::get('/detailsiswa/{id}', function ($id) {
    return "User ".$id;
});

Route::get('/berita/{slug}', function ($slug) {
    return "Detail Berita :  ".$slug;
});

//named Route
Route::get('/news', function () {
    return "Detail Berita : ";
})->name('baru');

//route with redirect
Route::redirect('/sinau','/belajar');

//route with sending value in view
Route::get('/namamu',function(){
    return view("welcome",[
        'nama' => "M. Ihwan Ngisomuddin"
    ]);
});

//route with controller
Route::get('/coba', [Cobacontroller::class, 'index']);
Route::get('/create',[Cobacontroller::class,'create'])->middleware('auth');
Route::post('/store',[Cobacontroller::class,'store']);
Route::get('/siswa/{id}/edit',[Cobacontroller::class,'edit'])->middleware('auth');
Route::put('/update',[Cobacontroller::class,'update']);
Route::get('/siswa/{id}',[Cobacontroller::class,'delete']);

Route::get('/register',[AuthContoller::class,'registerForm']);
Route::post('/processregister',[AuthContoller::class,'process']);
Route::get('/login',[AuthContoller::class,'login'])->name('login');
Route::post('/login',[AuthContoller::class,'loginproses']);
Route::get('/logout',[AuthContoller::class,'logout']);
Route::get('/forgot-password',[AuthContoller::class,'forgotpassword']);

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);
 
    $status = Password::sendResetLink(
        $request->only('email')
    );
 
    return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => __($status)])
                : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

Route::get('/reset-password/{token}', function (string $token) {
    return view('reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');


Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);
 
    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function (User $user, string $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));
 
            $user->save();
 
            event(new PasswordReset($user));
        }
    );
 
    return $status === Password::PASSWORD_RESET
                ? redirect()->route('login')->with('status', __($status))
                : back()->withErrors(['email' => [__($status)]]);
})->middleware('guest')->name('password.update');

Route::get('/kirimemail',function(){
    $name = "Ishomuddin";
    Mail::to('kangishom@gmail.com')->send(new TestIsmail($name));
    return 'Email Sent!';
});

//Assignments
Route::get('/about',function(){
    return view('about',[
        'nama'=> 'M. Ihwan Ngisomuddin',
        'gender'=> 'Laki-laki',
        'address'=> 'Bulu-Semen-Kediri',
    ]);
});
