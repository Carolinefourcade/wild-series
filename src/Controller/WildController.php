<?php
// src/Controller/WildController.php
namespace App\Controller;

use App\Entity\Category;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\CategoryType;
use App\Form\ProgramSearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WildController extends AbstractController
{
    /**
     * @Route("/wild", name="wild_index")
     */
    public function index() :Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        if (!$programs) {
            throw $this->createNotFoundException('No program found in program\'s table.');
        }


        $form = $this->createForm(
            ProgramSearchType::class, null,
        ['method' => Request::METHOD_GET]);

        return $this->render('wild/index.html.twig', [
            'programs' => $programs,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param string $slug The slugger
     * @Route("wild/show/{slug}", requirements={"slug"="[a-z0-9-]+"}, defaults={"slug" = null}, name="wild_show")
     * @return Response
     */
    public function show(?string $slug): Response
    {
        if(!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
            $slug = preg_replace('/-/', ' ', ucwords(trim(strip_tags($slug)),"-"));
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with '.$slug.' title, found in program\'s table.'
            );
        }
        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug' => $slug,
        ]);

    }

    /**
     * @Route("wild/category/{categoryName}", requirements={"slug"="[a-z0-9-]+"}, defaults={"slug" = null}, name="wild_category")
     * @param string $categoryName
     * @return Response
     */
    public function showByCategory(string $categoryName): Response
    {
        $category           = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => $categoryName]);
        $programsInCategory = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(
                ['category' => $category],
                ['id'       => 'DESC'],
                3
            );
        return $this->render('wild/category.html.twig', [
            'category' => $category,
            'programs' => $programsInCategory
        ]);
    }

    /**
     * @Route("/wild/{slug}/season", requirements={"slug"="[a-z0-9-]+"}, defaults={"slug" = null}, name="wild_season")})
     * @param string $slug
     * @return Response
     */

    public function showByProgram(string $slug): Response
    {
        $slug = preg_replace('/-/', ' ', ucwords(trim(strip_tags($slug)),"-"));
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);

        $seasonsInProgram = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findBy(['program' => $program]);
        return $this->render('wild/season.html.twig', [
            'program' => $program,
            'seasons' => $seasonsInProgram,
    ]);
    }

    /**
     * @Route("/wild/season/{id}", defaults={"id" = null}, name="wild_episodes"))
     * @param int $id
     * @return Response
     */

    public function showBySeason(int $id): Response
    {
        if (!$id) {
            throw $this
                ->createNotFoundException('No id has been sent to find a season in season\'s table.');
        }
        $season = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findOneBy(['id' => ($id)]);

        $program = $season->getProgram();
        $episodes = $season->getEpisodes();

        return $this->render('wild/episodes.html.twig', [
            'season' => $season,
            'program' => $program,
            'episodes' => $episodes
        ]);
    }

    /**
     * @Route("/wild/episode/{id}", defaults={"id" = null}, name="wild_episode"))
     * @param Episode $episode
     * @return Response
     */


public function showEpisode(Episode $episode): Response
{
    $season = $episode->getSeason();
    $program = $season->getProgram();

    return $this->render('wild/episode.html.twig', [
        'episode' => $episode,
        'program' => $program,
        'season' => $season,
    ]);
}
}
