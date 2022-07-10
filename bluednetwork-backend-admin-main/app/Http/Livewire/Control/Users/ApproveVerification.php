<?php

namespace App\Http\Livewire\Control\Users;

use App\Models\Users\User;
use App\Models\Users\UserProfile;
use LivewireUI\Modal\ModalComponent;

class ApproveVerification extends ModalComponent
{
    public User $user;
    public string $type;

    public function mount(User $user, string $type)
    {
        $this->user = $user;
        $this->type = $type;
    }

    public function approve()
    {
        $user = User::find($this->user->id);
        if ($this->type === "email") {
            $user->email_verified_at = now();
            $user->profile->updated_by = auth('dashboard')->user()->id;
        }

        if ($this->type === "phone") {
            $user->phone_verified_at = now();
            $user->profile->updated_by = auth('dashboard')->user()->id;
        }

        if ($this->type === "bvn") {
            $user->profile->bvn_verified_at = now();
            $user->profile->updated_by = auth('dashboard')->user()->id;
        }

        if ($this->type === "proofOfAddress") {
            $user->profile->proof_of_address_verified_at = now();
            $user->profile->proof_of_address_verified_by = auth('dashboard')->user()->id;
            if(optional(optional($user)->profile)->acount_level_id < 4 )
            {
                    UserProfile::where(['user_id' => $user->id])->update([
                        'account_level_id' => 4,
                    ]);
            }
        }

        if ($this->type === "identity") {
            $user->profile->identity_verified_at = now();
            $user->profile->identity_verified_by = auth('dashboard')->user()->id;

            if(optional(optional($user)->profile)->acount_level_id < 4 )
            {
                    UserProfile::where(['user_id' => $user->id])->update([
                        'account_level_id' => 4,
                    ]);
            }
        }
        $user->profile->save();
        $user->save();

        $user = User::find($this->user->id);
        if ($this->type === "proofOfAddress") {
            if(optional(optional($user)->profile)->acount_level_id < 4 )
            {
                    UserProfile::where(['user_id' => $user->id])->update([
                        'account_level_id' => 3,
                    ]);
            }
        }

        if ($this->type === "identity") {
            if(optional(optional($user)->profile)->acount_level_id >= 3 )
            {
                    UserProfile::where(['user_id' => $user->id])->update([
                        'account_level_id' => 4,
                    ]);
            }
        }
        
        flash($this->type . ' verified successfully')->success();
        
        $this->closeModal();
        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        return view('control.livewire.users.approve-verification');
    }
}
