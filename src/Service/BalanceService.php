<?php


namespace App\Service;


use App\Repository\BalanceRepository;
use App\Repository\PeriodRepository;

class BalanceService
{

    const coefficientEuros = 0.001;

    public function calculatePoints(){

    }

    public function getUserPointsByPeriod($userId, PeriodRepository $periodRepository, BalanceRepository $balanceRepository){
        $periodPoints = [[]];
        $periods = $periodRepository->findAll();
        $i = 0;
        foreach($periods as $period){
            $points = $balanceRepository->getSum($userId, $period->getId());
            var_dump($points);
            $periodPoints[$i]["period"] = $period->getLabel();
            $periodPoints[$i]["points"] = $points;
            $periodPoints[$i]["euros"] = $points  * self::coefficientEuros;
            $i++;
        }

        return $periodPoints;
    }

    public function firstProductCalculate($nbProduct1){
        return $nbProduct1 * 5;
    }

    public function secondProductCalculate($nbProduct2, $product1Selled = true){
        if(!$product1Selled){
            return 0;
        }

        return $nbProduct2 * 5;
    }

    public function thirdProductCalculate($nbProduct3){
        return floor($nbProduct3/2) * 15;

    }

    public function fourthProductCalculate($nbProduct4){
        return $nbProduct4 * 35;
    }

    public function pointsToEuros($points){
        return $points * self::coefficientEuros;
    }

}