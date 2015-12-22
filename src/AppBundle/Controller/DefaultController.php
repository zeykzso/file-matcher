<?php

namespace AppBundle\Controller;

use AppBundle\Form\FileSearchType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(new FileSearchType());
        $form->handleRequest($request);

        $searchResult = null;
        $searched = false;

        if ($form->isSubmitted() && $form->isValid()) {
            $isRegex = $form['isRegex']->getData();
            $searchResult = $this->get('oro_file_inventor')->search($form['search']->getData(), $isRegex);
            $searched = true;
        }

        return $this->render('default/index.html.twig', [
            'form' => $form->createView(),
            'searchResult' => $searchResult,
            'searched' => $searched
        ]);
    }
}
