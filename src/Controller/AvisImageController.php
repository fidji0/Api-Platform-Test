<?php

namespace App\Controller;




class AvisImageController 
{
    public function __invoke($data)
    {
        return $data;
    }
    /*public function __invoke(Request $request)
    {
        $image = $request->attributes->get('data');
        if (!($image instanceof Image)) {
            throw new RuntimeException('article attendu');
        }
        $file = $request->files->get('file');
        $image->setFile($request->files->get('file'));
        $image->setUpdatedAt(new \DateTimeImmutable());
        return $image;
    }*/
}
