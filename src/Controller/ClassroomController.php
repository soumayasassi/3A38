<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Form\ClassroomType;
use App\Repository\ClassroomRepository;
use  Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClassroomController extends AbstractController
{
    #[Route('/classroom', name: 'app_classroom')]
    public function index(): Response
    {
        return $this->render('classroom/index.html.twig', [
            'controller_name' => 'ClassroomController',
        ]);
    }


    #[Route('/read', name: 'read_classroom')]
    public function read(ManagerRegistry $doctrine): Response
    {
        $classrooms = $doctrine->getRepository(Classroom::class)->findAll();
        return $this->render('classroom/read.html.twig',
            ["classrooms" => $classrooms]);
    }

    #[Route('/read2', name: 'readclassroom')]
    public function read2(ClassroomRepository $rep): Response
    {
        $classrooms = $rep->findAll();
        return $this->render('classroom/read.html.twig',
            ["classrooms" => $classrooms]);
    }

    #[Route('/add', name: 'add_classroom')]
    public function  add(ManagerRegistry $doctrine, Request  $request) : Response
    { $classroom = new Classroom() ;
        $form = $this->createForm(ClassroomType::class, $classroom);
        $form->add('ajouter', SubmitType::class) ;
        $form->handleRequest($request);
        if ($form->isSubmitted())
        { $em = $doctrine->getManager();
            $em->persist($classroom);
            $em->flush();
            return $this->redirectToRoute('read_classroom');
        }
        return $this->renderForm("classroom/add.html.twig",
            ["f"=>$form]) ;


    }

    #[Route('/update/{id}', name: 'update_classroom')]
    public function  update(ManagerRegistry $doctrine,$id,  Request  $request) : Response
    { $classroom = $doctrine
        ->getRepository(Classroom::class)
        ->find($id);
        $form = $this->createForm(ClassroomType::class, $classroom);
        $form->add('update', SubmitType::class) ;
        $form->handleRequest($request);
        if ($form->isSubmitted())
        { $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('read_classroom');
        }
        return $this->renderForm("classroom/update.html.twig",
            ["f"=>$form]) ;


    }
   #[Route("/delete/{id}", name:'delete_classroom')]
    public function delete($id, ManagerRegistry $doctrine)
    {$c = $doctrine
        ->getRepository(Classroom::class)
        ->find($id);
        $em = $doctrine->getManager();
        $em->remove($c);
        $em->flush() ;
        return $this->redirectToRoute('read_classroom');
    }


    #[Route('/add2', name: 'addc2')]
    public function  add2(ManagerRegistry $doctrine , Request $request) : Response
    { $c = new Classroom() ;
        if($request->isMethod('POST'))
        { $c->setName($request->get('name'));
            $em = $doctrine->getManager();
            $em->persist($c);
            $em->flush();
            return $this->redirectToRoute('read_classroom');
        }
        return $this->render('classroom/add2.html.twig');



    }
}