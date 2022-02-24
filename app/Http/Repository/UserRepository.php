<?php

namespace App\Http\Repository;

use App\Models\User;


class UserRepository  extends BaseRepository
{

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function checkUserExists(int $mobile_no, string $name)
    {
        return  $this->model->where('mobile_no', $mobile_no)
            ->where('name', $name)
            ->get();
    }
}
