<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SubscriptionController extends AbstractController
{
    #[Route('/subscription', name: 'app_subscription')]
    public function index(): Response
    {
        // Strip
        $stripe = new \Stripe\StripeClient('sk_test_51PYFSpBTpbjFXff3MDqBk77DN1Pt4DuncdGrdfOUqnKEQOE4vrAbnBVZaZa9NxfYwjDdaRsk9Wu505Ocy1tq73lD001ZgRtLYt');
        $stripe->subscriptions->create([
            'customer' => 'cus_J4Zz1Zz1Zz1Zz1',
            'items' => [
                [
                    'price' => 'price_1J4Zz1Zz1Zz1Zz1',
                ],
            ],
        ]);



        return $this->render('subscription/index.html.twig', [
            'controller_name' => 'SubscriptionController',
            'stripe' => $stripe,
        ]);
    }
}
