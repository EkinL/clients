<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SubscriptionController extends AbstractController
{
    #[Route('/subscription', name: 'app_subscription')]
    public function checkout(): Response
    {
        // Configurez votre clé API secrète Stripe
        \Stripe\Stripe::setApiKey('sk_test_51PYFSpBTpbjFXff3MDqBk77DN1Pt4DuncdGrdfOUqnKEQOE4vrAbnBVZaZa9NxfYwjDdaRsk9Wu505Ocy1tq73lD001ZgRtLYt'); // Remplacez par votre clé API secrète

        // Créez une session de paiement
        $checkoutSession = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Personal',
                        'images' => ['https://avatars.githubusercontent.com/u/31770799?v=4'],
                    ],
                    'unit_amount' => 2900, // Montant en cents (29,00 $)
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => 'http://127.0.0.1:8000/success', // URL de succès
            'cancel_url' => 'http://127.0.0.1:8000/cancel',   // URL d'annulation
        ]);

        // Passez l'ID de session à la vue
        return $this->render('subscription/index.html.twig', [
            'checkout_session_id' => $checkoutSession->id,
            'publishable_key' => 'pk_test_51PYFSpBTpbjFXff3lHwADSdCoV8YivaPJPEiMuqS4lFLmtU4e69qBjFBrEh2t6IQzBJG6AO5048rUGZenmqNrjPD00bw8byh9s', // Remplacez par votre clé API publique
        ]);
    }
    #[Route('/success', name: 'app_success')]
    public function success(): Response
    {
        return $this->render('subscription/success.html.twig', [
            'checkout_session_id' => 'session_123',
        ]);
    }

    #[Route('/cancel', name: 'app_cancel')]
    public function cancel(): Response
    {
        return $this->render('subscription/cancel.html.twig', [
            'checkout_session_id' => 'session_123',
        ]);
    }
}
