<?php

namespace App\Form;

use App\DTO\ProductDto;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProductType extends AbstractType {

	/** @var EntityManagerInterface */
	private $entityManager;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('name', TextType::class)
			->add('description', TextareaType::class)
			->add('price', NumberType::class)
			->add('category', EntityType::class, [
				'class' => Category::class,
				'choices' => $this->entityManager->getRepository(Category::class)->findAll(),
				'choice_label' => 'name'
			])
			->add('photo', FileType::class, [
				'constraints' => [
					new File([
						'maxSize' => '4096k',
						'mimeTypes' => [
							'image/*'
						]
					])
				]
			])
		;
	}

	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults([
			'data_class' => ProductDto::class,
			'required' => false
		]);
	}
}
