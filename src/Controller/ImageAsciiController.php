<?php

namespace App\Controller;

use App\DTO\ImageAscii;
use App\Form\ImageAsciiType;
use claviska\SimpleImage;
use Spatie\Color\Hsl;
use Spatie\Color\Rgb;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImageAsciiController extends AbstractController
{
    /**
     * @Route("/image_ascii", name="image_ascii", methods={"GET", "POST"})
     */
    public function index(Request $request): Response
    {
	    $imageAscii = new ImageAscii();

	    $form = $this->createForm(
		    ImageAsciiType::class,
		    $imageAscii
	    );
	    $form->handleRequest($request);

	    if ($form->isSubmitted() && $form->isValid()) {
	    	$image = new SimpleImage();
		    $image->fromFile($imageAscii->image->getRealPath());

		    $ascii = [];

		    for ($y = 0; $y < $image->getHeight(); $y ++) {
		    	$ascii[$y] = [];

			    for ($x = 0; $x < $image->getWidth(); $x ++) {
				    $color = $image->getColorAt($x, $y);
				    $red   = $color['red'];
				    $green = $color['green'];
				    $blue  = $color['blue'];

				    $colorRgb = new Rgb($red, $green, $blue);
				    $hsl   = $colorRgb->toHsl();

				    $ascii[$y][$x] = $this->getCharFromHsl($hsl);
			    }
		    }

		    return $this->render('image_ascii/index.html.twig', [
			    'form' => $form->createView(),
			    'data' => $ascii
		    ]);
	    }

	    return $this->render('image_ascii/index.html.twig', [
		    'form' => $form->createView(),
		    'data' => $imageAscii
	    ]);
    }

	function getCharFromHsl(Hsl $hsl): string {
		return $hsl->lightness() >= 50 ? '-' : '0';
	}
}
