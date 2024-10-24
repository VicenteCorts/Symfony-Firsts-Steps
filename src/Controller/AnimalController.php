<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Animal;

class AnimalController extends AbstractController {

    #[Route('/animal', name: 'app_animal')]
    public function index(EntityManagerInterface $entityManager): Response {
        //Cargar Repositorio 
        $repository = $entityManager->getRepository(Animal::class);

        //Consulta find-all
        $animales = $repository->findAll();

//        //find donde se cumple la condición tipo = perro
//        $animales = $repository->findBy([
//            'tipo' => 'Perro'
//        ]);
//        
//        //find donde se cumple la condición tipo = perro Pero solo saca la priemra coincidencia
//        $animales = $repository->findOneBy([
//            'tipo' => 'Perro'
//        ]);
//        
//        //find donde se cumple la condición tipo = perro && Ordenación descendente
//        $animales = $repository->findBy([
//            'tipo' => 'Perro'
//        ], [
//            'id' => 'DESC'
//        ]);

        
        //QUERY BUILDER CLASE 446
        $qb = $repository->createQueryBuilder('a')
//                ->andWhere("a.color = :color")
//                ->setParameter('color', 'amarillo')
                ->orderBy('a.id', 'DESC')
                ->getQuery();
        
        $resultset = $qb->execute();
        
        var_dump($resultset);
        
        return $this->render('animal/index.html.twig', [
                    'controller_name' => 'AnimalController',
                    'animales' => $animales,
        ]);
    }

    public function save(EntityManagerInterface $entityManager): Response {
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
        return new Response('Nuevo Animal guardado con el id: ' . $animal->getId());
    }

    public function animal(Animal $animal): Response {
//        //Cargar Repositorio y Consulta "find"
//        $animal_repo = $entityManager->getRepository(Animal::class)->find($id);
//        
//        if (!$animal_repo) {
//            throw $this->createNotFoundException(
//                'No existe un registro en la tabla animal con el id: '.$id
//            );
//        }
//
        return new Response('El animal con ese id es: ' . $animal->getTipo());
    }

    public function update(EntityManagerInterface $entityManager, int $id): Response {
        //Cargar doctrine
        //Cargar entityManager
        //Ya lo hacemos en los paréntesis de la función
        //Cargar Repo Animal
        $em = $entityManager->getRepository(Animal::class);

        //Find para conseguir el objeto
        $animal = $em->find($id);

        //Comprobar si el bojeto llega
        if (!$animal) {
            $message = 'No existe un registro en la tabla animal con el id: ' . $id;
        } else {
            //Asignarle valores al objeto capturado
            $animal->setTipo('Koala ' . $id);
            $animal->setColor('Amarillo');
            $animal->setRaza('de la Patagonia');
            $animal->setCantidad(13);

            //Persistir en doctrine - No es necesario para actualizaciones
            $entityManager->persist($animal);

            //Guardar en la BBDD
            $entityManager->flush();

            $message = "El animal ha sido actualizado id: " . $animal->getId();
        }

        //Respuesta
        return new Response($message);
    }

    public function delete(EntityManagerInterface $entityManager, Animal $animal): Response {
        //Cargamos el entityManaget en los parámetros de la función


        if ($animal && is_object($animal)) {
            //Eliminamos de doctrine - de la memoria de objetos en la caché 
            $entityManager->remove($animal);
            //Ejecutamos el delete de la BBDD
            $entityManager->flush();
            //Respuesta final
            $message = 'Animal borrado correctamente';
        } else {
            //Respuesta final
            $message = 'Animal no encontrado';
        }
            
        return new Response($message);
    }
}
