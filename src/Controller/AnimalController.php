<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Animal;

class AnimalController extends AbstractController
{
    #[Route('/animal', name: 'app_animal')]
    public function index(): Response
    {
        return $this->render('animal/index.html.twig', [
            'controller_name' => 'AnimalController',
        ]);
    }
    
    public function save(EntityManagerInterface $entityManager): Response
    {
        //Crear el Objeto Animal
        $animal = new Animal();
        $animal->setTipo('Perro');
        $animal->setColor('azul');
        $animal->setRaza('husky');
        $animal->setCantidad(27);
                
        //Invocar doctrine para que guarde el objeto
        $entityManager->persist($animal);
        //Ejecutar orden para que doctrine guarde el objeto
        $entityManager->flush();
        
        //Respuesta
        return new Response('Nuevo Animal guardado con el id: '.$animal->getId());
    }
}
