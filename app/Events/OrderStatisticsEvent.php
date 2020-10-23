<?php


namespace App\Events;


use App\Models\Order;
use Illuminate\Queue\SerializesModels;

class OrderStatisticsEvent extends Event
{
use SerializesModels;

    public $order;

    public function __construct(Order $orders)
    {
     
        $this->order=$orders;
       $this->getOrders();
    }
function getOrders() {
   //var_dump($this->order);
    return $this->order;
}

}