<?php

namespace App\Service;

use App\Entity\Post;
use Symfony\Component\HttpFoundation\UrlHelper;

class PostNormalizee
{
    private $urlHelper;
    private $userNormalizee;

    public function __construct(
        UrlHelper $constructorDeURL,
        UserNormalizee $userNormalizee
        )
    {
        $this->urlHelper = $constructorDeURL;
        $this->userNormalizee = $userNormalizee;
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
           'user' => $this->userNormalizee->userNormalizee($post->getUser()) // Mirar porque rezirculacion
       ];
    }
}