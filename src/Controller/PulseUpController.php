<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\PulseUpTypeFormType;


class PulseUpController extends AbstractController
{
    /**
     * @Route("/pulseup/form", name="pulse_up")
     */
    public function index(): Response
    {
        $form = $this->createForm(PulseUpTypeFormType::class);

        if ($form->isSubmitted() && $form->isValid()) {
            $pulseup = $form->getData();

            echo "ok";

            return $this->redirectToRoute('process');
        }

        return $this->render('pulseup/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/pulseup/process", name="process")
     */
    public function process(): Response
    {
        return $this->render('pulseup/process.html.twig', [
        ]);
    }
}
