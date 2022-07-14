<?php

namespace App\Service;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class EncoderService{
    public function encode($objetRepository){
        $encoder = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers,$encoder);
        $jsonContent = $serializer->serialize($objetRepository, 'json', [
            'circular_reference_handler'=> function($object){
                return $object->getId();
            }
        ]);

        return $jsonContent;

    }
}