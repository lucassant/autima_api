<?php

namespace App\Http\Controllers;

use DB;
use App\Cliente;
use App\RepSql;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index($codVendedor, $dataSinc)
    {
        $lista = Cliente::where('flag_ativo', '1')
            ->join('associa_cliente_vendedor', 'participante.codigo', 'associa_cliente_vendedor.codigo_cliente')
            ->where('e_cliente', '1')
            ->where('data_alteracao', '>=', "'" . $dataSinc . "'")
            ->orderBy('codigo')
            ->get();
        $data = ['clientes' => $lista];

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $cliente = json_decode($request->cliente);

        //Verifica se o cliente já existe
        $existe = DB::select("SELECT COUNT(codigo) FROM participante WHERE e_cliente = 1 AND cpf_cnpj = '" . $cliente->cpf_cnpj . "'");

        if ($existe[0]->count == 0) {
            //Obtem o código antes de inserir
            $numControle = DB::select("select nextval('public.participante_codigo') AS codigo");
            $numControle = $numControle[0]->codigo;

            $data = [
                'codigo' => $numControle,
                'razao_social' => $cliente->razao_social,
                'nome_fantasia' => $cliente->nome_fantasia,
                'endereco' => $cliente->endereco,
                'codigo_bairro' => $cliente->codigo_bairro,
                'codigo_cidade' => $cliente->codigo_cidade,
                'complemento_endereco' => $cliente->complemento,
                'telefone' => $cliente->telefone,
                'email' => $cliente->email,
                'cpf_cnpj' => $cliente->cpf_cnpj,
                'e_cliente' => 1,
                'numero' => $cliente->numero,
                'cep' => $cliente->cep
            ];

            if (Cliente::insert($data)) {

                //Grava na rep_sql o insert do cliente
                $rep_sql = [
                    'text_sql' => 'INSERT INTO participante(
                            codigo, razao_social, nome_fantasia, endereco, codigo_bairro, codigo_cidade, complemento_endereco, telefone, email, cpf_cnpj, e_cliente, numero, cep
                            ) VALUES(
                                ' . $numControle . ',                                 
                                \'' . $cliente->razao_social . '\', 
                                \'' . $cliente->nome_fantasia . '\', 
                                \'' . $cliente->endereco . '\', 
                                ' . $cliente->codigo_bairro . ', 
                                ' . $cliente->codigo_cidade . ', 
                                \'' . $cliente->complemento . '\', 
                                \'' . $cliente->telefone . '\', 
                                \'' . $cliente->email . '\', 
                                \'' . $cliente->cpf_cnpj . '\', 
                                1, 
                                \'' . $cliente->numero . '\', 
                                \'' . $cliente->cep . '\'
                            )'
                ];
                RepSql::insert($rep_sql);
                //Fim do insert na rep_sql

                $associa = [
                    'codigo_cliente' => $numControle,
                    'codigo_vendedor' => $request->vendedor
                ];

                if (DB::table('associa_cliente_vendedor')->insert($associa)) {

                    //Grava a associação no rep_sql
                    $rep_sql = [
                        'text_sql' => 'INSERT INTO associa_cliente_vendedor(codigo_cliente, codigo_vendedor) VALUES(' . $numControle . ', ' . $request->vendedor . ')'
                    ];
                    RepSql::insert($rep_sql);
                    //Fim do insert na rep_sql

                    return json_encode((object) array('codigo' => $numControle, 'response' => 1));
                } else {
                    return json_encode((object) array('codigo' => $numControle, 'response' => 2));
                }
            } else {
                return json_encode((object) array('codigo' => 0, 'response' => 0));
            }
        } else {
            //Caso já exista ele retorna o código
            $_codigo = DB::select("SELECT codigo FROM participante WHERE e_cliente = 1 AND cpf_cnpj = '" . $cliente->cpf_cnpj . "'");
            $_codigo = $_codigo[0]->codigo;

            return json_encode((object) array('codigo' => $_codigo, 'response' => 3));
        }
    }
}
