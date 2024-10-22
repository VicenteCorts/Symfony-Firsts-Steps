<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'hello' => 'Hola Symfony',
        ]);
    }
    
//    #[Route('/bichos', name: 'app_animal')]
    public function animales($nombre, $apellidos) {
        
        $title = 'Bienvenido a la página de animales';
        $animales = array ('perro', 'gato', 'paloma', 'rata');
        $aves = array (
            'raza' => 'palomo',
            'loro' => 'pirata',
            'paloma' => 17,
            'aguila' => true
        );
        
        return $this->render('home/animales.html.twig',[
            'title' => $title,
            'nombre' => $nombre,
            'apellidos' => $apellidos,
            'animales' => $animales,
            'aves' => $aves,
        ]);
    }
    
    public function redirigir() {
//        return $this->redirectToRoute('home', array(), 301);
        return $this->redirect('http://localhost:8000/inicio');
    }
}
