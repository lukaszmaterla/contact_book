<?php

namespace ContactBookBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ContactController extends Controller
{
    /**
     * @Route("/new")
     * @Template("")
     */
    public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }
}
