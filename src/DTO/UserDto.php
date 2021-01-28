<?php

namespace App\DTO;

use App\Entity\AbstractEntity;
use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class UserDto extends AbstractDto  {

	/**
	 * @var string
	 * @Assert\NotBlank()
	 * @Assert\Length(max="250")
	 */
	public $name;

	/**
	 * @var string
	 * @Assert\NotBlank(groups={"add"})
	 */
	public $password;

	/**
	 * @var string
	 * @Assert\NotBlank(groups={"add"})
	 */
	public $passwordConfirm;

	/**
	 * @var string | null
	 * @Assert\NotBlank()
	 */
	public $address;

	/**
	 * @var string
	 * @Assert\Email()
	 * @Assert\NotBlank()
	 */
	public $mail;

	/**
	 * @param User $user
	 */
	public function setFromEntity(AbstractEntity $user): void {
		$this->address = $user->getAddress();
		$this->name    = $user->getName();
		$this->mail    = $user->getMail();
	}
}
