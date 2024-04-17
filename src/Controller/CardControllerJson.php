<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardHand;
use App\Card\DeckOfCards;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;

class CardControllerJson extends AbstractController
{
    #[Route("/api/deck", name: "api_deck")]
    public function apiDeck(): Response
    {
        $deck = new DeckOfCards();

        $data = [
            "deck" => $deck->deckOfCardsJson()
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/shuffle", name: "api_deck_shuffle")]
    public function apiDeckShuffle(
        SessionInterface $session
    ): Response {
        $deck = new DeckOfCards();
        $deck->shuffle();
        $session->set("deck", $deck->deckOfCardsJson());
        $session->set("cardHand", []);

        $data = [
            "deck" => $deck->deckOfCardsJson()
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/draw/{number}", name: "api_draw", methods: ['GET', 'POST'])]
    public function apiCardDraw(
        // Request $request,
        SessionInterface $session,
        $number = 1
    ): Response {
        if ($session->get("drawNumber")) {
            $number = $session->get("drawNumber");
            $session->remove("drawNumber");
        }

        if($session->get("deck")) {
            $deck = $session->get("deck");
        } else {
            $deck = new DeckOfCards();
            $session->set("deck", $deck->deckOfCardsJson());
        }

        $currentHand = $session->get("cardHand", []);

        $card = new CardHand();
        $card->addToHand($currentHand);
        $card->addCardHand($number, $deck);

        $session->set("deck", $card->getCardDeck());
        $session->set("cardHand", $card->getCardHand());

        $data = [
            "deck" => $card->getCardDeck(),
            "cardHand" => $card->getCardHand()
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/draw/number/form", name: "api_draw_numbers", methods: ['GET'])]
    public function apiCardDrawNumbers(
        SessionInterface $session
    ): Response {
        $deck = $session->get("deck");

        $data = [
            "deck" => count($deck)
        ];
        return $this->render('card/api-draw-numbers.html.twig', $data);
    }

    #[Route("/api/deck/draw/number/form", name: "api_draw_numbers_post", methods: ['GET', 'POST'])]
    public function apiCardDrawNumbersCallback(
        Request $request,
        SessionInterface $session
    ): Response {
        $session->set("drawNumber", $request->request->get('draw_cards'));

        return $this->redirectToRoute('api_draw');
    }

}
