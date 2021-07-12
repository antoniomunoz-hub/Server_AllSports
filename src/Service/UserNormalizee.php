<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\HttpFoundation\UrlHelper;

class UserNormalizee
{
    private $urlHelper;

    public function __construct(UrlHelper $constructorDeURL)
    {
        $this->urlHelper = $constructorDeURL;
    }

    /**
     * Normalize an user.
     *
     * @param User $user
     *
     * @return array|null
     */
    
    public function sportNormalizee(User $user): ?array
    {
       return [
           'id' => $user->getId(),
           'email' => $user->getEmail(),
           'role' =>$user->getRoles(),
           'firstName' =>$user->getFirstName(),
           'password' =>$user->getPassword(),
           'weigth' =>$user->getWeigth(),
           'country' =>$user->getCountry(),
           //la columna id_sport
           
       ];
    }
}