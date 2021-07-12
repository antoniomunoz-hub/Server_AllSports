<?php

namespace App\Service;

use App\Entity\Post;
use Symfony\Component\HttpFoundation\UrlHelper;

class PostNormalizee
{
    private $urlHelper;

    public function __construct(UrlHelper $constructorDeURL)
    {
        $this->urlHelper = $constructorDeURL;
    }

    /**
     * Normalize an Post.
     *
     * @param Post $post
     *
     * @return array|null
     */
    
    public function postNormalizee(Post $post): ?array
    {
       return [
           'tittle' => $post->getTitle(),
           'photo' => $post->getPhoto(),
           'textPublication' => $post->getTextPublication(),
        //    'user' => $post->getUser() // Mirar porque rezirculacion
       ];
    }
}