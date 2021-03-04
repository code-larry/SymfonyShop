<?php

namespace App\Controller\Purchase;

use Twig\Environment;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class PurchasesListController extends AbstractController
{
	protected $security;
	protected $router;
	protected $twig;

	public function __construct(Security $security, RouterInterface $router, Environment $twig)
	{
		$this->security = $security;
		$this->router = $router;
		$this->twig = $twig;
	}
	/**
	 * @Route("/purchases", name="purchase_index")
	 */
	public function index()
	{
		// Vérifier que la personne est connectée
		/** @var User */
		$user = $this->security->getUser();

		if(!$user)
		{
			throw new AccessDeniedException("Vous devez être connecté pour accéder à vos commande !");
		}

		// Nous voulons savoir qui est connecté
		// Nous voulons passer l'utilisateur connecté à Twig
		$html = $this->twig->render('purchase/index.html.twig', [
			'purchases' => $user->getPurchases()
		]);

		return new Response($html);
	}
}