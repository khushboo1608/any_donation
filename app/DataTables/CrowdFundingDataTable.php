<?php 
namespace App\DataTables;
use App\Models\CrowdFunding;
use DB;

class CrowdFundingDataTable{
  
  public function all(){
    $data = CrowdFunding::orderBy('created_at','ASC')->get();
    return $data;
  }
}
?>