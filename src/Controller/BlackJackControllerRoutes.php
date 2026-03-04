<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\BlackJackCardHand;
use App\Card\DeckOfCards;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class BlackJackControllerRoutes extends AbstractController
{
    #[Route("/proj", name: "blackjack")]
    public function blackjack(): Response
    {
        return $this->render('blackjack/blackjack.html.twig');
    }

    #[Route("/proj/about", name: "proj_about")]
    public function about(): Response
    {
        return $this->render('blackjack/about.html.twig');
    }

    #[Route("/proj/start", name: "blackjack_start")]
    public function blackjackStart(): Response
    {
        return $this->render('blackjack/blackjack-start.html.twig');
    }

    #[Route("/proj/game", name: "blackjack_game", methods: ['POST'])]
    public function startBlackjack(
        SessionInterface $session,
        Request $request
    ): Response {

        $deck = new DeckOfCards();
        $deckAsString = new CardGraphic();
        $deckAsString->deckToString($deck)->shuffleCardDeck();

        $blackjack_game = new blackJackCardHand();
        $blackjack_game->addCardDeck($deckAsString->deckGraphic);
        $blackjack_game->startGame($request->request->get('hands'), $request->request->get('markers'));

        
        $session->set("blackjackDeck", $blackjack_game->getCardDeck());
        $session->set("playerHands", $blackjack_game->getHands());
        $session->set("bankHand", $blackjack_game->getBankHand());
        $session->set("totalChips", $blackjack_game->getTotalChips());
        $session->set("bankPoints", $blackjack_game->countBankPoints());
        $session->set("message", null);
        $session->set("endGame", false);

        return $this->redirectToRoute('blackjack_play');
    }

    #[Route("/proj/play", name: "blackjack_play", methods: ['GET'])]
    public function play(SessionInterface $session): Response
    {

        $data = [
            "playerHands" => $session->get("playerHands"),
            "bankHand" => $session->get("bankHand"),
            "totalChips" => $session->get("totalChips"),
            "message" => $session->get("message"),
            "bankPoints" => $session->get("bankPoints"),
            "endGame" => $session->get("endGame"),
        ];

        return $this->render('blackjack/blackjack-game.html.twig', $data);
    }


    #[Route("/proj/draw-card/{hand}", name: "draw_card_bj", methods: ['GET'])]
    public function drawCard(
        SessionInterface $session,
        int $hand
    ): Response {
        // dd($session->all());

        $blackjack_game = new blackJackCardHand();

        $blackjack_game->addCardDeck($session->get("blackjackDeck"));
        $blackjack_game->setHands($session->get("playerHands"));
        $blackjack_game->setBankHand($session->get("bankHand"));
        $blackjack_game->setTotalChips($session->get("totalChips"));
        
        $blackjack_game->playTurn($hand);
        
        $session->set("blackjackDeck", $blackjack_game->getCardDeck());
        $session->set("playerHands", $blackjack_game->getHands());
        $session->set("totalChips", $blackjack_game->getTotalChips());
        $session->set("bankHand", $blackjack_game->getBankHand());
        $session->set("message", null);

        return $this->redirectToRoute('blackjack_play');
    }

    #[Route("/proj/split/{hand}", name: "split", methods: ['GET'])]
    public function split(
        SessionInterface $session,
        int $hand
    ): Response {
        // dd($session->all());

        $blackjack_game = new blackJackCardHand();

        $blackjack_game->addCardDeck($session->get("blackjackDeck"));
        $blackjack_game->setHands($session->get("playerHands"));
        $blackjack_game->setBankHand($session->get("bankHand"));
        $blackjack_game->setTotalChips($session->get("totalChips"));
        
        $blackjack_game->split($hand);
        
        $session->set("blackjackDeck", $blackjack_game->getCardDeck());
        $session->set("playerHands", $blackjack_game->getHands());
        $session->set("totalChips", $blackjack_game->getTotalChips());
        $session->set("bankHand", $blackjack_game->getBankHand());
        $session->set("message", $blackjack_game->getMessage());

        return $this->redirectToRoute('blackjack_play');
    }

    #[Route("/proj/double/{hand}", name: "double", methods: ['GET'])]
    public function double(
        SessionInterface $session,
        int $hand
    ): Response {
        // dd($session->all());

        $blackjack_game = new blackJackCardHand();

        $blackjack_game->addCardDeck($session->get("blackjackDeck"));
        $blackjack_game->setHands($session->get("playerHands"));
        $blackjack_game->setBankHand($session->get("bankHand"));
        $blackjack_game->setTotalChips($session->get("totalChips"));
        
        $blackjack_game->double($hand);
        
        $session->set("blackjackDeck", $blackjack_game->getCardDeck());
        $session->set("playerHands", $blackjack_game->getHands());
        $session->set("totalChips", $blackjack_game->getTotalChips());
        $session->set("bankHand", $blackjack_game->getBankHand());
        $session->set("message", $blackjack_game->getMessage());

        return $this->redirectToRoute('blackjack_play');
    }

    #[Route("/proj/end-blackjack", name: "end_blackjack", methods: ['GET'])]
    public function endBlackJack(
        SessionInterface $session,
    ): Response {
        // dd($session->all());

        $blackjack_game = new blackJackCardHand();

        $blackjack_game->addCardDeck($session->get("blackjackDeck"));
        $blackjack_game->setHands($session->get("playerHands"));
        $blackjack_game->setBankHand($session->get("bankHand"));
        $blackjack_game->setTotalChips($session->get("totalChips"));
        
        $blackjack_game->bankTurn();
        
        $session->set("blackjackDeck", $blackjack_game->getCardDeck());
        $session->set("playerHands", $blackjack_game->getHands());
        $session->set("totalChips", $blackjack_game->getTotalChips());
        $session->set("bankHand", $blackjack_game->getBankHand());
        $session->set("message", $blackjack_game->getMessage());
        $session->set("bankPoints", $blackjack_game->countBankPoints());
        $session->set("endGame", true);

        return $this->redirectToRoute('blackjack_play');
    }
}
