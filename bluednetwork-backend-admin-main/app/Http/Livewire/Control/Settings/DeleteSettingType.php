<?php

namespace App\Http\Livewire\Control\Settings;

use App\Models\Common\SettingType;
use LivewireUI\Modal\ModalComponent;

class DeleteSettingType extends ModalComponent
{
    public SettingType $settingType;

    public function mount(SettingType $settingType)
    {
        $this->settingType = $settingType;
    }

    public function delete()
    {
        $deleted = SettingType::destroy($this->settingType->id);
        if ($deleted) {
            flash('Setting Type deleted successfully')->success();
        } else {
            flash('Unable to delete Setting type\'s account')->error();
        }
        $this->closeModal();
        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        return view('control.livewire.settings.delete-setting-type');
    }
}
