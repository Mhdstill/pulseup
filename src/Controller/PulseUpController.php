<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\PulseUpTypeFormType;
use Symfony\Component\HttpFoundation\Request;

class PulseUpController extends AbstractController
{
    /**
     * @Route("/pulseup/form", name="pulse_up")/home/digiteka/Bureau/symfony-bohemebox
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(PulseUpTypeFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('submitFile')->getData();

            $test = [];
            if (($handle = fopen($file->getPathname(), "r")) !== false) {
                $headerCount = 0;
                while (($data = fgetcsv($handle)) !== false) {
                    if($headerCount == 0) {
                        continue;
                        $headerCount++;
                    }

                    $test = explode(";",$data[0]);
                    echo "#".$data[0]."#<br/>";
                }
                fclose($handle);

                print_r($test);
            }

           // return $this->redirectToRoute('user_balance',);
        }

        return $this->render('pulseup/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/{id}/balance", name="user_balance")
     */
    public function userBalance($id): Response
    {

        return $this->render('pulseup/process.html.twig', [
        ]);
    }
}
