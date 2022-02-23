<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Helpers\GlobalsHelper;
use Services;

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
     *    Below function calculates the 
     *    @param $request
     */

    public function firstStep(Requests\FirstStepRequest $a)
    {

        //insert the above data and generate a token that will be valid for 15 mins ..

        $returnData = $this->tokenService->process($a);
        GlobalsHelper\putsResponse($returnData['status'], $returnData['msg'], $returnData['data'], $returnData['code']);
    }

    /**
     *    Below function calculates the 
     *    @param $request
     */

    public function secondStep(Requests\SecondStepRequest $a)
    {
        //store the data in the table.
        //have one migration file will create the tables ..
        $returnData = $this->repyamentService->process($a);

        GlobalsHelper\putsResponse($returnData['status'], $returnData['msg'], $returnData['data'], $returnData['code']);
    }

    /**
     *    Below function calculates the 
     *    @param $request
     */

    public function repayamentSchedule()
    {
        //weekly repaymentSchedule..
        $returnData = $this->repyamentService->process();

        GlobalsHelper\putsResponse($returnData['status'], $returnData['msg'], $returnData['data'], $returnData['code']);
    }
}
