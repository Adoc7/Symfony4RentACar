<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\Mapping\Entity;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Tests\Compiler\EInterface;


class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function admin(UserRepository $userRepository)
    {
        return $this->render('admin/admin.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
    /**
     * @Route("/admin/delete/{id}", name="delete_user")
     */
    public function deleteUser(User $user, EntityManagerInterface $entityManager, UserRepository $repository)
    {

        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Utilisateur supprimé'
        );

        return $this->render('admin/admin.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
}
