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
    public function add(Product $product, string $typeCart, ?int $amount = 1): void
    {
        $cart = $this->get();
        $cartProductsIds = array_column($cart[$typeCart], 'id');
        $product->amount = !empty($amount) ? $amount : 1;
        $product->updated_at_ = now();

        // if (in_array($product->id, $cartProductsIds)) {
        //     $cart[$typeCart] = $this->productCartIncrement($product->id, $cart[$typeCart]);
        //     $this->set($cart);
        //     return;
        // }

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

    public function clearOrder(): void
    {
        $this->set($this->emptyOrder());
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

    public function emptyOrder()
    {
        $cart = $this->get();
        unset($cart['products']);

        $cart['products'] = [];

        $this->set($cart);
    }

    public function emptySale()
    {
        $cart = $this->get();
        unset($cart['products_sale']);

        $cart['products_sale'] = [];

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
                $item['updated_at_'] = now();
            }

            return $item;
        }, $cartItems);

        return $cartItems;
    }

    /**
     * Returns total order of the items in the cart.
     *
     * @return int
     */
    public function totalOrder(): int
    {
        $content = $this->get()['products'];

        $total = 0;

        foreach($content as $content){

            if($content['amount'] > 0) {
                $total += $content['amount'];
            }
        }

        return $total;
    }

    /**
     * Returns total sale of the items in the cart.
     *
     * @return int
     */
    public function totalSale(): int
    {
        $content = $this->get()['products_sale'];

        $total = 0;

        foreach($content as $content){

            if($content['amount'] > 0) {
                $total += $content['amount'];
            }
        }

        return $total;
    }

    /**
     * Returns total price of the items in the order cart.
     *
     * @return string
     */
    public function totalPriceOrder(string $typeCart): string
    {
        $content = $this->get()[$typeCart];

        $total = 0;

        foreach($content as $content){

            if($content['amount'] > 0) {
                $total += $this->priceReal($content, $typeCart) * $content['amount'];
            }
        }

        return number_format($total, 2, '.', '');
    }

    /**
     * Returns total price of the items in the order cart.
     *
     * @return string
     */
    public function totalPriceOrderWithIva(string $typeCart): string
    {
        $content = $this->get()[$typeCart];

        $total = 0;

        foreach($content as $content){

            if($content['amount'] > 0) {
                $total += $this->priceRealWithIva($content, $typeCart) * $content['amount'];
            }
        }

        return number_format($total, 2, '.', '');
    }

    /**
     * Return price of the item in the cart.
     *
     * @return string
     */
    public function priceReal($product, string $typeCart): string
    {
        $cart = $this->get();

        $cartProductsIds = array_column($cart[$typeCart], 'id');

        if (in_array($product->id, $cartProductsIds)) {

            if($cart['user']){
                return $product->getPrice($cart['user'][0]->customer->type_price ?? 'retail');
            }
            elseif($cart['departament']){
                return $product->getPrice($cart['departament'][0]->type_price  ?? 'retail');
            }
            else{
                return $product->getPrice('retail');
            }
        }

        return $product->getPrice('retail');
    }

    /**
     * Return price of the item in the cart.
     *
     * @return string
     */
    public function priceRealWithIva($product, string $typeCart): string
    {
        $cart = $this->get();

        $cartProductsIds = array_column($cart[$typeCart], 'id');

        if (in_array($product->id, $cartProductsIds)) {

            if($cart['user']){
                return $product->getPriceWithIva($cart['user'][0]->customer->type_price ?? 'retail');
            }
            elseif($cart['departament']){
                return $product->getPriceWithIva($cart['departament'][0]->type_price  ?? 'retail');
            }
            else{
                return $product->getPriceWithIva('retail');
            }
        }

        return $product->getPriceWithIva('retail');
    }
}
