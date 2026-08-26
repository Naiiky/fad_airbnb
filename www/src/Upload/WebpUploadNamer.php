<?php

namespace App\Upload;

use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\NamerInterface;

class WebpUploadNamer implements NamerInterface
{
    public function name(object $object, PropertyMapping $mapping): string
    {
        return bin2hex(random_bytes(16)).'.webp';
    }
}
