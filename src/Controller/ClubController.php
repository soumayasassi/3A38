<?php

namespace App\Controller;

use App\Repository\ClassroomRepository;
use App\Repository\ClubRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('club')]
class ClubController extends AbstractController
{
    #[Route('/read', name: 'app_club')]
    public function index(): Response
    {
        return $this->render('club/index.html.twig', [
            'controller_name' => 'ClubController',
        ]);
    }


    #[Route('/getname/{name}', name: 'app_name')]
    public function getName($name): Response
    {
        return $this->render('club/detail.html.twig',
            ['nom' => $name]);
    }

    #[Route('/list', name: 'list_club')]
    public function list(): Response
    {
        $formations = array(
            array('ref' => 'form147', 'Titre' => 'Formation Symfony
4','Description'=>'formation pratique',
                'date_debut'=>'12/06/2020', 'date_fin'=>'19/06/2020',
                'nb_participants'=>19) ,
            array('ref'=>'form177','Titre'=>'Formation SOA' ,
                'Description'=>'formation theorique','date_debut'=>'03/12/2020','date_fin'=>'10/12/2020',
                'nb_participants'=>0),
            array('ref'=>'form178','Titre'=>'Formation Angular' ,
                'Description'=>'formation theorique','date_debut'=>'10/06/2020','date_fin'=>'14/06/2020',
                'nb_participants'=>12)) ;
        return $this->render('club/list.html.twig', [
            'formations' => $formations ,
        ]);
    }

    #[Route('/detailf/{titre}', name: 'detail')]
    public function details($titre)
    {
        return $this->render("club/detail.html.twig", ["titre"=>$titre]);
    }
///Question 4 Atlier 5
    #[Route('/findclub', name: 'find_club')]
    public function find(ClubRepository $rep): Response
    {
        $clubs = $rep->lastThree();
        return $this->render('club/find.html.twig', compact('clubs')) ;
    }
}
