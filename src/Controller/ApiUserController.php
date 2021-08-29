<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\SportRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use App\Service\UserNormalizee;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


//CRUD COMPLETO CAMBIAR HASEO DE PASSWORD

/**
 * @Route("/api/users", name="api_user_")
 */
class ApiUserController extends AbstractController
{
    /**
     * @Route("", name="add", methods={"POST"})
     */
    public function add(
        Request $request,
        SportRepository $sportRepository,
        EntityManagerInterface $em,
        UserNormalizee $userNormalizee
    ):  Response
    {
        $data = json_decode($request->getContent(), true);
        dump($data);


        // // Crear un objeto usuario, estará vacío.
        $user = new User();

        $user->setFirstName($data['firstName']);
        $user->setLastName($data['lastName']);
        if(array_key_exists('priceManager', $data))
        {
            $user->setPriceManager($data['priceManager']);
        }
        if(array_key_exists('career', $data))
        {

            $user->setCareer($data['career']);
        }
        if(array_key_exists('speciality', $data))
        {
            $user->setSpeciality($data['speciality']);
        }
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);
        $user->setRoles([$data['roles']]);
        $user->setSex($data['sex']);
        $user->setWeigth($data['weight']);
        $user->setCountry($data['country']);


        $birth = \DateTime::createFromFormat('Y-m-d', $data['birth']);
        $user->setBirthdate($birth);

        $sport = $sportRepository->find($data['sport_id']);
        $user->setSport($sport);


        // // Validar el objeto $user.

        $em->persist($user);
        $em->flush();

        return $this->json(
            $userNormalizee->userNormalizee($user), 
            Response::HTTP_CREATED
        );
        
    }

    /**
    * @Route(
    *      "/uploadImage/{id}",
    *      name="uploadImage",
    *      methods={"POST"},
    *      requirements={
    *          "id": "\d+"
    *      }     
    * )
    */
    public function uploadImage(
        User $user, 
        Request $request, 
        EntityManagerInterface $entityManager):Response {

        if($request->files->has('File')) {
            $avatarFile = $request->files->get('File');

            $newFilename = uniqid().'.'.$avatarFile->guessExtension();

            // Intentamos mover el fichero a la carpeta public
            try {
                $avatarFile->move(
                    $request->server->get('DOCUMENT_ROOT') . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'avatar', // El primer parámetro es la ruta
                    $newFilename // El 2º param es el nombre del fichero
                );
            } catch (FileException $error) {
                throw new \Exception($error->getMessage());
            }

            $user->setImgpath($newFilename);
        }

        $entityManager->flush();
        
        return $this->json(
            null,
            Response::HTTP_NO_CONTENT
        );
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
        UserRepository $userRepository,
        UserNormalizee $userNormalizee): Response
    {
        $data = $userRepository->find($id);
        
        dump($id);
        dump($data);

        return $this->json(
            $userNormalizee->userNormalizee($data));
    }   
    
     /**
     * @Route(
     *      "/profile",
     *      name="get",
     *      methods={"GET"}
     * )
     */
    
    public function showprofile(
        UserRepository $userRepository,
        UserNormalizee $userNormalizee): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        
        // die($user->getId());
        // dump($data);

        return $this->json(
            $userNormalizee->userNormalizee($user));
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
    
    public function remove(
        User $user,
        EntityManagerInterface $em
        ): Response
   {
       $em->remove($user);
       $em->flush();
       return $this->json(
           null, Response::HTTP_NO_CONTENT
       );
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
        int $id,
        EntityManagerInterface $em,
        Request $request,
        SportRepository $sportRepository,
        UserRepository $userRepository
       ): Response
    {
        $data = json_decode($request->getContent(), true);
        $user = $userRepository->find($id);

        $user->setFirstName($data['firstName']);
        $user->setLastName($data['lastName']);
        if(array_key_exists('priceManager', $data))
        {
            $user->setPriceManager($data['priceManager']);
        }
        if(array_key_exists('career', $data))
        {

            $user->setCareer($data['career']);
        }
        if(array_key_exists('speciality', $data))
        {
            $user->setSpeciality($data['speciality']);
        }
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);
        $user->setRoles([$data['roles']]);
        $user->setSex($data['sex']);
        $user->setWeigth($data['weight']);
        $user->setCountry($data['country']);

        $em->flush();

        return $this->json(
            null, Response::HTTP_NO_CONTENT
        );
    }
}
