<?php

namespace App\Services;

use Illuminate\Support\Str;
use DOMDocument;

class SoapService
{
    public static function search($term)
    {
        $xml = simplexml_load_file(resource_path('xml/pokemons.xml'));
        $expression = '/pokedex/pokemon[species="'.Str::upper($term).'"]';

        $result = $xml->xpath($expression);

        if($result){
            $doc = new DOMDocument();
            $doc->loadXML($result[0]->saveXML());
            $doc->save(resource_path('xml/pokemon.xml'));
            return $result[0]->saveXML();
        }

        return false;
    }
}
