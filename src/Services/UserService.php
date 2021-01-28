<?php

namespace App\Services;

use App\DTO\AbstractDto;
use App\DTO\UserDto;
use App\Entity\AbstractEntity;
use App\Entity\User;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserService extends AbstractEntityService {

	/**
	 * @var UserPasswordEncoderInterface
	 */
	private $passwordEncoder;

	public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder) {
		parent::__construct($userRepository);
		$this->passwordEncoder = $passwordEncoder;
	}

	/**
	 * @param UserDto $dto
	 * @param User $entity
	 */
	public function addOrUpdate(AbstractDto $dto, AbstractEntity $entity): void {
		if ($dto->mail !== $entity->getMail()) {
			$userWithNewMail = $this->repository->findByMail($dto->mail);
			if ($userWithNewMail) {
				throw new Exception('Il y a déjà un utilisateur avec cette adresse mail');
			}
		}
		if ($dto->password) {
			$dto->password = $this->encodePassword($entity, $dto->password);
		}
		parent::addOrUpdate($dto, $entity);
	}

	public function encodePassword(UserInterface $user, string $value): string {
		return $this->passwordEncoder->encodePassword($user, $value);
	}

	public function isPasswordValid(UserInterface $user, string $value): bool {
		return $this->passwordEncoder->isPasswordValid($user, $value);
	}

	public function updateLastLogin(User $user): void {
		$user->setLastLogin(new DateTime());
		$this->repository->save($user);
	}
}
