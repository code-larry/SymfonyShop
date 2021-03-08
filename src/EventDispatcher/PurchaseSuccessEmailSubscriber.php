<?php

namespace App\EventDispatcher;

use App\Entity\User;
use App\Event\PurchaseSuccessEvent;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PurchaseSuccessEmailSubscriber implements EventSubscriberInterface
{
	protected $mailer;
	protected $security;

	public function __construct(MailerInterface $mailer, Security $security)
	{
		$this->mailer = $mailer;
		$this->security = $security;
	}
	
	public static function getSubscribedEvents()
	{
		return [
			'purchase.success' => 'sendSuccessEmail'
		];
	}

	public function sendSuccessEmail(PurchaseSuccessEvent $purchaseSuccessEvent)
	{
		// On récupère l'utilisateur
		/** @var User */
		$user = $this->security->getUser();

		// On récupère la commande
		$purchase = $purchaseSuccessEvent->getPurchase();

		// On écrit le mail
		$email = new TemplatedEmail();

		$email->from(new Address("contact@symfonyshop.com"))
			->to(new Address($user->getEmail(), $user->getFullName()))
			->subject("Merci pour votre commande " . $purchase->getId())
			->htmlTemplate("emails/purchase_success.html.twig")
			->context([
				'purchase' => $purchase,
				'user' => $user
			]);
			

		// On envoie le mail
		$this->mailer->send($email);
	}
}