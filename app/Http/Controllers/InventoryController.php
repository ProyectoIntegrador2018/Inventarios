<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use DB;

class InventoryController extends Controller
{
  public function __construct(){
    $this->middleware('auth');
  }

  public function viewInventory(){

    $id = Auth::user()->id;

    $devices = DB::select("
      SELECT COUNT(d.id) as quantity, d.name, d.brand, d.model, l.building, l.room, u.id
      FROM states s
      JOIN devices d ON d.id = s.device_id
      JOIN locations l ON l.id = d.location_id
      JOIN users u ON u.id = l.user_id
      WHERE s.state = 'Available' AND u.id = '$id'
      GROUP BY d.name, d.brand, d.model, l.building, l.room, u.id;
    ");

    $quantity = count($devices);

    return view('inventory')->with('devices', $devices)->with('quantity', $quantity);
  }
  
}
