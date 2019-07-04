<?php
namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProductType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

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
     * @param Request $requestHTTP
     * @param UserInterface $user
     * @param ObjectManager $manager
     * @return Response
     */
    public function create(Request $requestHTTP, ObjectManager $manager, UserInterface $user): Response
    {
        // Récupération du formulaire
        $product = new Produit();
        $formProduct = $this->createForm(ProductType::class, $product);
        //
        $formProduct->handleRequest($requestHTTP);
        // On vérifie que le formulaire est sommis et valide
        if ($formProduct->isSubmitted() && $formProduct->isValid()) {
            // On attribut l'utilisateur connecté en temps que publicateur de ce nouvel article
            $product->setPublisher($user);
            $manager->persist($product);
            $manager->flush();

            // Ajout d'un message flash
            $this->addFlash('success', 'le produit a bien était ajouté');
            // Petite redirection des familles
            return $this->redirectToRoute('app_products_liste');
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
     * @param ObjectManager $manager
     * @param Produit $product
     * @param UserInterface $user
     * @return Response
     */
    public function update(Request $requestHTTP, ObjectManager $manager, Produit $product, UserInterface $user): Response
    {
        if ($product->getPublisher() === $user || $this->isGranted('ROLE_MODERATEUR')) {
            // Récupération du formulaire
            $formProduct = $this->createForm(ProductType::class, $product);
            // On envoie les données postées au formulaire
            $formProduct->handleRequest($requestHTTP);
            // On vérifie que le formulaire est sommis et valide
            if ($formProduct->isSubmitted() && $formProduct->isValid()) {
                $manager->flush();

                // Ajout d'un message flash
                $this->addFlash('warning', 'Le produit a bien était modifié');
                // Petite redirection des familles
                return $this->redirectToRoute('app_products_liste', [
                    'formProduct' => $formProduct->createView()
                ]);
            }
            return $this->render(
                'products/modify.html.twig',
                [
                    'formProduct' => $formProduct->createView()
                ]
            );
        } else {
            throw $this->createAccessDeniedException(
                "Vous n'êtes pas le créateur de cette article, vous ne pouvez par concéquent pas modifier celui-ci"
            );
        }
    }

    /**
     * Suppression d'un produit
     * @param Produit $product
     * @param ObjectManager $manager
     * @param Request $request
     * @return Response
     */
    public function delete(Produit $product, ObjectManager $manager, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            // On sauvegarde le produit en BDD grâce au manager
            $manager->remove($product);
            $manager->flush();
            // Ajout d'un message flash
            $this->addFlash('danger', 'Le produit est supprimé');
        }
        return $this->redirectToRoute('app_products_liste');
    }

    /**
     * Affiche une page HTML (détails du produit)
     * @param string $slug
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
