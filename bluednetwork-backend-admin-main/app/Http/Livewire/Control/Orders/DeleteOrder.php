<?php

namespace App\Http\Livewire\Control\Orders;

use App\Models\Sales\Order;
use LivewireUI\Modal\ModalComponent;

class DeleteOrder extends ModalComponent
{
    public Order $order;

    public function mount(Order $order)
    {
        $this->order = $order;
    }

    public function delete()
    {
        $deleted = Order::destroy($this->order->id);
        if ($deleted) {
            flash('Order deleted successfully')->success();
        } else {
            flash('Unable to delete order')->error();
        }
        $this->closeModal();
        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        return view('control.livewire.orders.delete');
    }
}
