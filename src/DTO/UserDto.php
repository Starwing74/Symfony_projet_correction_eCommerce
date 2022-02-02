<?php

namespace App\DTO;

use App\Entity\AbstractEntity;
use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class UserDto extends AbstractDto  {

	#[Assert\NotBlank]
	#[Assert\Length(max: 250)]
	public string $name;

	#[Assert\NotBlank(groups: ["add"])]
	public ?string $password = null;

	#[Assert\NotBlank(groups: ["add"])]
	public ?string $passwordConfirm = null;

	#[Assert\NotBlank]
	public ?string $address = null;

	#[Assert\NotBlank]
	#[Assert\Email]
	public string $mail;

	/**
	 * @param User $user
	 */
	public function setFromEntity(AbstractEntity $user): void {
		$this->address = $user->getAddress();
		$this->name    = $user->getName();
		$this->mail    = $user->getMail();
	}
}
