<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Helpers\GlobalsHelper;
use App\Http\Services as Services;

class MainController
{

    protected $loanService;
    protected $repyamentService;
    protected $tokenService;

    public function __construct(
        Services\LoanRepaymentService $repyamentService,
        Services\LoanService $loanService,
        Services\TokenService $tokenService
    ) {
        $this->repyamentService = $repyamentService;
        $this->loanService = $loanService;
        $this->tokenService = $tokenService;
    }


    /**
     *    Below function generates the token based on the mobile no and name with a validity of 15 mins.
     *    @param $request
     */

    public function firstStep(Requests\FirstStepRequest $a)
    {   
        $returnData = $this->tokenService->process($a);
        GlobalsHelper\putsResponse($returnData['status'], $returnData['message'], $returnData['data'], $returnData['code']);
    }

    /**
     *    Below function created a loan and generated the EMI Schedule
     *    @param $request
     */

    public function secondStep(Requests\SecondStepRequest $a)
    {
        $returnData = $this->loanService->process($a);
        GlobalsHelper\putsResponse($returnData['status'], $returnData['message'], $returnData['data'], $returnData['code']);
    }

    /**
     *    Below function helps to pay the EMI
     *    @param $request
     */

    public function processEmi(Requests\PayEmiRequest $a)
    {
        $returnData = $this->repyamentService->payEmi($a->emi_id, $a->emi_amount);
        GlobalsHelper\putsResponse($returnData['status'], $returnData['message'], $returnData['data'], $returnData['code']);
    }


    /**
     *    Below function gives the EMI Schedule
     *    @param $request
     */

    public function repayamentSchedule(Requests\GetEmiScheduleRequest $a)
    {
        $returnData = $this->repyamentService->getEmiSchedule($a->loan_id);
        GlobalsHelper\putsResponse($returnData['status'], $returnData['message'], $returnData['data'], $returnData['code']);
    }
}
