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

                                                    //METODOS CGET Y PUT ERRORES ; POST, DELETE Y GET/ID ESTAN  OK 
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
            $postNormalizee->postNormalizee($post),
            Response::HTTP_CREATED
        );

    }

    /**
     * @Route(
     *      "",
     *      name="cget",
     *      methods={"GET"}
     * )
     */
    
     public function index(
         Post $post,
         Request $request,
         PostRepository $postRepository, 
         PostNormalizee $postNormalizee): Response
    {
        $result = $postRepository->findAll();
        
        $data = [];

        foreach($result as $posts) {

            $data[] = $postNormalizee->postNormalizee($posts);

        }
        return $this->json($data);
        // return $this->json(
        //     $posts,
        //     Response::HTTP_OK
        // );

    }

    

     /**
     * @Route(
     *      "/{id}",
     *      name="get",
     *      methods={"GET"},
     *      requirements={
     *          "id": "\d+"
     *      }
     * )
     */

    public function show(
        int $id, 
        PostRepository $postRepository,
        PostNormalizee $postNormalizee,
        Request $request
        ): Response
    {
        $data = json_decode($request->getContent());

        $data = $postRepository->find($id);

        dump($id);
        dump($data);

        return $this->json($postNormalizee->postNormalizee($data));
    }



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
    
    public function update(
        Request $request,
        EntityManagerInterface $em,
        PostNormalizee $postNormalizee,
        Post $post,
        PostRepository $postRepository
    ):  Response
    {
       
        $data = $request->$request;
        $post = $postRepository->find($data->post_id);
    
    
        $post->setTitle($data['title']);
        $post->setPhoto($data['photo']);
        $post->setTextPublication($data['textPublication']);
        
        $em->flush();

        return $this->json(
            null,
            // $postNormalizee->postNormalizee($post),
            Response::HTTP_NO_CONTENT
        );
    }

    /**
     * @Route(
     *      "/{id}",
     *      name="delete",
     *      methods={"DELETE"},
     *      requirements={
     *          "id": "\d+"
     *      }
     * )
     */
    
    public function delete(
        Post $post,
        EntityManagerInterface $entityManager
        ): Response
   {

       $entityManager->remove($post);
       $entityManager->flush();
       return $this->json(
           null, Response::HTTP_NO_CONTENT
       );
   }
}
