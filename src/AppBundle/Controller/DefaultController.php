<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
    }

    /**
     * @Route("/dernieres-sorties", name="homepage")
     */
    public function dernieresSortiesAction(Request $request)
    {
        $limit = $request->get('limit');
        $fromDate = $request->get('from_date');

        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery(
            'SELECT s
            FROM AppBundle:Sortie s
            WHERE s.dateSortie > :date_sortie
            ORDER BY s.dateSortie ASC'
        )->setParameter('date_sortie', new \DateTime($fromDate));

        $sorties = $query->setMaxResults($limit)->getArrayResult();

        $response = new JsonResponse();
        $response->setData($sorties);

        return $response;
    }
}
