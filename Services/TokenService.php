<?php

namespace Services;

use App\Helpers\GlobalsHelper;
use Repository\TokenRepository;
use Illuminate\Http\Request;
use DB;
use Log;
use Config;

class TokenService
{

    protected $tokenRepo;

    public function __construct(TokenRepository $tokenRepo)
    {
        $this->tokenRepo = $tokenRepo;
    }

    public function process($a)
    {
        DB::beginTransaction();

        try {

            $insertData = [
                'mobile_no' => $a->mobile_no,
                'name' => $a->name,
                'created_at' => now()
            ];

            $key = hash('sha256', config('commonconfig.key'));
            $requestData = json_encode($insertData);

            $token = hash_hmac('sha256', $requestData, $key);

            $insertData['token'] = $token;
            
            $this->tokenRepo->insert($insertData);

            DB::commit();

            GlobalsHelper\funcReturnsData(true, "successful", ['token' => $token], 200);
        } catch (\Exception $ex) {

            DB::rollback();
            Log::info($ex);
            GlobalsHelper\funcReturnsData(false, "error", ['error' => $ex->getMessage()], 400);
        }
    }
}
