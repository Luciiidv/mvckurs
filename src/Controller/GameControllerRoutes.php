<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use App\Card\CardGame;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class GameControllerRoutes extends AbstractController
{
    #[Route("/game", name: "game")]
    public function game(): Response
    {
        return $this->render('game/game.html.twig');
    }

    #[Route("/game/doc", name: "game_doc")]
    public function gameDoc(): Response
    {
        return $this->render('game/game-doc.html.twig');
    }

    #[Route("/game/init", name: "init_game")]
    public function initGame(
        SessionInterface $session
    ): Response {

        $deck = new DeckOfCards();
        $deckAsString = new CardGraphic();
        $deckAsString->deckToString($deck)->shuffleCardDeck();

        $session->set("gameDeck", $deckAsString->deckGraphic);
        $session->set("playerHand", []);
        $session->set("bankHand", []);

        return $this->redirectToRoute('new_game');
    }

    #[Route("/game/new-game", name: "new_game", methods: ['GET'])]
    public function newGame(
        SessionInterface $session
    ): Response {

        $deck = $session->get("gameDeck");
        $currentPlayerHand = $session->get("playerHand", []);

        $playerHand = new CardHand();
        $playerHand->addToHand($currentPlayerHand);
        $playerHand->addCardHand(1, $deck);

        $session->set("playerHand", $playerHand->getCardHand());
        $session->set("gameDeck", $playerHand->getCardDeck());
        $session->set("playerPoints", $playerHand->countPlayerPoints());

        if ($playerHand->countPlayerPoints() > 21) {
            $lostGame = "Du fick över 21p banken vann.";
            $data = [
                "cardHand" => $playerHand->getCardHand(),
                "playerPoints" => $playerHand->countPlayerPoints(),
                "lostGame" => $lostGame
            ];

            return $this->render('game/new-game.html.twig', $data);
        }

        $data = [
            "cardHand" => $playerHand->getCardHand(),
            "playerPoints" => $playerHand->countPlayerPoints(),
            "lostGame" => null
        ];

        return $this->render('game/new-game.html.twig', $data);
        // return $this->render('game/new_game.html.twig');
    }

    #[Route("/game/draw-card", name: "draw_card", methods: ['GET'])]
    public function drawCard(): Response
    {

        return $this->redirectToRoute('new_game');
        // return $this->render('game/new_game.html.twig');
    }

    #[Route("/game/bank", name: "bank", methods: ['GET'])]
    public function bank(
        SessionInterface $session
    ): Response {
        $deck = $session->get("gameDeck");
        $currentBankHand = $session->get("bankHand", []);

        //Skapa en han till banken
        $bankHand = new CardHand();
        $bankHand->addToBankHand($currentBankHand);
        $bankHand->addBankHand(1, $deck);

        //Lägg till poäng
        while ($bankHand->countBankPoints() < 17) {
            $bankHand->addBankHand(1, $deck);
        }

        //Setta bankhanden och poängen till sessionen
        $session->set("bankHand", $bankHand->getBankHand());
        $session->set("bankPoints", $bankHand->countBankPoints());

        return $this->redirectToRoute('end_game');
    }

    #[Route("/game/end-game", name: "end_game", methods: ['GET'])]
    public function endGame(
        SessionInterface $session
    ): Response {

        $playerPoints = $session->get("playerPoints");
        $bankPoints = $session->get("bankPoints");
        $currentBankHand = $session->get("bankHand");
        $currentPlayerHand = $session->get("playerHand");

        $hands = new CardHand();
        $hands->addToHand($currentPlayerHand);
        $hands->addToBankHand($currentBankHand);

        // var_dump($deck);

        //Vinnaren
        $winner = $hands->winner();

        $data = [
            "bankHand" => $hands->getBankHand(),
            "playerPoints" => $playerPoints,
            "bankPoints" => $bankPoints,
            "winner" => $winner
        ];

        return $this->render('game/end-game.html.twig', $data);
        // return $this->render('game/new_game.html.twig');
    }


    #[Route("/api/game", name: "api_game", methods: ['GET'])]
    public function apiGame(
        SessionInterface $session
    ): Response {

        $playerPoints = $session->get("playerPoints");
        $bankPoints = $session->get("bankPoints");
        $currentBankHand = $session->get("bankHand");
        $currentPlayerHand = $session->get("playerHand");

        $hands = new CardHand();
        $hands->addToHand($currentPlayerHand);
        $hands->addToBankHand($currentBankHand);

        // var_dump($deck);

        //Vinnaren
        $winner = $hands->winner();

        $data = [
            "playerHand" => $hands->apiCardHand(),
            "bankHand" => $hands->apiBankHand(),
            "playerPoints" => $playerPoints,
            "bankPoints" => $bankPoints,
            "winner" => $winner
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
