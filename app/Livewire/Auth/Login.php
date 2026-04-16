<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Login - Sistim Payroll')]
class Login extends Component
{
    public $email = '';
    public $password = '';

    public function authenticate()
    {
        //validasi input
        $scredentials = $this->validate([
            'email' => 'required | email',
            'password' => 'required',
        ]);

        //Coba Login
        if(Auth::attempt($scredentials)) {
            session() ->regenerate();

            return redirect()->intended('/');

        }

        $this->addError('email', 'Email atau Password Salah');
    }
    public function render()
    {
        return view('livewire.auth.login');
    }
}
