<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\User;
use App\Location;
use App\Device;

use DB;

use Carbon\Carbon;

class DeviceController extends Controller
{
    public function createDevice(Request $request)
    {

        $finalMessage = "";
        $finalStatus = 0;

        $name = $request->input('name');
        $brand = $request->input('brand');
        $model = $request->input('model');
        $quantity = $request->input('quantity');
        $serialNumbers = $request->input('serial_numbers');
        $tags = $request->input('tags');

        $building = $request->input('building');
        $room = $request->input('room');

        $requiredSerialNumbers = $request->input('requiredSN');

        $id = Auth::user()->id;

        // Create location if does not exists previously
        // and if exists, get the id and put in the location_id of the device
        // the user_id of the location is the one gotten previously
        // Creation of the device

        $locationExistence = DB::table('locations')->where([['building', '=', $building],['room', '=', $room]])->get();

        if($requiredSerialNumbers == "yes"){
            //Do the process like always
            if(count($locationExistence) == 0){

                // That location does not exist yet, let's create it

                DB::table('locations')->insert(
                    [
                        'building'   => $building,
                        'room'       => $room,
                        'user_id'    => $id,
                        'created_at' => \Carbon\Carbon::now(),
                        'updated_at' => \Carbon\Carbon::now()
                    ]
                );

                $locationID = DB::table('locations')->where([['building', '=', $building],['room', '=', $room]])->get(['id']);
                $locationID = $locationID[0]->id;

                // Let's check if the quantity variable and the serial numbers array size are the same
                $serialNumbersSeparated = explode(",", $serialNumbers);

                if($quantity == count($serialNumbersSeparated)){

                    // $response["message"] = "Are the same size";

                    for($x = 0; $x < $quantity; $x++) {

                        $temporarySerialNumber = $serialNumbersSeparated[$x];

                        // Let's check if the serial number exists in the brand already
                        $serialNumberExistance = DB::table('devices')->where([
                            ['serial_number', '=', $temporarySerialNumber],
                            ['model', '=', $model]
                        ])->get();

                        if(count($serialNumberExistance) > 0){
                            // The device already exists in the database, the aggregate will be skipped
                            // At the moment, where are not returning this situation
                            $finalMessage = "The device already exists.";
                            $finalStatus = 2;
                            continue;
                        }else{
                            // The device does not exist, it will be created
                            DB::table('devices')->insert(
                                [
                                    'name'               => $name,
                                    'serial_number'      => $temporarySerialNumber,
                                    'brand'              => $brand,
                                    'model'              => $model,
                                    'added_to_warehouse' => \Carbon\Carbon::now(),
                                    'location_id'        => $locationID,
                                    'created_at'         => \Carbon\Carbon::now(),
                                    'updated_at'         => \Carbon\Carbon::now()
                                ]
                            );

                            $lastDeviceAddedID = DB::table('devices')->where(
                                [
                                    ['serial_number', '=', $serialNumbersSeparated[$x]],
                                    ['model', '=', $model]
                                ]
                                )->get(['id']);
                            $lastDeviceAddedID = $lastDeviceAddedID[0]->id;

                            DB::table('states')->insert(
                                [
                                    'state'      => 'Available',
                                    'device_id'  => $lastDeviceAddedID,
                                    'created_at' => \Carbon\Carbon::now(),
                                    'updated_at' => \Carbon\Carbon::now()
                                ]
                            );

                            $separatedTags = explode(",", $tags);

                            for($y = 0; $y < count($separatedTags); $y++){

                                // Search if the tag actually exists
                                // If it exists, just get the id to after use it in the relation
                                // If not exists already, the tag and device_tag instances are going to be created

                                // strtolower($str);

                                $tagExistance = DB::table('tags')->where('tag', '=', $separatedTags[$y])->get();

                                if(count($tagExistance) > 0){

                                    // The tag already exists, let's get tag id and just create the relation

                                    $tagID = DB::table('tags')->where('tag', '=', $separatedTags[$y])->get(['id']);
                                    $tagID = $tagID[0]->id;

                                    DB::table('device_tag')->insert(
                                        [
                                            'tag_id' => $tagID,
                                            'device_id' => $lastDeviceAddedID,
                                            'created_at' => \Carbon\Carbon::now(),
                                            'updated_at' => \Carbon\Carbon::now()
                                        ]
                                    );

                                }else{
                                    // The tag doesn't exists, let's create both
                                    DB::table('tags')->insert(
                                        [
                                            //'tag' => $separatedTags[$y],
                                            'tag' => strtolower($separatedTags[$y]),
                                            'created_at' => \Carbon\Carbon::now(),
                                            'updated_at' => \Carbon\Carbon::now()
                                        ]
                                    );

                                    $lastTagAddedID = DB::table('tags')->orderBy('id', 'desc')->first();

                                    DB::table('device_tag')->insert(
                                        [
                                            'tag_id' => $lastTagAddedID->id,
                                            'device_id' => $lastDeviceAddedID,
                                            'created_at' => \Carbon\Carbon::now(),
                                            'updated_at' => \Carbon\Carbon::now()
                                        ]
                                    );
                                }
                            }
                        }

                    }

                    if($finalStatus == 2){
                        $response["status"] = 2;
                    }else{
                        $response["status"] = 1;
                    }

                    if($finalMessage != ""){
                        $response["messsage"] = $finalMessage;
                    }else{
                        $response["messsage"] = "Location, Devices, States, Tags and Device-Tag instances created.";
                    }

                    return json_encode($response);

                }else{
                    //Return the valid reponse code to indicate the following situation
                    $response["status"] = 2;
                    $response["message"] = "The quantity value specifies and the serial numbers size provided are not the same amount.";
                    return json_encode($response);
                }

            }else{

                // A location with that building and room already exists
                // $response["message"] = "The location already exists, nothing more done, just the verification.";

                $locationID = DB::table('locations')->where([['building', '=', $building],['room', '=', $room]])->get(['id']);
                $locationID = $locationID[0]->id;

                // Let's check if the quantity variable and the serial numbers array size are the same
                $serialNumbersSeparated = explode(",", $serialNumbers);

                if($quantity == count($serialNumbersSeparated)){

                    // $response["message"] = "Are the same size";

                    for($x = 0; $x < $quantity; $x++) {

                        $temporarySerialNumber = $serialNumbersSeparated[$x];

                        // Let's check if the serial number exists in the brand already
                        $serialNumberExistance = DB::table('devices')->where([
                            ['serial_number', '=', $temporarySerialNumber],
                            ['model', '=', $model]
                        ])->get();

                        if(count($serialNumberExistance) > 0){
                            // The device already exists in the database, the aggregate will be skipped
                            // At the moment, where are not returning this situation
                            $finalMessage = "The device already exists.";
                            $finalStatus = 2;
                            continue;
                        }else{
                            // The device does not exist, it will be created
                            DB::table('devices')->insert(
                                [
                                    'name'               => $name,
                                    'serial_number'      => $temporarySerialNumber,
                                    'brand'              => $brand,
                                    'model'              => $model,
                                    'added_to_warehouse' => \Carbon\Carbon::now(),
                                    'location_id'        => $locationID,
                                    'created_at'         => \Carbon\Carbon::now(),
                                    'updated_at'         => \Carbon\Carbon::now()
                                ]
                            );

                            $lastDeviceAddedID = DB::table('devices')->where(
                                [
                                    ['serial_number', '=', $serialNumbersSeparated[$x]],
                                    ['model', '=', $model]
                                ]
                                )->get(['id']);
                            $lastDeviceAddedID = $lastDeviceAddedID[0]->id;

                            DB::table('states')->insert(
                                [
                                    'state'      => 'Available',
                                    'device_id'  => $lastDeviceAddedID,
                                    'created_at' => \Carbon\Carbon::now(),
                                    'updated_at' => \Carbon\Carbon::now()
                                ]
                            );

                            $separatedTags = explode(",", $tags);

                            for($y = 0; $y < count($separatedTags); $y++){

                                // Search if the tag actually exists
                                // If it exists, just get the id to after use it in the relation
                                // If not exists already, the tag and device_tag instances are going to be created

                                $tagExistance = DB::table('tags')->where('tag', '=', $separatedTags[$y])->get();

                                if(count($tagExistance) > 0){

                                    // The tag already exists, let's get tag id and just create the relation

                                    $tagID = DB::table('tags')->where('tag', '=', $separatedTags[$y])->get(['id']);
                                    $tagID = $tagID[0]->id;

                                    DB::table('device_tag')->insert(
                                        [
                                            'tag_id' => $tagID,
                                            'device_id' => $lastDeviceAddedID,
                                            'created_at' => \Carbon\Carbon::now(),
                                            'updated_at' => \Carbon\Carbon::now()
                                        ]
                                    );

                                }else{
                                    // The tag doesn't exists, let's create both
                                    DB::table('tags')->insert(
                                        [
                                            //'tag' => $separatedTags[$y],
                                            'tag' => strtolower($separatedTags[$y]),
                                            'created_at' => \Carbon\Carbon::now(),
                                            'updated_at' => \Carbon\Carbon::now()
                                        ]
                                    );

                                    $lastTagAddedID = DB::table('tags')->orderBy('id', 'desc')->first();

                                    DB::table('device_tag')->insert(
                                        [
                                            'tag_id' => $lastTagAddedID->id,
                                            'device_id' => $lastDeviceAddedID,
                                            'created_at' => \Carbon\Carbon::now(),
                                            'updated_at' => \Carbon\Carbon::now()
                                        ]
                                    );
                                }
                            }
                        }

                    }

                    if($finalStatus == 2){
                        $response["status"] = 2;
                    }else{
                        $response["status"] = 1;
                    }

                    if($finalMessage != ""){
                        $response["messsage"] = $finalMessage;
                    }else{
                        $response["messsage"] = "Location, Devices, States, Tags and Device-Tag instances created.";
                    }

                    return json_encode($response);

                }else{
                    //Return the valid reponse code to indicate the following situation
                    $response["status"] = 2;
                    $response["message"] = "The quantity value specifies and the serial numbers size provided are not the same amount.";
                    return json_encode($response);
                }

            }
        }else{
            //Do the process without serial numbers
            if(count($locationExistence) == 0){

                // That location does not exist yet, let's create it

                DB::table('locations')->insert(
                    [
                        'building'   => $building,
                        'room'       => $room,
                        'user_id'    => $id,
                        'created_at' => \Carbon\Carbon::now(),
                        'updated_at' => \Carbon\Carbon::now()
                    ]
                );

                $locationID = DB::table('locations')->where([['building', '=', $building],['room', '=', $room]])->get(['id']);
                $locationID = $locationID[0]->id;

                // $response["message"] = "Are the same size";

                for($x = 0; $x < $quantity; $x++) {



                        // The device does not exist, it will be created
                        DB::table('devices')->insert(
                            [
                                'name'               => $name,
                                'serial_number'      => "",
                                'brand'              => $brand,
                                'model'              => $model,
                                'added_to_warehouse' => \Carbon\Carbon::now(),
                                'location_id'        => $locationID,
                                'created_at'         => \Carbon\Carbon::now(),
                                'updated_at'         => \Carbon\Carbon::now()
                            ]
                        );

                        $lastDeviceAddedID = DB::table('devices')->where(
                            [
                                ['model', '=', $model]
                            ]
                            )->get(['id']);
                        $lastDeviceAddedID = $lastDeviceAddedID[0]->id;

                        DB::table('states')->insert(
                            [
                                'state'      => 'Available',
                                'device_id'  => $lastDeviceAddedID,
                                'created_at' => \Carbon\Carbon::now(),
                                'updated_at' => \Carbon\Carbon::now()
                            ]
                        );

                        $separatedTags = explode(",", $tags);

                        for($y = 0; $y < count($separatedTags); $y++){

                            // Search if the tag actually exists
                            // If it exists, just get the id to after use it in the relation
                            // If not exists already, the tag and device_tag instances are going to be created

                            // strtolower($str);

                            $tagExistance = DB::table('tags')->where('tag', '=', $separatedTags[$y])->get();

                            if(count($tagExistance) > 0){

                                // The tag already exists, let's get tag id and just create the relation

                                $tagID = DB::table('tags')->where('tag', '=', $separatedTags[$y])->get(['id']);
                                $tagID = $tagID[0]->id;

                                DB::table('device_tag')->insert(
                                    [
                                        'tag_id' => $tagID,
                                        'device_id' => $lastDeviceAddedID,
                                        'created_at' => \Carbon\Carbon::now(),
                                        'updated_at' => \Carbon\Carbon::now()
                                    ]
                                );

                            }else{
                                // The tag doesn't exists, let's create both
                                DB::table('tags')->insert(
                                    [
                                        //'tag' => $separatedTags[$y],
                                        'tag' => strtolower($separatedTags[$y]),
                                        'created_at' => \Carbon\Carbon::now(),
                                        'updated_at' => \Carbon\Carbon::now()
                                    ]
                                );

                                $lastTagAddedID = DB::table('tags')->orderBy('id', 'desc')->first();

                                DB::table('device_tag')->insert(
                                    [
                                        'tag_id' => $lastTagAddedID->id,
                                        'device_id' => $lastDeviceAddedID,
                                        'created_at' => \Carbon\Carbon::now(),
                                        'updated_at' => \Carbon\Carbon::now()
                                    ]
                                );
                            }
                        }


                }

                if($finalStatus == 2){
                    $response["status"] = 2;
                }else{
                    $response["status"] = 1;
                }

                if($finalMessage != ""){
                    $response["messsage"] = $finalMessage;
                }else{
                    $response["messsage"] = "Location, Devices, States, Tags and Device-Tag instances created.";
                }

                return json_encode($response);

            }else{

                // A location with that building and room already exists
                // $response["message"] = "The location already exists, nothing more done, just the verification.";

                $locationID = DB::table('locations')->where([['building', '=', $building],['room', '=', $room]])->get(['id']);
                $locationID = $locationID[0]->id;

                // Let's check if the quantity variable and the serial numbers array size are the same
                // $serialNumbersSeparated = explode(",", $serialNumbers);



                // $response["message"] = "Are the same size";

                for($x = 0; $x < $quantity; $x++) {

                        // The device does not exist, it will be created
                        DB::table('devices')->insert(
                            [
                                'name'               => $name,
                                'serial_number'      => "",
                                'brand'              => $brand,
                                'model'              => $model,
                                'added_to_warehouse' => \Carbon\Carbon::now(),
                                'location_id'        => $locationID,
                                'created_at'         => \Carbon\Carbon::now(),
                                'updated_at'         => \Carbon\Carbon::now()
                            ]
                        );

                        $lastDeviceAddedID = DB::table('devices')->where(
                            [
                                ['serial_number', '=', $serialNumbersSeparated[$x]],
                                ['model', '=', $model]
                            ]
                            )->get(['id']);
                        $lastDeviceAddedID = $lastDeviceAddedID[0]->id;

                        DB::table('states')->insert(
                            [
                                'state'      => 'Available',
                                'device_id'  => $lastDeviceAddedID,
                                'created_at' => \Carbon\Carbon::now(),
                                'updated_at' => \Carbon\Carbon::now()
                            ]
                        );

                        $separatedTags = explode(",", $tags);

                        for($y = 0; $y < count($separatedTags); $y++){

                            // Search if the tag actually exists
                            // If it exists, just get the id to after use it in the relation
                            // If not exists already, the tag and device_tag instances are going to be created

                            $tagExistance = DB::table('tags')->where('tag', '=', $separatedTags[$y])->get();

                            if(count($tagExistance) > 0){

                                // The tag already exists, let's get tag id and just create the relation

                                $tagID = DB::table('tags')->where('tag', '=', $separatedTags[$y])->get(['id']);
                                $tagID = $tagID[0]->id;

                                DB::table('device_tag')->insert(
                                    [
                                        'tag_id' => $tagID,
                                        'device_id' => $lastDeviceAddedID,
                                        'created_at' => \Carbon\Carbon::now(),
                                        'updated_at' => \Carbon\Carbon::now()
                                    ]
                                );

                            }else{
                                // The tag doesn't exists, let's create both
                                DB::table('tags')->insert(
                                    [
                                        //'tag' => $separatedTags[$y],
                                        'tag' => strtolower($separatedTags[$y]),
                                        'created_at' => \Carbon\Carbon::now(),
                                        'updated_at' => \Carbon\Carbon::now()
                                    ]
                                );

                                $lastTagAddedID = DB::table('tags')->orderBy('id', 'desc')->first();

                                DB::table('device_tag')->insert(
                                    [
                                        'tag_id' => $lastTagAddedID->id,
                                        'device_id' => $lastDeviceAddedID,
                                        'created_at' => \Carbon\Carbon::now(),
                                        'updated_at' => \Carbon\Carbon::now()
                                    ]
                                );
                            }
                        }


                }

                if($finalStatus == 2){
                    $response["status"] = 2;
                }else{
                    $response["status"] = 1;
                }

                if($finalMessage != ""){
                    $response["messsage"] = $finalMessage;
                }else{
                    $response["messsage"] = "Location, Devices, States, Tags and Device-Tag instances created.";
                }

                return json_encode($response);

            }
        }



        // $response["status"] = 1;
        // $response["message"] = "Location created in this case.";
        // $response["locations"] = $locationExistence;
        // return json_encode($response);
    }

    public function editDevice(Request $request)
    {
        $finalMessage = "vvv";
        $finalStatus = 0;

        $model = $request->input('model');
        $name = $request->input('name');
        $brand = $request->input('brand');
        $serialNumbers = $request->input('serial_numbers');
        $status = $request->input('status');
        $oldTags = $request->input('oldTags');
        $newTags = $request->input('newTags');
        $oldStatus = $request->input('oldStatus');
        $newStatus = $request->input('newStatus');

        // Get the ids and serials from that model
        $devices = DB::select("
        SELECT d.id, d.serial_number
        FROM devices d
        WHERE d.model = '$model';
        ");

        $id = Auth::user()->id;

        if (strcmp($oldTags,$newTags) != 0) {
            $separatedTags = explode(",", $newTags);

            // Delete the actual device-tag relations on all the id's on that model
            foreach($devices as $device) {
                $id = $device->id;
                DB::delete("
                DELETE FROM device_tag
                WHERE id = '$id';
                ");
            }

            for($y = 0; $y < count($separatedTags); $y++){

                // Search if the tag actually exists
                // If it exists, just get the id to after use it in the relation
                // If not exists already, the tag and device_tag instances are going to be created

                // strtolower($str);

                $tagExistance = DB::table('tags')->where('tag', '=', $separatedTags[$y])->get();

                if(count($tagExistance) > 0){

                    // The tag already exists, let's get tag id and just create the relation

                    $tagID = DB::table('tags')->where('tag', '=', $separatedTags[$y])->get(['id']);
                    $tagID = $tagID[0]->id;

                    foreach($devices as $device) {
                        DB::table('device_tag')->insert(
                            [
                                'tag_id' => $tagID,
                                'device_id' => $device->id,
                                'created_at' => \Carbon\Carbon::now(),
                                'updated_at' => \Carbon\Carbon::now()
                            ]
                        );
                    }
                }else{
                    // The tag doesn't exists, let's create both
                    DB::table('tags')->insert(
                        [
                            'tag' => $separatedTags[$y],
                            'created_at' => \Carbon\Carbon::now(),
                            'updated_at' => \Carbon\Carbon::now()
                        ]
                    );

                    $lastTagAddedID = DB::table('tags')->orderBy('id', 'desc')->first();

                    foreach($devices as $device) {
                        DB::table('device_tag')->insert(
                            [
                                'tag_id' => $lastTagAddedID->id,
                                'device_id' => $device->id,
                                'created_at' => \Carbon\Carbon::now(),
                                'updated_at' => \Carbon\Carbon::now()
                            ]
                        );
                    }
                }
                $finalStatus++;
                $finalMessage = "Tags been happen";
            }
        }

        for($i = 0; $i < count($serialNumbers); $i++) {
            if (strcmp($newStatus[$i], $oldStatus[$i]) != 0 ) {
                $sn = $serialNumbers[$i];
                $deviceIdArr = DB::select(
                    "SELECT d.id
                    FROM devices d
                    WHERE d.serial_number = '$sn'"
                );
                $device_id = $deviceIdArr[0]->id;
                DB::update('UPDATE states SET state = ? WHERE device_id = ?', [$newStatus[$i], $device_id]);
                $finalMessage = "update must be done";
            }
        }

        DB::update('UPDATE devices SET name = ?, brand = ? WHERE model = ?', [$name, $brand, $model]);

        $response["status"] = 1;
        $response["message"] = $finalMessage;
        return json_encode($response);
    }

    public function getDeviceNames()
    {
      $deviceNames = DB::select("
          SELECT d.name
          FROM devices d
          GROUP BY d.name;
      ");
      return array($deviceNames);
    }

    public function getDeviceBrands()
    {
      $deviceBrands = DB::select("
          SELECT d.brand
          FROM devices d
          GROUP BY d.brand;
      ");
      return array($deviceBrands);
    }

    public function getDeviceModels()
    {
      $deviceModels = DB::select("
          SELECT d.model
          FROM devices d
          GROUP BY d.model;
      ");
      return array($deviceModels);
    }

    public function searchDevice(Request $request)
    {
      $word = $request["word"];
      $tags = $request["tags"];
      $tagSize = (int)$request["tagsQuantity"];
      if ($tagSize == 0)
      {
        $tags = array();
      }
      $searchTag = $this->getQueryForTags($tags);
      $queryForDevices = $this->getQueryForDevices($tagSize);

      $devices = DB::select("
        WITH filtered_tags AS (
          SELECT t.id
          FROM tags t
          WHERE ({$searchTag})
        ),
        match_tagged_devices AS (
          SELECT DISTINCT dt.device_id
          FROM device_tag dt
          JOIN filtered_tags ft ON dt.tag_id = ft.id
        )

        SELECT COUNT(d.id) as quantity, d.name, d.brand, d.model
        FROM devices d
        JOIN match_tagged_devices mt ON d.id = mt.device_id
        JOIN states s ON d.id = s.device_id
        WHERE s.state = 'Available' AND
              (d.name LIKE '%{$word}%' OR d.brand LIKE '%{$word}%' OR d.model LIKE '%{$word}%' )
              {$queryForDevices}
        GROUP BY d.name, d.brand, d.model
      ");

      return $this->viewInventory($devices);
    }

    private function getQueryForTags(array $tags)
    {

      $pattern = '/\'+(\s+)/i';
      $substitute = '${0} OR ';
      $query = "TRUE";

      if (count($tags) > 0)
      {
        $query = "";
        foreach($tags as $tag)
        {
          $query = "{$query} t.tag = '{$tag}'";
        }
        $query = preg_replace($pattern, $substitute, $query);
      }

      return $query;
    }

    private function getQueryForDevices($tagSize)
    {
      $query = "";

      if ($tagSize > 0)
      {
        $query = " AND {$tagSize} = (SELECT COUNT(dt.device_id)
                                    FROM device_tag dt
                                    JOIN filtered_tags ft ON dt.tag_id = ft.id
                                    WHERE dt.device_id = d.id)";
      }

      return $query;
    }

    private function viewInventory($devices)
    {
      $quantity = count($devices);
      if (Auth::check())
      {
        $viewFile = 'inventory';
      }
      else
      {
        $viewFile = 'inventory-guest';
      }
      return view($viewFile)->with('devices', $devices)
                                    ->with('quantity', $quantity);
    }
}
