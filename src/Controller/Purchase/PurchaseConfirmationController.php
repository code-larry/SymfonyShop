<?php

namespace App\Controller\Purchase;

use DateTime;
use App\Entity\Purchase;
use App\Cart\CartService;
use App\Entity\PurchaseItem;
use App\Form\CartConfirmationType;
use App\Purchase\PurchasePersister;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchaseConfirmationController extends AbstractController
{
	protected $persister;

	public function __construct(PurchasePersister $persister)
	{
		$this->persister = $persister;
	}

	/**
	 * @Route("/purchase/confirm", name="purchase_confirm")
	 * @IsGranted("ROLE_USER", message="Vous devez être connecté pour confirmer une commande")
	 */
	public function confirm(Request $request, CartService $cartService)
	{
		$form = $this->createForm(CartConfirmationType::class);

		$form->handleRequest($request);

		if(!$form->isSubmitted())
		{
			$this->addFlash('warning', 'Vous devez remplir le formulaire de confirmation');
			return $this->redirectToRoute('cart_show');
		}

		$cartItems = $cartService->getDetailedCartItems();

		if(count($cartItems) === 0)
		{
			$this->addFlash('warning', 'Vous ne pouvez pas confirmer une commande lorsque votre panier est vide !');
			return $this->redirectToRoute('cart_show');
		}

		/** @var Purchase */
		$purchase = $form->getData();

		$this->persister->storePurchase($purchase);

		$cartService->empty();

		$this->addFlash('success', 'Votre commande a bien été enregistrée !');

		return $this->redirectToRoute('purchase_index');
	}
}