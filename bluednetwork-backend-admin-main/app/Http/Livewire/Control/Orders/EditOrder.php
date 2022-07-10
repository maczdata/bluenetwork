<?php

namespace App\Http\Livewire\Control\Orders;

use App\Models\Sales\Order;
use Livewire\Component;

class EditOrder extends Component
{
    public Order $order;
    public $orderStatus;

    public function mount(Order $order)
    {
        $this->order = $order;
        $this->orderStatus = $this->order->status;
    }
    public function render()
    {
        return view('control.livewire.orders.edit');
    }
}
