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
        EntityManagerInterface $em
    ): Response
    {
        // // Coger el cuerpo de la solicutd HTTP
        // // y codificarlo como JSON.
        $data = \json_decode($request->getContent(), true);
        // dump($data);

        // // si lo transformas a objeto la forma de acceder a las propiedades es con el operador de objeto $data->name
        // // si lo transformas a array asociativo la forma de acceder a las propiedades es con la sintaxis de array $data['name']

        // // Crear un objeto usuario, estará vacío.
        $user = new User();
        // dump($user);

        // // setear los valores "simples" a cada propiedad.
        $user->setFirstName($data['firstName']);
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);
        // $user->setSports($data['sports']);
        $user->setSex($data['sex']);
        $user->setWeigth($data['weigth']);
        $user->setCountry($data['country']);


        // setear un date
        // 2021-12-31 - Y-m-d
        // 31/12/2021 - d/m/Y
        // 31-12-2021 - d-m-Y
        // https://www.php.net/manual/es/class.datetime.php
        $birth = \DateTime::createFromFormat('Y-m-d', $data['birth']);
        // dump($birth);
        $user->setBirthdate($birth);

        // setear propiedades que sean relaciones.
        $sport = $sportRepository->find($data['sport_id']);
        // dump($sport);
        $user->setSport($sport);

        // // Recuerda que los dumps y die son sólo para depurar la solicitud de Thunder Cliente, luego deberás borrarlos.
        // dump($user);
        // die();

        // // Validar el objeto $user.

        $em->persist($user);
        $em->flush();

        return $this->json(
            $user, // Normalizado MANUALMENTE (UserNormalizer), para evitar problemas de referencias circulares.
            Response::HTTP_CREATED
        );
        
    }
}
