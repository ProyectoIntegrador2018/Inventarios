<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

class AboutController extends Controller
{
  public function viewAbout()
  {
      return view('about');
  }

  public function getInventory()
  {
    $devices = DB::select("
      SELECT COUNT(d.id) as quantity, d.name, d.brand, d.model
      FROM devices d JOIN states s
      ON d.id = s.device_id
      WHERE s.state = 'Available'
      GROUP BY d.name, d.brand, d.model
    ");

    $quantity = count($devices);

    return view('inventory-guest')->with('devices', $devices)->with('quantity', $quantity);
  }
}
