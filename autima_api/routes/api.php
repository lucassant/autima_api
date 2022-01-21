<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/bairros/{dataSinc}', 'BairroController@index');

Route::post('/orcamento', 'OrcamentoController@store');
Route::post('/orcamento-lancto', 'OrcamentoLanctoController@store');
Route::post('/cliente', 'ClienteController@store');

Route::get('/senha/{codigo}/{senha}', 'ConfiguracoesController@verificaSenhaVendedor');
Route::get('/decodepass/{codigo}', 'ConfiguracoesController@decodePassword');

Route::get('/cidades/{dataSinc}', 'CidadeController@index');
Route::get('/clientes/{codVendedor}/{dataSinc}', 'ClienteController@index');
Route::get('/configs', 'ConfiguracoesController@index');
Route::get('/embalagens/{dataSinc}', 'EmbalagemController@index');
Route::get('/estados/{dataSinc}', 'EstadoController@index');
Route::get('/produtos/{dataSinc}', 'ProdutoController@index');

//Estoques
Route::get('/estoque/{idProduto}', 'ProdutoController@getEstoque');
Route::get('/estoques/{dataSinc}', 'ProdutoController@allEstoques');

Route::get('/produtosBarra', 'ProdutoController@produtoBarra');

Route::get('/sincronizar/{codVendedor}/{dataSinc}', 'SincronizarController@retornaDados');

Route::get('/regrasPrecos', 'RegraPrecoController@index');
Route::get('/tabelasPrecos', 'TabelaPrecoController@index');

//Histórico
Route::get('/historico-todos/{dataIni}/{dataFim}/{codVendedor}/{codCliente}/{tipo}', 'HistoricoController@retornaTodos');
Route::get('/lanctos/{numControle}', 'HistoricoController@retornaLanctos');

//Relatórios
Route::get('historico-verba/{dataIni}/{dataFim}/{codVendedor}', 'RelatoriosController@historicoVerba');
Route::get('ranking-cliente/{dataIni}/{dataFim}/{codVendedor}', 'RelatoriosController@rankingCliente');
Route::get('ranking-produto/{dataIni}/{dataFim}/{codVendedor}', 'RelatoriosController@rankingProduto');

Route::get('inadimplencias/{codVendedor}', 'InadimplenciasController@getInadimplencias');

Route::get('faturamento-mensal', 'RelatoriosController@faturadosMensal');
