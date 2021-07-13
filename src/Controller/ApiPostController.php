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
use App\Service\PostNormalizee;
use App\Repository\PostRepository;
use App\Entity\User;


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
        UserRepository $userRepository,
        PostNormalizee $postNormalizee
    ):  Response
    
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
            $postNormalizee->postNormalizee($post),// Normalizado MANUALMENTE (UserNormalizer), para evitar problemas de referencias circulares.
            Response::HTTP_CREATED
        );

    }

    /**
     * @Route("/", name="post_index", methods={"GET"})
     */
    
    //  public function index(PostRepository $postRepository): Response
    // {
        // $user = $this->getUser();

        // $postsResult = $postRepository->findBy([
        //    'user' = $this->getUser()
        //]);

        // $posts = [];
        // foreach($postsResult as $post) {
        //     $posts[] = $postNormalizee->postNormalizee($post);
        // }

        // return $this->json(
        //     $posts, // Normalizado MANUALMENTE (UserNormalizer), para evitar problemas de referencias circulares.
        //     Response::HTTP_OK
        // );

    // }

    /**
     * @Route(
     *      "/{id}",
     *      name="put",
     *      methods={"PUT"},
     *      requirements={
     *          "id": "\d+"
     *      }
     * )
     */
    
//     public function update(
//         Request $request,
//         EntityManagerInterface $em,
//         UserRepository $userRepository,
//         PostNormalizee $postNormalizee,
//         Post $post
//     ):  Response
//     {
       
//         $data = $request->$request;
    
    
//         $post->setTitle($data['title']);
//         $post->setPhoto($data['photo']);
//         $post->setTextPublication($data['textPublication']);
        
//         $em->flush();

//         return $this->json(
//             $postNormalizee->postNormalizee($post),// Normalizado MANUALMENTE (UserNormalizer), para evitar problemas de referencias circulares.
//             Response::HTTP_NO_CONTENT
//         );
//     }

//     /**
//      * @Route(
//      *      "/{id}",
//      *      name="delete",
//      *      methods={"DELETE"},
//      *      requirements={
//      *          "id": "\d+"
//      *      }
//      * )
//      */
    
//     public function delete(
//         Post $post,
//         EntityManagerInterface $entityManager
//         ): Response
//    {
//        //remove() prepara el sistema pero NO ejecuta la sentencia

//        $entityManager->remove($post);
//        $entityManager->flush();
//        return $this->json(
//            null, Response::HTTP_NO_CONTENT
//        );
//    }
}
