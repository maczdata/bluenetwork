<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           PasswordForm.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     21/08/2021, 1:29 AM
 */

namespace App\Http\Livewire\Control;

use App\Repositories\Users\UserRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Class PasswordForm
 * @package App\Http\Livewire\Control
 */
class PasswordForm extends Component
{
    /**
     * The component's state.
     *
     * @var array
     */
    public array $state = [
        'current_password' => '',
        'password' => '',
        'password_confirmation' => '',
    ];

    /**
     * Update the user's password.
     *
     * @param UserRepository $userRepository
     */
    public function updatePassword(UserRepository $userRepository)
    {
        $user = \auth('dashboard')->user();
        $passwordRules = [
            'state.password' => 'required|confirmed',
            'state.password_confirmation' => 'required'
        ];
        if (!is_null($user->password)) {
            $passwordRules['state.current_password'] = 'required|password:user';
        }
        //$updater->update(Auth::user(), $this->state);
        $this->validate($passwordRules, [
            'state.current_password.required' => 'Current password is required',
            'state.current_password.password' => 'Current password is wrong',
            'state.password.required' => 'New password is required',
            'state.password_confirmation.required' => 'Please confirm new password',
        ]);
        $userRepository->updateUser(auth('dashboard')->user(), request()->merge($this->state));
        $this->state = [
            'current_password' => '',
            'password' => '',
            'password_confirmation' => '',
        ];

        $this->emit('saved');
    }

    /**
     * Get the current user of the application.
     *
     * @return Authenticatable|null
     */
    public function getUserProperty(): ?Authenticatable
    {
        return auth('dashboard')->user();
    }

    /**
     * Render the component.
     *
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function render(): \Illuminate\Contracts\View\View|Factory|Application
    {
        return view('control.account.profile.password');
    }
}
