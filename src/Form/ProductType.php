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

	private EntityManagerInterface $entityManager;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('name', TextType::class, [
				'label' => 'Nom'
			])
			->add('description', TextareaType::class, [
				'label' => 'Description'
			])
			->add('price', NumberType::class, [
				'html5' => true,
				'attr' => [
					'min' => 0.01,
					'step' => 0.01,
				],
				'label' => 'Prix'
			])
			->add('category', EntityType::class, [
				'class' => Category::class,
				'choices' => $this->entityManager->getRepository(Category::class)->findAll(),
				'choice_label' => 'name',
				'label' => 'Catégorie'
			])
			->add('photo', FileType::class, [
				'label' => 'Photo',
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
