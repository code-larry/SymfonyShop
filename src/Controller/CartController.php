<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CartController extends AbstractController
{
    /**
     * @Route("/cart/add/{id}", name="cart_add", requirements={"id":"\d+"})
     */
    public function add($id, ProductRepository $productRepository, SessionInterface $session): Response
    {
		// Je vérifie si le produit existe
		$product = $productRepository->find($id);

		if(!$product)
		{
			throw $this->createNotFoundException("Le produit $id n'existe pas.");
		}
        // Retrouver le panier dans la session
		// Si le panier n'existe pas, alors prendre un tableau vide
		$cart = $session->get('cart', []);

		// Voir si le produti existe déjé dans le tableau
		// Si c'est le cas, augmenter la quantité
		// Sinon ajouter le produit avec la quantité
		if(array_key_exists($id, $cart))
		{
			$cart[$id]++;
		} else {
			$cart[$id] = 1;
		}

		// Enregistrer le tableau dans la session
		$session->set('cart', $cart);

		$this->addFlash('success', "Le produit a bien été ajouté au panier");

		return $this->redirectToRoute('product_show', [
			'category_slug' => $product->getCategory()->getSlug(),
			'slug' => $product->getSlug()
		]);
    }
}
