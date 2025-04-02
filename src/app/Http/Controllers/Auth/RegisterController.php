<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Actions\Fortify\CreateNewUser;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    protected $createNewUser;

    public function __construct(CreateNewUser $createNewUser)
    {
        $this->createNewUser = $createNewUser;
    }

    public function store(RegisterRequest $request)
    {
        $user = $this->createNewUser->create($request->validated());

        $user->sendEmailVerificationNotification();

        $user->update(['is_first_login' => false]);

        Auth::login($user);

        return redirect()->route('attendance.create')->with('message', '確認メールを送信しました。メールアドレスを確認してください。');
    }
}
