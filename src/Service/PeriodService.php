<?php


namespace App\Service;


class PeriodService
{

    public function frDateToUs($date){
        return implode('-',array_reverse  (explode('/',$date)));
    }
}