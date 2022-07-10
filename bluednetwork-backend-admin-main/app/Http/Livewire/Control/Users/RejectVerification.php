<?php

namespace App\Http\Livewire\Control\Users;

use App\Models\Users\User;
use LivewireUI\Modal\ModalComponent;

class RejectVerification extends ModalComponent
{
    public User $user;
    public string $type;

    public function mount(User $user, string $type)
    {
        $this->user = $user;
        $this->type = $type;
    }

    public function reject()
    {
        $user = User::find($this->user->id);
        if ($this->type === "email") {
            $user->email_verified_at = null;
            $user->profile->updated_by = auth('dashboard')->user()->id;
        }

        if ($this->type === "phone") {
            $user->phone_verified_at = null;
            $user->profile->updated_by = auth('dashboard')->user()->id;
        }

        if ($this->type === "bvn") {
            $user->profile->bvn_verified_at = null;
            $user->profile->updated_by = auth('dashboard')->user()->id;
        }

        if ($this->type === "proofOfAddress") {
            $user->profile->proof_of_address_verified_at = null;
            $user->profile->proof_of_address_verified_by = auth('dashboard')->user()->id;
        }

        if ($this->type === "identity") {
            $user->profile->identity_verified_at = null;
            $user->profile->identity_verified_by = auth('dashboard')->user()->id;
        }
        $user->profile->save();
        $user->save();
        flash($this->type . ' rejected successfully')->success();
        
        $this->closeModal();
        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        return view('control.livewire.users.reject-verification');
    }
}
