<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/products")
 * @package AppBundle\Controller
 */
class ProductController extends Controller
{

    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $products = $this->getDoctrine()->getRepository('AppBundle:Product')->findAll();
        $products = $this->get('jms_serializer')->serialize($products, 'json');
        return new Response($products);
//        return new JsonResponse($products);
    }
}
