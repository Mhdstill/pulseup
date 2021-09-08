<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\PulseUpTypeFormType;

class PulseUpController extends AbstractController
{
    /**
     * @Route("/pulseup", name="pulse_up")
     */
    public function index(): Response
    {
        $form = $this->createForm(PulseUpTypeFormType::class);

        return $this->render('pulseup/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
