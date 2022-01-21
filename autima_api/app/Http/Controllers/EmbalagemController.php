<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Embalagem;
use Illuminate\Http\Request;

class EmbalagemController extends Controller
{
    private $embalagens;

    public function __construct(Embalagem $embalagem){
        $this->embalagens = $embalagem;
    }

    public function index($dataSinc)
    {
        $lista = Embalagem::where('dh_inclusao', '>=', $dataSinc)->where('ativo', 1)->get();
        $data = ['embalagens' => $lista];

        return response()->json($data);

    }
}
