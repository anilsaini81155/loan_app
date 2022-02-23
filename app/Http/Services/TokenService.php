<?php

namespace App\Http\Services;

use App\Helpers\GlobalsHelper;
use App\Http\Repository\TokenRepository;
use App\Http\Repository\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection as Collect;
use DB;
use Log;
use Config;

class TokenService
{

    protected $tokenRepo;
    protected $userRepo;

    public function __construct(TokenRepository $tokenRepo, UserRepository $userRepo)
    {
        $this->tokenRepo = $tokenRepo;
        $this->userRepo = $userRepo;
    }

    public function process($a): Collect
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

            $userData = $this->userRepo->checkUserExists($a->mobile_no, $a->name);

            if ($userData->isEmpty()) {
                $this->userRepo->insert($insertData);
            }

            $insertData['token'] = $token;

            $this->tokenRepo->insert($insertData);

            DB::commit();

            return  GlobalsHelper\funcReturnsData(true, "successful", ['token' => $token], 200);
        } catch (\Exception $ex) {

            DB::rollback();
            Log::info($ex);
            return  GlobalsHelper\funcReturnsData(false, "error", ['error' => $ex->getMessage()], 400);
        }
    }
}
