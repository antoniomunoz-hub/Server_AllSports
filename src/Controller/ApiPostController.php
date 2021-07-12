<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Date;
use App\Repository\UserRepository;

/**
 * @Route("/api/post", name="api_post_")
 */
class ApiPostController extends AbstractController
{
  
    /**
     * @Route("", name="add", methods={"POST"})
     */
    public function add(
        Request $request,
        EntityManagerInterface $em,
        UserRepository $userRepository
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
            $post, // Normalizado MANUALMENTE (UserNormalizer), para evitar problemas de referencias circulares.
            Response::HTTP_CREATED
        );

        
        
    }
}
