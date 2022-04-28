<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class GameController extends AbstractController
{
    public function __invoke():Response
    {
        return $this->render('game.html.twig');
    }
}
