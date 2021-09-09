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
            $periodPoints[$i]["period"] = $period->getLabel();
            $periodPoints[$i]["points"] = $points;
            $periodPoints[$i]["euros"] = $points  * self::coefficientEuros;
            $i++;
        }

        return $periodPoints;
    }
}