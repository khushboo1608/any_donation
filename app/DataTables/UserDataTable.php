<?php
namespace App\DataTables;
use App\Models\User;
use DB;
class UserDataTable
{
    public function all()
    {
        $data = User::where('login_type',"!=",1)->orderBy('created_at','desc')->get();
        return $data;
    }
}
