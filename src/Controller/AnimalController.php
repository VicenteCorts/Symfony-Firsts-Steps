<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Animal;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Form\AnimalType;

use Symfony\Component\HttpFoundation\Session\Session;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Email;

class AnimalController extends AbstractController {

    public function validarEmail($email) {
        
        $validator = Validation::createValidator();
        $errores = $validator->validate($email, [
            new Email()
        ]);
        
        if(count($errores)!=0){
            echo "El email no se ha validado correctamente";
        }else{
            echo "El email SE HA VALIDADO correctamente";
        }
        
        die();
    }
    
    public function crearAnimal(EntityManagerInterface $entityManager, Request $request): Response {
        $animal = new Animal();
        $form = $this->createForm(AnimalType::class, $animal);

        //Conseguir los datos introducidos en el Formulario
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$animal` variable has also been updated
            $animal = $form->getData();
            
            //Invocar doctrine para que guarde el objeto
            $entityManager->persist($animal);
            //Ejecutar orden para que doctrine guarde el objeto
            $entityManager->flush();
            
            //SESION FLASH
            $session = new Session();
            $session->getFlashBag()->add('message', 'Animal creado');
           
            return $this->redirectToRoute('crear-animal');
        }

        //Lo pasamos a una vista para imprimir el formulario
        return $this->render('animal/crear-animal.html.twig', [
                    'form' => $form,
        ]);
    }

    #[Route('/animal', name: 'app_animal')]
    public function index(EntityManagerInterface $entityManager): Response {
        //Cargar Repositorio 
        $repository = $entityManager->getRepository(Animal::class);

        //Consulta find-all
        $animales = $repository->findAll();

        //find donde se cumple la condición tipo = perro
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
//        //QUERY BUILDER CLASE 446
//        $qb = $repository->createQueryBuilder('a')
//                ->andWhere("a.color = :color")
//                ->setParameter('color', 'amarillo')
//                ->orderBy('a.id', 'DESC')
//                ->getQuery();
//        
//        $resultset = $qb->execute();
//        //DQL CLASE 447
//        $dql = "SELECT a FROM App\Entity\Animal a WHERE a.color = 'amarillo'";
//                
//        $query = $entityManager->createQuery($dql);
//        
//        var_dump($query->getResult());
        //SQL
        $conn = $entityManager->getConnection();
        $sql = "SELECT * FROM Animal ORDER BY id DESC";
        $resultSet = $conn->executeQuery($sql);

        // Obtener los resultados reales
        $results = $resultSet->fetchAllAssociative();
//        var_dump($results);
        //REPOSITORIO
        $animals = $repository->findByRaza('DESC');

        return $this->render('animal/index.html.twig', [
                    'controller_name' => 'AnimalController',
                    'animales' => $animals,
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
