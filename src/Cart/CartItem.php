<?php

namespace App\Cart;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartItem 
{
	public $product;
	public $qty;

	public function __construct(Product $product, int $qty)
	{
		$this->product = $product;
		$this->qty = $qty;
	}

	public function getTotal() : int
	{
		return $this->product->getPrice() * $this->qty;
	}
}