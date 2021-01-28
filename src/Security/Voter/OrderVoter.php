<?php

namespace App\Security\Voter;

use App\Entity\Order;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class OrderVoter extends Voter {

	const VIEW = 'view';

	/**
	 * @inheritDoc
	 */
	protected function supports($attribute, $subject) {
		// if the attribute isn't one we support, return false
		if (!in_array($attribute, [self::VIEW], true)) {
			return false;
		}

		// only vote on Post objects inside this voter
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

		// you know $subject is a Post object, thanks to supports
		/** @var Order $order */
		$order = $subject;

		switch ($attribute) {
			case self::VIEW:
				return $user->getId() === $order->getUser()->getId();
		}
	}
}
