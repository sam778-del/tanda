<?php


namespace Modules\Merchant\Http\Services;


use Modules\Merchant\Models\Order;

class OrderServices
{
    public function fetchOrders()
    {
        return Order::where('merchant_id', auth()->user()->id)->paginate();
    }

    public function getSingleOrder(Order $order)
    {
        return Order::findOrFail($order->id);
    }

    public function refundOrder()
    {

    }
}
