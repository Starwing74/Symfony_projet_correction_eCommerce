<?php

namespace App\Services;

use App\DTO\AbstractDto;
use App\DTO\ProductDto;
use App\Entity\AbstractEntity;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProductService extends AbstractEntityService {

	/**
	 * @var ContainerInterface
	 */
	private $container;

	public function __construct(ContainerInterface $container, ProductRepository $productRepository) {
		parent::__construct($productRepository);
		$this->container = $container;
	}

	/**
	 * @param ProductDto $productDto
	 * @param Product $product
	 */
	public function addOrUpdate(AbstractDto $productDto, AbstractEntity $product): void {
		$this->saveFile($productDto);

		if ($product->getPhoto()) {
			$this->deleteFile($product);
		}

		parent::addOrUpdate($productDto, $product);
	}

	/**
	 * @param Product $product
	 */
	public function delete(AbstractEntity $product): void {
		if (!$product->getOrderLines()->isEmpty()) {
			throw new Exception('Le produit a déjà été commandé');
		}
		parent::delete($product);

		if ($product->getPhoto()) {
			$this->deleteFile($product);
		}
	}

	private function deleteFile(Product $product): void {
		@unlink($this->container->getParameter('kernel.project_dir') . '/public/' . $product->getPhoto());
	}

	private function saveFile(ProductDto $productDto): void {
		if ($productDto->photo) {
			$newFilename  = uniqid('', true) . '.' . $productDto->photo->guessExtension();

			try {
				$productDto->photo = $productDto->photo->move(
					$this->container->getParameter('upload_dir'),
					$newFilename
				);
			} catch (FileException $e) {
				throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'Erreur dans l\'enregistrement du fichier', $e);
			}
		}
	}
}
