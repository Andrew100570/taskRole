<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\DataType;
use App\Entity\Data;
use App\Entity\User;

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

        $id = $this->getUser()->getId();
        foreach ($array as $key => $arr) {
            if (array_search($arr, $this->getUser()->getRoles()) !== false) {
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
        $form = $this->createForm(DataType::class, new Data(), [
            'action' => $this->generateUrl('data_form'),
        ]);

        $data = ($route === 'admin')
            ? $this->entityManager->getRepository(User::class)->findAll()
            : $this->entityManager->getRepository(Data::class)->findOneBySomeField($this->getUser()->getId());

        $dates = !empty($data) ? $data : '';

        return $this->render('dashboard/index.html.twig', [
            'id' => $id,
            'name' => $this->getUser()->getEmail(),
            'role' => $route,
            'formData' => $form->createView(),
            'dates' => $dates,
        ]);
    }

    #[Route('/data_form', name: 'data_form',methods: ['POST'])]
    /**
     * param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function data_form(Request $request, EntityManagerInterface $entityManager)
    {

        $data = new Data();

        $form = $this->createForm(DataType::class, $data);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

        $url = $_SERVER['HTTP_REFERER'];
        $routeName = strpos($url, 'admin');

        $route = ($routeName === 'false') ? 'admin' : 'user';

        $data->setRecordingDate(new \DateTime());

        $data->setUser($this->getUser());

        $path = $this->getParameter('kernel.project_dir') . '/public/uploads';
        $img = $request->files->get('data')['file'];

        $type = $img->getmimeType();

        if (!is_bool(strpos($type, 'jpeg')) OR !is_bool(strpos($type, 'pdf')) OR !is_bool(strpos($type, 'doc'))) {
            $img->move($path,rand(1, 15000).$img->getClientOriginalName());
        } else {
            $data->setFile('Null');
        }


        $entityManager->persist($data);
        $entityManager->flush();
        }

        $data = ($route === 'admin')
            ? $this->entityManager->getRepository(User::class)->findAll()
            : $this->entityManager->getRepository(Data::class)->findOneBySomeField($this->getUser()->getId());

        $dates = !empty($data) ? $data : '';

        return $route = $this->redirectToRoute($route, [
            'slug' => $this->getUser()->getId(),
            'dates' => $dates,
        ]);
    }

    #[Route('/user_delete/{slug}', name: 'user_delete',methods: ['GET','DELETE'])]
    public function user_delete($slug)
    {
        $user = $this->entityManager->getRepository(User::class)->find($slug);

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        $data = $this->entityManager->getRepository(User::class)->findAll();

        $dates = !empty($data) ? $data : '';

        return $route = $this->redirectToRoute('admin', [
            'slug' => $this->getUser()->getId(),
            'dates' => $dates,
        ]);
    }

}
