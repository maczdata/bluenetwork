<?php

namespace App\Http\Livewire\Control\Users;

use App\Models\Users\User;
use App\Models\Users\UserProfile;
use LivewireUI\Modal\ModalComponent;

class DeleteUser extends ModalComponent
{
    public User $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function delete()
    {
        UserProfile::where('user_id', $this->user->id)->forceDelete();
        $deleted = User::where('id', $this->user->id)->forceDelete();
        if ($deleted) {
            flash('User deleted successfully')->success();
        } else {
            flash('Unable to delete user\'s account')->error();
        }
        $this->closeModal();
        return redirect()->route('control.user.list');
    }
    
    public function render()
    {
        return view('control.livewire.users.delete');
    }
}
