<?php
namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TagController extends AbstractController
{
    /**
     * @param TagRepository $tag
     * @return Response
     */
    public function liste(TagRepository $tag): Response
    {
        $repository = $tag;
        $tags = $repository->findAll();

        return  $this->render('tag/liste.html.twig', ['tags' => $tags]);
    }

    /**
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function create(Request $request, ObjectManager $manager): Response
    {
        $tag = new Tag();
        $tagForm = $this->createForm(TagType::class, $tag);
        $tagForm->handleRequest($request);

        if ($tagForm->isSubmitted() && $tagForm->isValid()) {
            $manager->persist($tag);
            $manager->flush();

            $this->addFlash('success', 'Votre tag a bien été ajouté, félicitations !');
            return $this->redirectToRoute('app_tag_liste');
        }
        return $this->render('tag/create.html.twig', [
            'tagForm' => $tagForm->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param ObjectManager $manager
     * @param Tag $tag
     * @return Response
     */
    public function update(Request $request, ObjectManager $manager, Tag $tag): Response
    {
        $tagForm = $this->createForm(TagType::class, $tag);
        $tagForm->handleRequest($request);

        if ($tagForm->isSubmitted() && $tagForm->isValid()) {
            $manager->flush();

            $this->addFlash('warning', 'Votre tag a bien été modifié');
            return $this->redirectToRoute('app_tag_liste');
        }
        return $this->render('tag/update.html.twig', [
            'updateForm' => $tagForm->createView()
        ]);
    }

    /**
     * @param ObjectManager $manager
     * @param Tag $tag
     * @return Response
     */
    public function delete(ObjectManager $manager, Tag $tag): Response
    {
        $manager->remove($tag);
        $manager->flush();
        $this->addFlash('danger', 'Tag supprimé... -__-');
        return $this->redirectToRoute('app_tag_liste');
    }
}
