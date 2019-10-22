<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Admin\Admin;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/{segment}", name="admin_segment", methods={"GET"})
     */
    public function index(string $segment, Admin $admin): Response
    {
        $paginator = $admin->getPaginator($segment);

        return $this->render('admin/users/list.html.twig', [
            'paginator' => $paginator,
        ]);
    }
}
