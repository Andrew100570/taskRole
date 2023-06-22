<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class DashboardController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/dashboard', name: 'dashboard')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $array = ['admin' => 'ROLE_ADMIN', 'user' => 'ROLE_USER'];
        $roles = $this->getUser()->getRoles();
        if (!isset($roles)) {
            return $this->render('registration/register.html.twig', [
            ]);
        }
        $id = $this->getUser()->getId();
        foreach ($array as $key => $arr) {
            if (array_search($arr, $roles) !== false) {
                $route = $this->redirectToRoute($key, [
                    'slug' => $id,
                ]);
                if ($key === 'admin') {
                    return $route;
                } else {
                    return $route;
                }
            }
        }
    }

    #[Route('/admin/{slug}', name: 'admin')]
    public function admin($slug): Response
    {
        return $this->content($slug, $param = 'admin');
    }

    #[Route('/user/{slug}', name: 'user')]
    public function user($slug): Response
    {
        return $this->content($slug, $param = 'user');
    }

    public function content($id, $route)
    {
        return $this->render('dashboard/index.html.twig', [
            'id' => $id,
            'name' => $this->getUser()->getEmail(),
            'role' => $route,
        ]);
    }

}
