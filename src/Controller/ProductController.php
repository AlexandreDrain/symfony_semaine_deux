<?php
namespace App\Controller;

use App\Entity\Category;
use App\Entity\Produit;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * Affiche une page HTML (Liste des produits)
     * @return Response
     */
    public function liste(): Response
    {
        // Récupération de .....
        $repository = $this->getDoctrine()->getRepository(Produit::class);
        //
        $products = $repository->findBy(["isPublished" => true]);
        //
        return $this->render('products/liste.html.twig', ['products' => $products]);
    }
    /**
     * Affiche une page HTML (Création d'un produit)
     * @var  Request
     * @return Response
     */
    public function create(Request $requestHTTP): Response
    {
        // Récupération du formulaire
        $product = new Produit();
        $formProduct = $this->createForm(ProductType::class, $product);
        //
        $formProduct->handleRequest($requestHTTP);
        // On vérifie que le formulaire est sommis et valide
        if ($formProduct->isSubmitted() && $formProduct->isValid()) {
            // On sauvegarde le produit en BDD grace a un manager
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($product);
            $manager->flush();

            // Ajout d'un message flash
            $this->addFlash('success', 'le produit a bien était ajouté');
            // Petite redirection des familles
            return $this->redirectToRoute('app_product_liste');
        }

        return $this->render(
            'products/create.html.twig',
            [
            'formProduct' => $formProduct->createView()
            ]
        );
    }

    /**
     * Affiche et traite le formulaire de modification d'un produit
     * @param Request $requestHTTP
     * @param Product $product
     * @return Response
     */
    public function update(Request $requestHTTP, string $slug): Response
    {
        // Récupération du formulaire
        $formProduct = $this->createForm(ProductType::class, $product);
        // On envoie les données postées au formulaire
        $formProduct->handleRequest($requestHTTP);
        // On vérifie que le formulaire est sommis et valide
        if ($formProduct->isSubmitted() && $formProduct->isValid()) {
            // On sauvegarde le produit en BDD grace a un manager
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            // Ajout d'un message flash
            $this->addFlash('warning', 'Le produit a bien était modifié');
            // Petite redirection des familles
            return $this->redirectToRoute('app_product_liste', [
            'formProduct' => $formProduct->createView()
            ]);
        }

        return $this->render(
            'products/modify.html.twig',
            [
            'formProduct' => $formProduct->createView()
            ]
        );
    }

    /**
     * Suppression d'un produit
     * @param Produit $product
     * @return Response
     */
    public function delete(Produit $product): Response
    {
        // On sauvegarde le produit en BDD grâce au manager
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($product);
        $manager->flush();
        // Ajout d'un message flash
        $this->addFlash('danger', 'Le produit est supprimé');
        return $this->redirectToRoute('app_product_liste');
    }
    /**
     * Affiche une page HTML (détails du produit)
     * @return Response
     */
    public function show(string $slug): Response
    {
        $repository = $this->getDoctrine()->getRepository(Produit::class);
        $product = $repository->findOneBy(['slug' => $slug, 'isPublished' => true]);
        if (!$product) {
            throw $this->createNotFoundException('Produit innexistant');
        }
         return $this->render('products/show.html.twig', ['product' => $product]);
    }
}
