<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\HttpFoundation\UrlHelper;

class UserNormalizee
{
    private $urlHelper;
    private $sportNormalizee;

    public function __construct(
        UrlHelper $constructorDeURL,
        SportNormalizee $sportNormalizee
        )
    {
        $this->urlHelper = $constructorDeURL;
        $this->sportNormalizee = $sportNormalizee;
    }

    /**
     * Normalize an user.
     *
     * @param User $user
     *
     * @return array|null
     */
    
    public function userNormalizee(User $user): ?array
    {
       return [
           'id' => $user->getId(),
           'email' => $user->getEmail(),
           'role' =>$user->getRoles(),
           'firstName' =>$user->getFirstName(),
           'password' =>$user->getPassword(),
           'weigth' =>$user->getWeigth(),
           'country' =>$user->getCountry(),
           'sport' => $this->sportNormalizee->sportNormalizee($user->getSport())           
       ];
    }
}