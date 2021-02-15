<?php

namespace App\Events;
class ProductStatisticsEvent
{

    public $data;

    public function __construct($product_id, $quantity,$store_id, $chain_id, $price, $category_id, $supplier_id)
    {
        $this->data['product_id'] = $product_id;
        $this->data['quantity'] = $quantity;
        $this->data['store_id'] = $store_id;
        $this->data['chain_id'] = $chain_id;
        $this->data['price'] = $price;
        $this->data['category_id'] = $category_id;
        $this->data['supplier_id'] = $supplier_id;

    }
}
