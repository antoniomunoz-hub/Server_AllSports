<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\SportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use App\Service\UserNormalizee;

/**
 * @Route("//api/users", name="api_user_")
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
        // dump($request->getContent());
        dump($data);


        // // Crear un objeto usuario, estará vacío.
        $user = new User();

        $user->setFirstName($data['firstName']);
        $user->setLastName($data['lastName']);
        if(array_key_exists("priceManager", $data))
        {
            $user->setPriceManager($data['priceManager']);
        }
        if(array_key_exists("career", $data))
        {

            $user->setCareer($data['career']);
        }
        if(array_key_exists("speciality", $data))
        {
            $user->setSpeciality($data['speciality']);
        }
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);
        $user->setRoles([$data['roles']]);
        $user->setSex($data['sex']);
        $user->setWeigth($data['weigth']);
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
}
