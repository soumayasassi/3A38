<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Form\RechercheType;
use App\Form\SearchByMoyenneType;
use App\Form\StudentType;
use App\Entity\Student ;
use App\Repository\ClassroomRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Repository\StudentRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{
    #[Route('student', name : 'app_student' )]
public function index() : Response
{
    return new Response("Bonjour mes étudiants") ;
}
    #[Route('readstudent', name : 'read_student' )]
    public function read(StudentRepository $rep) : Response
    { $students = $rep->findAll();
       return $this->render("student/read.html.twig",
           ["students"=>$students]);
    }
    #[Route('/addstudent', name: 'adds')]
    public function  add(ManagerRegistry $doctrine, Request  $request) : Response
    { $student = new Student() ;
        $form = $this->createForm(StudentType::class, $student);
        $form->add('ajouter', SubmitType::class) ;
        $form->handleRequest($request);
        if ($form->isSubmitted())
        { $em = $doctrine->getManager();
            $em->persist($student);
            $em->flush();


            return $this->redirectToRoute('read_student');
        }
        return $this->renderForm("student/add.html.twig",
            ["f"=>$form]) ;
    }
// Question 1 & 3&  5 Atelier 5
    #[Route('/find', name: 'findemail')]
    public function  find(StudentRepository $repo) : Response
    { $students = $repo->TriparEmail();
        $students2 = $repo->findEtat(true);
$id=4 ;
        $students3 = $repo->listStudentsByClasse(4);
        return $this->render("student/find.html.twig",
        ["students"=>$students, "s"=>$students2 ,
            "stu"=>$students3, "id"=>$id]);
    }
// Question 2 QB
    #[Route('/findnsc', name: 'find_nsc')]
    public function  findnsc(StudentRepository $repo, Request $request) : Response
    { $student = New Student() ;
        $students= $repo->findAll();
        $form = $this->createForm(RechercheType::class, $student);
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $nsc = $form['nsc']->getData() ;
            $student = $repo->findOneByNSC($nsc);
            return $this->renderForm("student/findnsc.html.twig",
                ["f"=>$form,"student"=>$student]);
        }
        return $this->renderForm("student/list.html.twig",
            ["f"=>$form,"students"=>$students]);

    }
    //Question 2 DQL
    #[Route('/moyenne/{id}', name: 'moyenne_class')]
    public function MoyenneClasse(StudentRepository  $repo ,ClassroomRepository  $repository,$id ): Response
    {
        $class = $repository->find($id) ;
        $moyenne = $repo->findByMoyenne($id) ;
        return $this->render("student/moyenne.html.twig",["moyenne"=>$moyenne,
            "class"=>$class]);
    }
    //Question 3 DQL
    #[Route('/findByAVG', name: 'find_avg')]
    public function searchStudentByAVG(Request $request, StudentRepository $repository)
    { $students = $repository->findAll();

        if ($request->isMethod("post")) {
            $minMoy=$request->get('minMoy');
            $maxMoy =$request->get('maxMoy');
            $resultOfSearch = $repository->findStudentByAVG($minMoy, $maxMoy);
            return $this->render('student/serachByAVG.html.twig', [
                'students' => $resultOfSearch]);
        }

        return $this->render("student/read.html.twig",
            ["students"=>$students]);
    }
//Question 4 DQL
    #[Route('/redoublant/{id}', name: 'find_redoublant')]
    public function nbRedoublants(StudentRepository $repo ,$id , ManagerRegistry$doctrine )
    {$classroom =$doctrine->getRepository(Classroom::class)->find($id);
        $redoublants = $repo->findRedoublants($id);
        return $this->render('student/redoublant.html.twig', [
            "classroom" => $classroom,
            "redoublants"=>$redoublants]);
    }
}