<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/products")
 * @package AppBundle\Controller
 */
class ProductController extends Controller
{
    /**
     * @Route("/", methods={"GET","HEAD"})
     */
    public function indexAction()
    {
        $products = $this->getDoctrine()->getRepository('AppBundle:Product')->findAll();
        $products = $this->get('jms_serializer')->serialize($products, 'json');
        return new Response($products);
//        return new JsonResponse($products);
    }

    /**
     * @Route("/{id}", methods={"GET","HEAD"})
     */
    public function getAction(Product $product)
    {
        $products = $this->get('jms_serializer')->serialize($product, 'json');
        return new Response($products);
//        return new JsonResponse($products);
    }

    /**
     * @Route("", methods={"POST"})
     */
    public function saveAction(Request $request)
    {
        $data = json_decode($request->getContent(), 1);
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);
        $form->submit($data);

        $doctrine = $this->getDoctrine()->getManager();
        $doctrine->persist($product);
        $doctrine->flush();

        $data = $this->get('jms_serializer')->serialize($product, 'json');
        return new Response($data);
    }

    /**
     * @Route("/{id}", methods={"PUT"})
     */
    public function updateAction(Request $request, Product $product)
    {
        $data = json_decode($request->getContent(), 1);
        $data['name']        = $data['name']        ?? $product->getName();
        $data['description'] = $data['description'] ?? $product->getDescription();
        $data['slug']        = $data['slug']        ?? $product->getSlug();

        $product->setName($data['name']);
        $product->setDescription($data['description']);
        $product->setSlug($data['slug']);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($product);
        $entityManager->flush();

        $data = $this->get('jms_serializer')->serialize($product, 'json');
        return new Response($data);
    }

    /**
     * @Route("/{id}", methods={"DELETE"})
     */
    public function deleteAction(Product $product)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($product);
        $entityManager->flush();

        return new Response("deleted");
    }
}
