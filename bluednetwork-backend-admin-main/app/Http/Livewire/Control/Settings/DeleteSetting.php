<?php

namespace App\Http\Livewire\Control\Settings;

use App\Models\Common\Setting;
use LivewireUI\Modal\ModalComponent;

class DeleteSetting extends ModalComponent
{
    public Setting $setting;

    public function mount(Setting $setting)
    {
        $this->setting = $setting;
    }

    public function delete()
    {
        $deleted = Setting::destroy($this->setting->id);
        if ($deleted) {
            flash('Setting deleted successfully')->success();
        } else {
            flash('Unable to delete Setting\'s account')->error();
        }
        $this->closeModal();
        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        return view('control.livewire.settings.delete-setting');
    }
}
