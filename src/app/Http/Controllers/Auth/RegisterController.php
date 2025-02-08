<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Requests\RegisterRequest;

class RegisterController
{
    protected $createNewUser;

    public function __construct(CreateNewUser $createNewUser)
    {
        $this->createNewUser = $createNewUser;
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->createNewUser->create($request->validated());

        $user->sendEmailVerificationNotification();

        return redirect()->route('login')->with('message', '確認メールを送信しました。メールアドレスを確認してください。');
    }
}
