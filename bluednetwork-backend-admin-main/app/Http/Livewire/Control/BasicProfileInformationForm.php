<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           BasicProfileInformationForm.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     21/08/2021, 1:29 AM
 */

namespace App\Http\Livewire\Control;

use App\Repositories\Users\UserRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Class BasicProfileInformationForm
 * @package App\Http\Livewire\Control
 */
class BasicProfileInformationForm extends Component
{
    use WithFileUploads;

    /**
     * The component's state.
     *
     * @var array
     */
    public array $state = [];

    /**
     * The new avatar for the user.
     *
     * @var mixed
     */
    public $profile_image;

    /**
     * Prepare the component.
     *
     */
    public function mount()
    {
        $this->state = auth('dashboard')->user()->withoutRelations()->toArray();
    }

    /**
     * Update the user's profile information.
     */
    public function updateProfileInformation(UserRepository $userRepository)
    {
        $this->validate([
            'state.first_name' => ['required', 'string', 'max:255'],
            'state.last_name' => ['required', 'string', 'max:255'],
            'state.email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('admin', 'email')->ignore($this->state['id']),
            ]
        ], [
            'state.first_name.required' => 'First name is required',
            'state.last_name.required' => 'Last name is required',
            'state.email.required' => 'Email address is required',
            'state.email.email' => 'Email address is invalid',
            'state.email.unique' => 'Email address not available'
        ]);
        $data = array_merge($this->state, [
            'profile_image' => $this->profile_image ?? null,
        ]);
        $userRepository->updateUser(auth('dashboard')->user(), request()->merge($data));

        $this->emit('saved');

        $this->emit('refresh-navigation-menu');
    }

    /**
     * Delete user's profile photo.
     *
     * @return void
     */
    public function deleteProfilePhoto()
    {
        //Auth::user()->deleteProfilePhoto();

        $this->emit('refresh-navigation-menu');
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
     * @return View
     */
    public function render(): View
    {
        return view('control.account.profile.basic');
    }
}
