<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CardControllerRoutes extends AbstractController
{
    #[Route("/card/init", name: "card_init")]
    public function init(
        SessionInterface $session
    ): Response {
        //initiera sessionerna.
        $deck = new DeckOfCards();
        $deckAsString = new CardGraphic();
        $deckAsString->deckToString($deck);
        $session->set("deck", $deckAsString->deckGraphic);
        $session->set("cardHand", []);

        return $this->redirectToRoute('card');
    }

    #[Route("/session", name: "session_show")]
    public function session(
        SessionInterface $session
    ): Response {
        $data = [
            'session' => $session->all()
        ];

        return $this->render('card/session.html.twig', $data);
    }

    #[Route("/session/delete", name: "session_delete")]
    public function sessionDelete(
        SessionInterface $session
    ): Response {
        //deletar sessionerna.
        $session->clear();
        return $this->redirect('card/session.html.twig');
    }

    #[Route("/card", name: "card", methods: ['GET'])]
    public function card(
        // Request $request,
        SessionInterface $session
    ): Response {
        return $this->render('card/card.html.twig');
    }

    #[Route("/card/deck", name: "card_deck", methods: ['GET'])]
    public function cardDeck(): Response
    {
        $deck = new DeckOfCards();
        $deckToString = new CardGraphic();

        $data = [
            "deck" => $deckToString->deckToString($deck)->deckGraphic
        ];

        return $this->render('card/card-deck.html.twig', $data);
    }

    #[Route("/card/deck/shuffle", name: "card_shuffle", methods: ['GET'])]
    public function cardShuffle(
        SessionInterface $session
    ): Response {

        $deck = new DeckOfCards();
        $deckAsString = new CardGraphic();
        $deckAsString->deckToString($deck)->shuffleCardDeck();

        $session->set("deck", $deckAsString->deckGraphic);
        $session->set("cardHand", []);

        $data = [
            "deck" =>  $deckAsString->deckGraphic
        ];

        return $this->render('card/card-shuffle.html.twig', $data);
    }

    #[Route("/card/deck/draw/{number}", name: "card_draw", methods: ['GET', 'POST'])]
    public function cardDraw(
        Request $request,
        SessionInterface $session,
        $number = 1
    ): Response {
        if ($session->get("drawNumber")) {
            $number = $session->get("drawNumber");
            $session->remove("drawNumber");
        }
        $deck = $session->get("deck");
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
        return $this->render('card/card-draw.html.twig', $data);
    }

    #[Route("/card/deck/draw/number/form", name: "card_draw_numbers", methods: ['GET'])]
    public function cardDrawNumbers(
        SessionInterface $session
    ): Response {
        $deck = $session->get("deck");

        $data = [
            "deck" => count($deck)
        ];
        return $this->render('card/draw-numbers.html.twig', $data);
    }

    #[Route("/card/deck/draw/number/form", name: "card_draw_numbers_post", methods: ['POST'])]
    public function cardDrawNumbersCallback(
        Request $request,
        SessionInterface $session
    ): Response {
        $session->set("drawNumber", $request->request->get('draw_cards'));

        return $this->redirectToRoute('card_draw');
    }

}
