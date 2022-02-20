<?php

namespace App\Http\Controllers;

use App\Models\Anapioficeandfire;
use Illuminate\Http\Request;

class CharacterController extends Controller
{
    public function getCharacters(Request $request)
    {
        try {

            $ice_and_fire = new Anapioficeandfire();

            if ($request->query("name")) {
                $name = $request->query("name");
                $characters = $ice_and_fire->getCharacterByName($name);
            } else if ($request->query("culture")) {

                $culture = $request->query("culture");
                $characters = $ice_and_fire->getCharactersByCulture($culture);
            } else if ($request->query("gender")) {

                $gender = $request->query("gender");
                $characters = $ice_and_fire->getCharactersByGender($gender);
            } else if ($request->query("age")) {

                $age = $request->query("age");
                //  $response = $ice_and_fire->getcha

            } else {

                $characters = $ice_and_fire->getAllCharacters();
            }


            // CHECK FOR ORDER ( Descending | Ascending order )

            if ($request->query("order") == 'DESC') {
                //  Reverse array to get Descending order
                $order = "DESC";
                $characters = array_reverse($characters);
            } else {
                //  Characters already coming in Ascending order
                $order = "ASC";
            }



            $data = [
                "meta" => [
                    "total_characters" => count($characters),
                    "total_age" =>  NULL
                ],
                "data" => $characters
            ];


            return response()->json([
                'code' => '200',
                'message' => "Character(s) available",
                'data' => $data
            ], 200);
        } catch (\Exception  $e) {
            return response()->json([
                'code' => '500',
                'message' => $e->getMessage(),
                'data' => NULL
            ], 500);
        }
    }

    public function getSingleCharacter($characer_id)
    {
        try {
            //code...


            $ice_and_fire = new Anapioficeandfire();
            $character = $ice_and_fire->getCharacterByID($characer_id);

            $data = [
                "meta" => [
                    "total_characters" => count($character),
                    "total_age" =>  NULL
                ],
                "data" => $character
            ];


            return response()->json([
                'code' => '200',
                'message' => "Character(s) available",
                'data' => $data
            ], 200);
        } catch (\Exception  $e) {
            return response()->json([
                'code' => '500',
                'message' => $e->getMessage(),
                'data' => NULL
            ], 500);
        }
    }
}