<?php

namespace Oro\FileInventorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('OroFileInventorBundle:Default:index.html.twig');
    }
}
