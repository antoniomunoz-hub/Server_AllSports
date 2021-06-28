<?php

namespace App\Service;

use App\Entity\Sport;
use Symfony\Component\HttpFoundation\UrlHelper;

class sportNormalizee
{
    private $urlHelper;

    public function __construct(UrlHelper $constructorDeURL)
    {
        $this->urlHelper = $constructorDeURL;
    }

    /**
     * Normalize an sport.
     *
     * @param Sport $sport
     *
     * @return array|null
     */
    
    public function sportNormalizee(Sport $sport): ?array
    {
       return [
           'id' => $sport->getId(),
           'name' => $sport->getName()
       ];
    }
}