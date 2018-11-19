<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test/{name}", name="test")
     */
    public function index($name, \Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('bigfisheslt@gmail.com')
            ->setTo('bigfisheslt@gmail.com')
            ->setBody(
                $this->renderView(
                    // templates/test/index.html.twig
                    'test/index.html.twig',
                    array('name' => $name)
                ),
                'text/html'
            )
        ;

        $mailer->send($message);


        return $this->render('test/index.html.twig', [
            'name' => $name,
        ]);
    }
}
