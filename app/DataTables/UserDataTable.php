<?php
namespace App\DataTables;
use App\Models\User;
use DB;
class UserDataTable
{
    public function all()
    {
        $data = User::where('login_type',"!=",1)->where('login_type',"=",2)->orderBy('created_at','desc')->get();
        return $data;
    }

    public function userngo()
    {
        $data = User::where('login_type',"!=",1)->where('login_type',"=",3)->orderBy('created_at','desc')->get();
        return $data;
        // die;
    }

    public function userblood()
    {
        $data = User::where('login_type',"!=",1)->where('login_type',"=",4)->orderBy('created_at','desc')->get();
        return $data;
    }
}
