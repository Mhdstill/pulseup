<?php

namespace App\Controller;

use App\Entity\Balance;
use App\Entity\User;
use App\Repository\BalanceRepository;
use App\Repository\PeriodRepository;
use App\Repository\UserRepository;
use App\Service\BalanceService;
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
    public function index(Request $request, BalanceService $balanceService, UserRepository $userRepository, PeriodRepository $periodRepository): Response
    {
        $form = $this->createForm(PulseUpTypeFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('submitFile')->getData();

            $test = [];
            if (($handle = fopen($file->getPathname(), "r")) !== false) {
                $headerCount = 0;
                while (($data = fgetcsv($handle)) !== false) {
                    if ($headerCount == 0) {
                        $headerCount++;
                        continue;
                    }
                    $line = explode(";", $data[0]);

                    $entityManager = $this->getDoctrine()->getManager();

                    //ADD USER IN DB
                    $userId = $line[0];
                    $user = $userRepository->find($userId);
                    if (!$user) {
                        $user = new User();
                        $user->setId($line[0]);
                        $entityManager->persist($user);
                        $entityManager->flush();
                    }

                    //CALCULATE POINTS
                    $points = 0;
                    $points += $balanceService->firstProductCalculate($line[1]);
                    $points += $balanceService->secondProductCalculate($line[2]);
                    $points += $balanceService->thirdProductCalculate($line[3]);
                    $points += $balanceService->fourthProductCalculate($line[4]);

                    $currentDate = new \DateTime();
                    $period = $periodRepository->findOneByDate($currentDate);

                    if($period) {
                        $balance = new Balance();
                        $balance->setPoints($points);
                        $balance->setUserId($userId);
                        $balance->setPeriodId($period->getId());
                        $balance->setCreatedAt(new \DateTime());
                        $entityManager->persist($balance);
                        $entityManager->flush();
                    }


                }
                fclose($handle);
            }

            return $this->redirectToRoute('user_balance',["id"=>123456789]);
        }

        return $this->render('pulseup/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/{id}/balance", name="user_balance")
     */
    public function userBalance($id, BalanceRepository $balanceRepository, UserRepository $userRepository, PeriodRepository $periodRepository): Response
    {
        $periodPoints = [[]];
        $periods = $periodRepository->findAll();
        $i = 0;
        foreach($periods as $period){
            $points = $balanceRepository->getSum($id, $period->getId());
            var_dump($points);
            $periodPoints[$i]["period"] = $period->getLabel();
            $periodPoints[$i]["points"] = $points;
            $periodPoints[$i]["euros"] = $points  * self::coefficientEuros;
            $i++;
        }

        var_dump($periodPoints);

        return $this->render('pulseup/process.html.twig', [
        ]);
    }
}
