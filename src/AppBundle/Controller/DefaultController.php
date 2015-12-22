<?php

namespace AppBundle\Controller;

use AppBundle\Form\FileSearchType;
use Oro\FileInventorBundle\Inventor\FileSearchResult;
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
        if ($form->isSubmitted() && $form->isValid()) {
            $searchResult = $this->get('oro_file_inventor')->searchString($form['search']->getData());
        }

        return $this->render('default/index.html.twig', [
            'form' => $form->createView(),
            'searchResult' => $searchResult
        ]);
    }
}
