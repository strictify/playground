<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Admin\Admin;
use App\Annotation\SidebarGroup;
use App\Annotation\SidebarMenu;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @SidebarGroup(name="Users module", icon="fas fa-tachometer-alt")
 */
class UsersController extends AbstractController
{
    /**
     * @Route("/users", name="admin_users", methods={"GET"})
     *
     * @SidebarMenu(label="Users", icon="fas fa-tachometer-alt")
     */
    public function index(Request $request, UserRepository $repository): Response
    {
        $search = $request->query->getAlnum('search');
        $pager = $repository->getPaginator($search);

        return $this->render('admin/users/list.html.twig', [
            'pager' => $pager,
            'search' => $search,
        ]);
    }

    /**
     * @Route("/users/edit", name="admin_users_edit", methods={"GET"})
     *
     * @SidebarMenu(label="Handle users", icon="fas fa-tachometer-alt")
     */
    public function edit(): Response
    {
        return $this->render('admin_base.html.twig');
    }
}
