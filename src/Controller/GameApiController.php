<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Get 20 unique random winners, between 1 and 1000, not in an array of losers
 */
final class GameApiController extends AbstractController
{
    public function __invoke():Response
    {
        $losers = [
            2, 56, 345, 674, 234, 764, 543, 123, 324, 9, 78, 12, 94, 12, 50, 5, 13
        ];

        $winners = [];

        $i = 0;
        for ($i = 0; $i < 21; $i++) {
            $winner = mt_rand(1, 1000);
            if (!in_array($winner, $losers)) {
                $winners[] = $winner;
            } else {
                $i--;
            }
        }

        return new JsonResponse(['winners' => $winners]);
    }
}