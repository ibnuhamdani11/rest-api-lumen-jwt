<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request; 

class QuoteController extends Controller
{
    public function read(Request $request)
    {
        $client  = new \GuzzleHttp\Client();
        // $res = $client->get('https://api.chucknorris.io/jokes/random');
        $this->validate($request, [
            'url' => 'required',
        ]);
        
        $res = $client->get($request->url);
        // $resStatusCode = $res->getStatusCode();
        // $resHeader = $res->getHeader('content-type')[0]; 
        return  $res->getBody();
    }
} 