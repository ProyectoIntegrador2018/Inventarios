<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

class InventoryController extends Controller
{
  // public function __construct()
  // {
  //     echo 'Inventroy controller';
  // }

  public function index() {
    echo '<br> index???';
      //return view('inventory');
  }

  public function inventory(){

      $devices = DB::select("
          SELECT COUNT(d.id) as quantity, d.name, d.brand, d.model
          FROM devices d JOIN states s
          ON d.id = s.device_id
          WHERE s.state = 'Available'
          GROUP BY d.name, d.brand, d.model
      ");

      $quantity = count($devices);

      return view('inventory')->with('devices', $devices)->with('quantity', $quantity);
  }
}
