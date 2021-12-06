<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/program",name="program_")
 */
class ProgramController extends AbstractController

{
    /**
     * Show all rows from Program's entity
     *
     * @Route("/",name="index")
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        return $this->render(
            'program/index.html.twig',
            ['programs' => $programs]
        );
    }


    /**
     * @Route("/show/{id<^[0-9]+$>}", name="show", methods="GET", requirements={"id"="\d{1,}"})
     * @return Response
     */
    public function show(int $id): Response
    {
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['id' => $id]);

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : ' . $id . ' found in program\'s table.'
            );
        }
        $seasons = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findBy(['program'=> $program]);

        return $this->render('program/show.html.twig', [
            'program' => $program,
            'seasons' => $seasons,
        ]);
    }

    /**
     * @Route("/{programId}/seasons/{seasonId}", name="season_show")
     */
    Public function showSeason (int $ProgramId, int $seasonId): Response
    {
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneById($ProgramId);

        $season = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findOneById($seasonId);

        $episodes = $this->getDoctrine()
            ->getRepository(Episode::class)
            ->findBySeason($season);

        return $this->render('/program/season_show.html.twig',
        ['program'  => $program,
         'season'   => $season,
         'episodes' => $episodes,
            ]);
    }


}

