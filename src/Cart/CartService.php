<?php

namespace App\Cart;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService 
{
	protected $session;
	protected $productRepository;

	public function __construct(SessionInterface $session, ProductRepository $productRepository)
	{
		$this->session = $session;
		$this->productRepository = $productRepository;
	}

	// Cette fonction me permet de récupérer mon panier en session
	// Ou un panier vide
	protected function getCart() : array
	{
		return $this->session->get('cart', []);
	}

	// Cette fonction me permet de sauvegarder le contenu de mon panier
	// Après ajout, décrementation et suppression
	protected function saveCart(array $cart)
	{
		return $this->session->set('cart', $cart);
	}

	public function add(int $id)
	{
		// Retrouver le panier dans la session
		// Si le panier n'existe pas, alors prendre un tableau vide
		$cart = $this->getCart();

		// Voir si le produti existe déjé dans le tableau
		// Si c'est le cas, augmenter la quantité
		// Sinon ajouter le produit avec la quantité
		if(!array_key_exists($id, $cart))
		{
			$cart[$id] = 0;
		}

		$cart[$id]++;
		

		// Enregistrer le tableau dans la session
		$this->saveCart($cart);
	}

	public function remove(int $id)
	{
		$cart = $this->getCart();

		unset($cart[$id]);

		$this->saveCart($cart);
	}

	public function decrement(int $id)
	{
		$cart = $this->getCart();

		if(!array_key_exists($id, $cart))
		{
			return;
		}

		// Soit le produit est à 1 en quantité
		if($cart[$id] === 1)
		{
			$this->remove($id);
			return;
		}

		// Soit le produit est à une quantité > 1
		$cart[$id]--;
		
		$this->saveCart($cart);
	}


	public function getTotal() : int
	{
		$total = 0;

		foreach($this->getCart() as $id => $qty)
		{
			$product = $this->productRepository->find($id);

			if(!$product)
			{
				continue;
			}

			$total += $product->getPrice() * $qty;
		}

		return $total;
	}

	public function getDetailedCartItems() : array
	{
		$detailedCart = [];

		foreach($this->getCart() as $id => $qty)
		{
			$product = $this->productRepository->find($id);

			if(!$product)
			{
				continue;
			}

			$detailedCart[] = new CartItem($product, $qty);
		}

		return $detailedCart;
	}
}