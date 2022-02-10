<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;

class PokemonController extends Controller
{
    private $filter, $data, $code;

    public function pokemon(){
        $client = new Client();
        $response = $client->request('GET', 'https://pokeapi.co/api/v2/pokemon?limit=100&offset=200');
        $data = json_decode($response->getBody());

        return response()->json($data, 200);
    }

    public function pokemonByFilter(Request $request){
        if (count($request->all()) === 1){
            if ($this->validateParams($request)){
                $this->executeApi($request);
    
                return response()->json($this->data,  $this->code);
            }
        }
   
        return response()->json("Parametro no permitido", 406);
    }

    private function validateParams(Request $request){
        switch (true) {
            case Arr::exists($request, 'pokemon'):
                $this->filter = "pokemon";
                return true;
                break;
            case Arr::exists($request, 'pokemon-species'):
                $this->filter = "pokemon-species";
                return true;
                break;
            case Arr::exists($request, 'pokemon-color'):
                $this->filter = "pokemon-color";
                return true;
                break;
            case Arr::exists($request, 'pokemon-form'):
                $this->filter = "pokemon-form";
                return true;
                break;
            case Arr::exists($request, 'pokemon-habitat'):
                $this->filter = "pokemon-habitat";
                return true;
                break;
            case Arr::exists($request, 'pokemon-shape'):
                $this->filter = "pokemon-shape";
                return true;
                break;
            default:
                return false;
                break;
        }
    }

    private function executeApi(Request $request){
        $client = new Client();
        $value = $request->{$this->filter};

        try {
            $response = $client->request('GET', "https://pokeapi.co/api/v2/{$this->filter}/{$value}");
            $this->data = json_decode($response->getBody());
            $this->code = 200;
        } catch (\Throwable $th) {
            $this->data = [
                'message' => 'No fue posible encontrar pokemon',
                'status' => 'error'
            ];

            $this->code = 404;
        }
    }
    
}
