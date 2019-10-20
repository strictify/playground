<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="admin_dashboard", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('admin_base.html.twig');
    }
}
