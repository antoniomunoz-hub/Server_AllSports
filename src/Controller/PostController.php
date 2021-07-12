<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints\Date;
use App\Repository\UserRepository;
use App\Service\PostNormalizee;

/**
 * @Route("/post")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="post_index", methods={"GET"})
     */
    public function index(PostRepository $postRepository): Response
    {
        
        return $this->json(
            $postRepository->findAll(), // Normalizado MANUALMENTE (UserNormalizer), para evitar problemas de referencias circulares.
            Response::HTTP_OK
        );

    }

    /**
     * @Route("/new", name="post_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('post_index');
        }

        return $this->render('post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_show", methods={"GET"})
     */
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="post_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Post $post): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('post_index');
        }

        return $this->render('post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_delete", methods={"POST"})
     */
    public function delete(Request $request, Post $post): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('post_index');

        
    }

     /**
     * @Route("", name="add", methods={"POST"})
     */
    public function add(
        Request $request,
        EntityManagerInterface $em,
        UserRepository $userRepository,
        PostNormalizee $postNormalizee
    ): Response
    {
       
        $data = \json_decode($request->getContent(), true);
       


       
        $post = new Post();
        $user = $userRepository->find($data['user_id']);
        $post->setUser($user);
        
        $post->setTitle($data['title']);
        $post->setPhoto($data['photo']);
        $post->setTextPublication($data['textPublication']);
        


        $em->persist($post);
        $em->flush();

        return $this->json(
            $postNormalizee->postNormalizee($post), // Normalizado MANUALMENTE (UserNormalizer), para evitar problemas de referencias circulares.
            Response::HTTP_CREATED
        );

        
        
    }
}
