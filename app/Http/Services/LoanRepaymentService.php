<?php

namespace App\Http\Services;

use App\Helpers\GlobalsHelper;
use App\Http\Repository\LoanRepository;
use App\Http\Repository\RepaymentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection as Collect;
use DB;
use Log;
use Config;

class LoanRepaymentService
{

    protected $loanRepo;
    protected $loanRepaymentRepo;


    public function __construct(LoanRepository $loanRepo, RepaymentRepository $loanRepaymentRepo)
    {

        $this->loanRepo = $loanRepo;
        $this->loanRepaymentRepo = $loanRepaymentRepo;
    }

    public function payEmi(int $emiId, float $amount): Collect
    {
        DB::beginTransaction();

        try {

            $emiRecord = $this->loanRepaymentRepo->select($emiId);
            
            if ($emiRecord == false) {
                return  GlobalsHelper\funcReturnsData(false, "EMI Not found", [], 400);
            }

            if ($emiRecord->emi_amount !== $amount) {
                return  GlobalsHelper\funcReturnsData(false, "EMI Amount Not Matched || Partial EMI not allowed", [], 400);
            }

            if ($emiRecord->status == config('commonconfig.emi_status.Paid')) {
                return  GlobalsHelper\funcReturnsData(false, "EMI Already Paid", [], 400);
            }

            $emiUpdateCount =  $this->loanRepaymentRepo->update(['emi_status' => config('commonconfig.emi_status.Paid')], $emiId);
            
            if ($emiUpdateCount == 0) {
                throw new \Exception("Updation failed");
            }
            
            if ($emiRecord->principal_outstanding == 0) {
                $msg = "Congratulations All EMIs are paid";
                $count = $this->loanRepo->update(['loan_status' => config('commonconfig.loan_status.Closed')], $emiRecord->loan_id);
                if ($count == 0) {
                    throw new \Exception("Updation failed");
                }
            } else {
                $msg = "Thank you for the EMI.";
            }

            DB::commit();

            return  GlobalsHelper\funcReturnsData(true, $msg, [], 200);
        } catch (\Exception $ex) {
            
            DB::rollback();
            Log::info($ex);
        }

        return  GlobalsHelper\funcReturnsData(false, $ex->getMessage(), [], 400);
    }

    public function getEmiSchedule(int $loanId): Collect
    {
        $emiRecords = $this->loanRepaymentRepo->getSchedule($loanId);

        if ($emiRecords->isEmpty()) {
            return  GlobalsHelper\funcReturnsData(false, 'Emi Schedule Not Created', [], 400);
        }

        return  GlobalsHelper\funcReturnsData(true, 'EMI Schedule Found', $emiRecords->all(), 200);
    }
}
