<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{

    /**
     * @Route("admin/users", name="user_list")
     */
    public function userList(UserRepository $userRepository)
    {
        $users = $userRepository->findAll();

        return $this->render("admin/users.html.twig", ['users' => $users]);
    }
}
