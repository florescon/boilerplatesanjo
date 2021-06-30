<?php

namespace App\Helpers;

use App\Models\Product;

class Cart
{
    public function __construct()
    {
        if($this->get() === null)
            $this->set($this->empty());
    }

    public function add(Product $product): void
    {
        $cart = $this->get();
        $cartProductsIds = array_column($cart['products'], 'id');
        $product->amount = !empty($product->amount) ? $product->amount : 1;

        if (in_array($product->id, $cartProductsIds)) {
            $cart['products'] = $this->productCartIncrement($product->id, $cart['products']);
            $this->set($cart);
            return;
        }

        array_push($cart['products'], $product);
        $this->set($cart);
    }


    public function add_sale(Product $product): void
    {
        $cart = $this->get();
        $cartProductsIds = array_column($cart['products_sale'], 'id');
        $product->amount = !empty($product->amount) ? $product->amount : 1;

        if (in_array($product->id, $cartProductsIds)) {
            $cart['products_sale'] = $this->productCartIncrement($product->id, $cart['products_sale']);
            $this->set($cart);
            return;
        }

        array_push($cart['products_sale'], $product);
        $this->set($cart);
    }

    public function remove(int $productId): void
    {
        $cart = $this->get();
        array_splice($cart['products'], array_search($productId, array_column($cart['products'], 'id')), 1);
        $this->set($cart);
    }


    public function removeSale(int $productId): void
    {
        $cart = $this->get();
        array_splice($cart['products_sale'], array_search($productId, array_column($cart['products_sale'], 'id')), 1);
        $this->set($cart);
    }

    public function clear(): void
    {
        $this->set($this->empty());
    }

    public function empty(): array
    {
        return [
            'products' => [],
            'products_sale' => [],
        ];
    }
    

    public function get(): ?array
    {
        return request()->session()->get('cart');
    }

    private function set($cart): void
    {
        request()->session()->put('cart', $cart);
    }

    private function productCartIncrement($productId, $cartItems)
    {
        $amount = 1;
        $cartItems = array_map(function ($item) use ($productId, $amount) {
            if ($productId == $item['id']) {
                $item['amount'] += $amount;
                $item['price'] = $item['price'];
            }

            return $item;
        }, $cartItems);

        return $cartItems;
    }
}
