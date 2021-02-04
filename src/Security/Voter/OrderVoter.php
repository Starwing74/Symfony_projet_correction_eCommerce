<?php

namespace App\Security\Voter;

use App\Entity\Order;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class OrderVoter extends Voter {

	const VIEW = 'view';

	/**
	 * @var Security
	 */
	private Security $security;

	public function __construct(Security $security) {
		$this->security = $security;
	}

	/**
	 * @inheritDoc
	 */
	protected function supports($attribute, $subject) {
		if ($attribute !== self::VIEW) {
			return false;
		}

		if (!$subject instanceof Order) {
			return false;
		}

		return true;
	}

	/**
	 * @inheritDoc
	 */
	protected function voteOnAttribute($attribute, $subject, TokenInterface $token) {
		/** @var User $user */
		$user = $token->getUser();

		if ($this->security->isGranted('ROLE_ADMIN')) {
			return true;
		}

		/** @var Order $order */
		$order = $subject;

		switch ($attribute) {
			case self::VIEW:
				return $user->getId() === $order->getUser()->getId();
			default:
				return false;
		}
	}
}
