<?php

namespace App\Helpers;

use App\Models\Product;
use App\Domains\Auth\Models\User;
use App\Models\Departament;

class Cart
{
    public function __construct()
    {
        if($this->get() === null)
            $this->set($this->empty());
    }

    /*
     * typeCart: products or products_sale
     *
     */
    public function add(Product $product, string $typeCart): void
    {
        $cart = $this->get();
        $cartProductsIds = array_column($cart[$typeCart], 'id');
        $product->amount = !empty($product->amount) ? $product->amount : 1;

        if (in_array($product->id, $cartProductsIds)) {
            $cart[$typeCart] = $this->productCartIncrement($product->id, $cart[$typeCart]);
            $this->set($cart);
            return;
        }

        array_push($cart[$typeCart], $product);
        $this->set($cart);
    }

    public function addUser(User $user): void
    {
        $cart = $this->get();
        $cartUserId = array_column($cart['user'], 'id');

        array_splice($cart['user'], 0, 1);

        array_push($cart['user'], $user);
        $this->set($cart);
    }

    public function addDepartament(Departament $departament): void
    {
        $cart = $this->get();
        $cartDepartamentId = array_column($cart['departament'], 'id');

        array_splice($cart['departament'], 0, 1);

        array_push($cart['departament'], $departament);
        $this->set($cart);
    }

    public function remove(int $productId, string $typeCart): void
    {
        $cart = $this->get();
        array_splice($cart[$typeCart], array_search($productId, array_column($cart[$typeCart], 'id')), 1);
        $this->set($cart);
    }

    public function clear(): void
    {
        $this->set($this->empty());
    }

    public function clearUser(): void
    {
        $cart = $this->get();
        array_splice($cart['user'], 0, 1);
        $this->set($cart);
    }

    public function clearDepartament(): void
    {
        $cart = $this->get();
        array_splice($cart['departament'], 0, 1);
        $this->set($cart);
    }

    public function empty(): array
    {
        return [
            'products' => [],
            'products_sale' => [],
            'user' => [],
            'departament' => [],
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