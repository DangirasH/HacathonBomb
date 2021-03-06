<?php

namespace App\Controller;

use App\Model\UserManager;

class LoginController extends AbstractController
{
    public function login(): string
    {
        $errors = $connection = [];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $connection = array_map('trim', $_POST);
            $errors = $this->validate($connection);

            if (empty($errors)) {
                $userManager = new UserManager();
                $user = $userManager->selectOneByName($connection['name']);
                if ($user) {
                    if ($connection['password'] === $user['password']) {
                        $_SESSION['user'] = $user['id'];
                        header('Location: player');
                    } else {
                        $errors[] = 'Mot de passe inconnu';
                    }
                } else {
                    $errors[] = 'Le nom est inconnu';
                }
            }
        }
        return $this->twig->render('Login/login.html.twig', [
            'errors' => $errors,
        ]);
    }

    public function logout()
    {
        if (!empty($_SESSION['user'])) {
            unset($_SESSION['user']);
        }

        header('Location: /');
    }

    private function validate(array $connection): array
    {
        $errors = [];
        if (empty($connection['name'])) {
            $errors[] = 'Le champ name ne doit pas être vide';
        }
        if (empty($connection['password'])) {
            $errors[] = 'Le mot de passe ne doit pas être vide';
        }
        return $errors;
    }
}
