<?php

namespace App\Controller;

use App\Model\PlayerManager;

class PlayerController extends AbstractController
{
    public array $airQuality = [
        1 => 'Bien',
        2 => 'Viable',
        3 => 'Moyen',
        4 => 'Mauvais',
        5 => 'Toxique',
    ];

    public function index(): string
    {
        $playerManager = new PlayerManager();
        $player = $playerManager->selectOneById($_SESSION['user']);
        $points = 0;
        if (isset($_GET['airQuality'])) {
            $points = $player['xp'] + $_GET['airQuality'] * 10;
            $playerManager->updateXp($player['id'], $points);

            $level = Level::calculate($points);
            $playerManager->updateLevel($player['id'], $level);
        }
        $player = $playerManager->selectOneById($_SESSION['user']);
        $progression = $player['xp'] - ($player['level'] - 1) * 100;

        return $this->twig->render('Player/index.html.twig', [
            'player' => $player,
            'progression' => $progression,
        ]);
    }
}
