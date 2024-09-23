<?php

use App\Http\Controllers\ProfileController;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome', [
        'users' => User::all()
    ]);
});

Route::get('/chat/{user}', function (User $user) {
    $messages = ChatMessage::query()->where(function ($query) use ($user) {
        $query->where('sender_id', auth()->id())
            ->where('receiver_id', $user->id);
    })
        ->orWhere(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->where('receiver_id', auth()->id());
        })
        ->with('sender', 'receiver')
        ->orderBy('created_at', 'desc');

    return view('chat', [
        'user' => $user,
        'messages' => $messages->get()
    ]);
})->middleware('auth')->name('user.chat');

//Route::get('/messages/{user}', function (User $user) {
//    return ChatMessage::query()->where(function ($query) use ($user) {
//        $query->where('sender_id', auth()->id())
//            ->where('receiver_id', $user->id);
//    })
//        ->orWhere(function ($query) use ($user) {
//            $query->where('sender_id', $user->id)
//                ->where('receiver_id', auth()->id());
//        })
//        ->with('sender', 'receiver')
//        ->orderBy('created_at', 'desc');
//});

Route::post('/messages/{user}', function (User $user) {
    return ChatMessage::create([
        'sender_id' => auth()->id(),
        'receiver_id' => $user->id,
        'message' => request('message')
    ]);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
