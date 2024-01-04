<?php 
namespace App\DataTables;
use App\Models\Ngo;
use DB;

class NgoDataTable{

  public function all()
  {
      // $data = Banner::where('is_disable',0)->get();
      // $data = Photos::orderBy('created_at','desc')->get();
      $data = Ngo::select('ngos.*', 'users.name as user_name')
      ->orderBy('ngos.created_at', 'desc')
      ->leftJoin('users', 'ngos.user_id', '=', 'users.id')
      ->get();
      // exit;
      return $data;
  }

}
?>