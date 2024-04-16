<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LuckyControllerJson
{
    #[Route("/api/qoute", name: 'api_qoute')]
    public function jsonQoute(): Response
    {
        $number = random_int(0, 3);

        if ($number == 1) {
            $qoute = 'Ju aeldre du blir, desto mer inser du att vaenlighet är synonymt med lycka.';
        } elseif ($number == 2) {
            $qoute = 'Erfarenhet aer inte vad som haender dig; det aer vad du gor med det som haender dig';
        } else {
            $qoute = 'Humor spelar naera den stora, heta eld som aer sanningen.';
        }

        $timestamp = date('Y-m-d H:i:s', time());

        $data = [
            'Dagens citat' => $qoute,
            'Tidsstaempel' => $timestamp
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

}
