<?php

namespace Services;

use App\Helpers\GlobalsHelper;
use Repository\LoanRepository;
use Repository\RepaymentRepository;
use Illuminate\Http\Request;
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

    public function process(Request $a)
    {

        DB::beginTransaction();

        try {

            $insertData = [
                'loan_amount' => $a->loan_amount,
                'loan_tenure' => $a->loan_tenure,
                'created_at' => now()
            ];

            //repaymemt section can only pay EMIs one by one ..

            $returnData = $this->loanRepo->insert($insertData);

            DB::commit();

            GlobalsHelper\funcReturnsData(true, "successful", ['id' => $returnData], 200);
        } catch (\Exception $ex) {

            DB::rollback();
            Log::info($ex);
            GlobalsHelper\funcReturnsData(false, "error", ['error' => $ex->getMessage()], 400);
        }
    }

    public function payEmi(int $emiId, float $amount)
    {
        DB::beginTransaction();

        try {

            $emiRecord = $this->loanRepaymentRepo->select($emiId);

            if ($emiRecord->isEmpty()) {
                GlobalsHelper\funcReturnsData(false, "EMI Not found", [], 400);
            }

            if ($emiRecord->emi_amount !== $amount) {
                GlobalsHelper\funcReturnsData(false, "EMI Amount Not Matched || Partial EMI not allowed", [], 400);
            }

            if ($emiRecord->status == config('commonconfig.emi_status.Paid')) {
                GlobalsHelper\funcReturnsData(false, "EMI Already Paid", [], 400);
            }

            $emiUpdateCount =  $this->loanRepaymentRepo->update(['status' => config('commonconfig.emi_status.Paid')], $emiId);
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

            GlobalsHelper\funcReturnsData(true, $msg, [], 200);
        } catch (\Exception $ex) {
            DB::rollback();
            Log::info($ex);
        }

        GlobalsHelper\funcReturnsData(false, $ex->getMessage(), [], 400);
    }
}
