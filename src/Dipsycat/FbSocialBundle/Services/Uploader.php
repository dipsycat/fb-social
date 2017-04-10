<?php

namespace Dipsycat\FbSocialBundle\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class Uploader {
    
    private $absoluteUploadDir;
    private $relativeUploadDir;
    
    public function __construct($absoluteUploadDir, $relativeUploadDir) {
        $this->absoluteUploadDir = $absoluteUploadDir;
        $this->relativeUploadDir = $relativeUploadDir;
    }

    public function uploadFile(UploadedFile $file) {
        $fileName = $file->getClientOriginalName();
        $type = $file->guessExtension();
        $fileName = $this->createFileName($fileName)  . '.' . $type;
        $file->move($this->absoluteUploadDir, $fileName);
        return $this->relativeUploadDir . $fileName;
    }
    
    public function createFileName($name) {
        return hash('md5', strtolower(str_replace(' ', '', $name)) . time());
    }

}
