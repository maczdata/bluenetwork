<?php

namespace App\Http\Livewire\Control\Services\GiftCards\Categories;

use App\Models\Common\GiftCardCategory;
use LivewireUI\Modal\ModalComponent;

class DeleteCategory extends ModalComponent
{
    public GiftCardCategory $giftCardCategory;

    public function mount(GiftCardCategory $giftCardCategory)
    {
        $this->giftCardCategory = $giftCardCategory;
    }

    public function delete()
    {
        $deleted = GiftCardCategory::destroy($this->giftCardCategory->id);
        if ($deleted) {
            flash('Gift Card Category deleted successfully')->success();
        } else {
            flash('Unable to delete Gift Card Category')->error();
        }
        $this->closeModal();
        return redirect(request()->header('Referer'));
    }
 

    public function render()
    {
        return view('control.livewire.services.giftcards.categories.delete-category');
    }
}
