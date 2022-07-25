<?php

namespace App\Controller;

use App\Entity\Image;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;


class ArticleImageController 
{
    public function __invoke(Request $request)
    {
        $image = $request->attributes->get('data');
        if (!($image instanceof Image)) {
            throw new RuntimeException('article attendu');
        }
        $file = $request->files->get('file');
        $image->setFile($request->files->get('file'));
        $image->setUpdatedAt(new \DateTimeImmutable());
        return $image;
    }
}
