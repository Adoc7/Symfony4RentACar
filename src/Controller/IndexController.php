<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\CarType;
use App\Entity\Keyword;
use App\Services\ImageHandler;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Node\Expression\Binary\RangeBinary;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class IndexController extends AbstractController
{

    /**
     * @Route("/car/add", name="add")
     */
    public function add(EntityManagerInterface $manager, Request $request, ImageHandler $handler)
    {

        $path = $this->getParameter('kernel.project_dir') . '/public/images';
        $form = $this->createForm(CarType::class, null, ['path' => $path]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupère les valurs soumises sous forme d'objet Car
            $car = $form->getData();

            //permet de récupérer l'utilisateur courant ou connecté
            $user = $this->getUser();

            $car->setUser($user);

            // Donne le nom à l'image
            // $image->setName($name);

            $manager->persist($car);
            $manager->flush();

            $this->addFlash(
                'notice',
                'Voiture ajoutée'
            );

            return $this->redirectToRoute('home');
        }


        return $this->render('add.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/car/edit/{id}", name="edit")
     */
    public function edit(Car $car, EntityManagerInterface $manager, Request $request)
    {

        $path = $this->getParameter('kernel.project_dir') . '/public/images';
        $form = $this->createForm(CarType::class, $car, ['path' => $path]);
        $this->denyAccessUnlessGranted('EDIT', $car);
        // $form = $this->createForm(CarType::class, $car);

        $form->handleRequest($request);

        // $manager->flush();
        if ($form->isSubmitted() && $form->isValid()) {
            $path = $this->getParameter('kernel.project_dir') . '/public/images';
            // $image = $form->getData()->getImage();
            // $image->setPath($path);

            $manager->flush();

            $this->addFlash(
                'notice',
                'Voiture modifiée'
            );

            return $this->redirectToRoute('home');
        }


        return $this->render('edit.html.twig', [
            'car' => $car,
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/car/delete/{id}", name="delete")
     */
    public function delete(Car $car, EntityManagerInterface $manager)
    {
        $this->denyAccessUnlessGranted('DELETE', $car);
        $manager->remove($car);
        $manager->flush();

        return $this->redirectToRoute('home');
    }



    /**
     * @Route("/contact", name="contact")
     */
    public function contact()
    {
        return $this->render('contact.html.twig');
    }

    /**
     *
     * @Route("/", name="home", methods={"get"})
     */
    public function index(CarRepository $carRepository)
    {

        $cars = $carRepository->findAll();
        $car = $carRepository->findBy(['carburant' => 'diesel', 'color' => 'vert'], null, 2);
        // dd($car);
        return $this->render('index.html.twig', [
            'cars' => $cars
        ]);
    }


    /**
     * @Route("/show/{id}", name="show")
     */
    public function show(car $car)
    {
        return $this->render('show.html.twig', [
            'car' => $car
        ]);
    }

    /**
     * @Route("delete/keyword/{id}",
     *      name="delete_keyword",
     *      methods={"GET"},
     *      condition="request.headers.get('X-Requested-With') matches '/XMLHttpRequest/i'"
     *      )
     */
    public function deleteKeyword(Keyword $keyword, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($keyword);
        $entityManager->flush();

        return new JsonResponse();

    }





}