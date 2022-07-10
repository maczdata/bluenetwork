<?php

namespace App\Http\Livewire\Control\Orders;

use App\Helpers\Utils;
use App\Models\Finance\Transaction;
use App\Models\Sales\Order;
use App\Notifications\WalletCredited;
use App\Repositories\Common\TransactionRepository;
use App\Repositories\Common\WalletRepository;
use LivewireUI\Modal\ModalComponent;

class UpdateStatus extends ModalComponent
{
    private WalletRepository $walletRepository;
    private TransactionRepository $transactionRepository;
    public Order $order;
    public $status;

    public function mount(Order $order, string $status)
    {
        $this->order = $order;
        $this->status = $status;
    }

    public function update()
    {
        $updated = $this->order->update([
            'status' => $this->status,
        ]);


        if ($updated) {
            $amountToPay = $this->order->meta->where('key', 'amount_to_give')->first();

            if (
                $this->order->orderable?->parent?->key === "airtime-for-cash" &&
                $this->status === "completed"
            ) {
                $refNumber = "SYS-" . Utils::generateTransactionRef();

                $this->creditUserViaSystem($this->order->user, $amountToPay->value, $refNumber);
            }
            flash('Order status updated successfully')->success();
        } else {
            flash('Unable to update order status')->error();
        }
        $this->closeModal();
        return redirect(request()->header('Referer'));
    }

    private function creditUserViaSystem($user, $amount, $refNumber)
    {
        $this->transactionRepository = app(TransactionRepository::class);
        $transaction = $this->transactionRepository->scopeQuery(function ($query) use ($refNumber) {
            return $query->whereHasMeta('sys_transaction_id')->whereMeta('sys_transaction_id', $refNumber);
        })->count();
        if ($transaction != false) {
            flash('Fraudulent activity detected')->error();
        }
        try {
            $this->walletRepository = app(WalletRepository::class);
            $this->walletRepository->deposit($user, $amount, ['sys_transaction_id' => $refNumber]);
            $user->notify(new WalletCredited($amount, $user->id, 'system'));

            flash('Your wallet has been credited')->success();
        } catch (\Exception $exception) {
            logger()->error('wallet crediting error : ' . $exception);

            flash('Unable to credit wallet ')->error();
        }
    }
    public function render()
    {
        return view('control.livewire.orders.update-status');
    }
}
