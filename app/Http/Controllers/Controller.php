<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class Controller extends BaseController
{
    public function loadUsers()
    {
        $limit = 5000;

        $response = Http::acceptJson()->get("https://randomuser.me/api/", [
            "results" => $limit,
        ])->json();

        if(isset($response["error"])){
            return response()->json([
                "success"=>false,
                "message"=>$response["error"]
            ]);
        }

        $usersData = array_map(static fn($result) => [
            "first_name" => $result["name"]["first"],
            "last_name" => $result["name"]["last"],
            "email" => $result["email"],
            "age" => (int)$result["dob"]["age"],
        ], $response["results"]);
        $dataCounts = count($usersData);
        $updateTime = date('Y-m-d H:i:s');

        DB::beginTransaction();
        User::upsert($usersData,[ 'first_name','last_name',],['email','age',"update_time"=>$updateTime]);
        DB::commit();

        $updated = User::where('update_time', '=', $updateTime)->count();
        $count = User::count();
        $created = $dataCounts - $updated;

        return response()->json([
            "success"=>true,
            "data"=>compact("updated","count","created")
        ]);

    }
}
