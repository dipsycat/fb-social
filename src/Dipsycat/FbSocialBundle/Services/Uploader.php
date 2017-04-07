<?php

namespace Dipsycat\FbSocialBundle\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class Uploader {
    /*
     * 
     * [test:Symfony\Component\HttpFoundation\File\UploadedFile:private] => 
      [originalName:Symfony\Component\HttpFoundation\File\UploadedFile:private] => Blog Image 2_2.jpg
      [mimeType:Symfony\Component\HttpFoundation\File\UploadedFile:private] => image/jpeg
      [size:Symfony\Component\HttpFoundation\File\UploadedFile:private] => 134059
      [error:Symfony\Component\HttpFoundation\File\UploadedFile:private] => 0
      [pathName:SplFileInfo:private] => C:\xampp\tmp\phpBDA9.tmp
      [fileName:SplFileInfo:private] => phpBDA9.tmp
     */
    
    private $dir;
    
    public function __construct($uploadDir) {
        $this->dir = $uploadDir;
    }

    public function uploadFile(UploadedFile $file) {
        $fileName = $file->getClientOriginalName();
        $type = $file->guessExtension();
        $fileName = $this->createFileName($fileName)  . '.' . $type;
        $file->move($this->dir, $fileName);
        return $fileName;
    }
    
    public function createFileName($name) {
        return hash('md5', strtolower(str_replace(' ', '', $name)) . time());
    }

}
