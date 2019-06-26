<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use \RecursiveIteratorIterator;
use \RecursiveArrayIterator;
use App\Carrinho;
use App\CarrinhoItem;
use DB;

#use App\DIContainer;

class FreteController extends Controller
{
    public function frete(Request $request, Response $response){
    
    
      #dd($request->pacote);
      $basket =  json_decode($request->getContent());
    
      #dd($basket);

    //Variaveis
    $origem  = $basket->pacote->origem;
    $destino = $basket->pacote->destino;
    $carrinho_itens = $basket->pacote->produto;
    $carrinho = $basket->pacote->carrinho;
    
    //Ultimo ID Inserido na Carrinho
    $last_insert_id = null;

    $error_msg = array();
    
    //Loop de Produtos
      foreach ($carrinho_itens as $prod) 
      {
               
        //ID Produto
        if(!isset($prod->id)){
          array_push($error_msg, 'ID do produto não pode ser nulo');
        }

        //Nome Produto
        if(!isset($prod->nome)){
          array_push($error_msg,'Nome do produto não pode ser nulo');
        }

        //SKU Produto
        if(!isset($prod->sku)){
          array_push($error_msg,'SKU do produto não pode ser nulo');
        }

        //Peso Produto
        if(!isset($prod->peso)){
          array_push($error_msg,'Peso do produto não pode ser nulo');
        }

        //Altura Produto
        if(!isset($prod->altura)){
          array_push($error_msg,'Altura do produto não pode ser nulo');
        }
        
        //Largura Produto
        if(!isset($prod->largura)){
          array_push($error_msg,'Largura do produto não pode ser nulo');
        }
        
        //Comprimento Produto
        if(!isset($prod->comprimento)){
          array_push($error_msg,'Comprimento do produto não pode ser nulo');
        }
        
        //Valor Produto

        if(!isset($prod->valor)){
          array_push($error_msg,'Valor do produto não pode ser nulo');
        }

      } //fim loop produto
           
      //carrinho-info

      //Carrinho id
      if(!isset($carrinho->id)){
        array_push($error_msg,'ID do Carrinho não pode ser nulo');
      }

      //Carrinho valor
      if(!isset($carrinho->valor)){
        array_push($error_msg,'Valor do Carrinho não pode ser nulo');
      } // fim carrinho-info
      
      //Carrinho-Usuario
     
      //Usuario nome   
        if(!isset($carrinho->usuario->nome)){
          array_push($error_msg, 'Nome do usuário não pode ser nulo');
        }

        //Usuario email   
        if(!isset($carrinho->usuario->email)){
          array_push($error_msg, 'E-mail do usuário não pode ser nulo');
        }

         //Usuario telefone   
        if(!isset($carrinho->usuario->telefone)){
            array_push($error_msg, 'Telefone do usuario não pode ser nulo');
        }
       
        //Origem
        
          $origem->logradouro; 
          $origem->numero;
          $origem->complemento;
          $origem->bairro;
          $origem->cidade;
          $origem->estado;
          $origem->cep;
          $origem->referencia;

       
        //Destino
        
        if(!isset($destino->logradouro)){
          array_push($error_msg, 'Logradouro de destino não pode ser nulo');
        }
        
        if(!isset($destino->numero)){
          array_push($error_msg, 'Número do destino não pode ser nulo');
        }

        if(!isset($destino->numero)){
          array_push($error_msg, 'Número do destino não pode ser nulo');
        }
        
       /*  if(!isset($destino->complemento)){
          $destino->complemento;
        }else{
          array_push($error_msg, 'Número do destino não pode ser nulo');
        } */

        $destino->complemento;
        
        if(!isset($destino->numero)){
          array_push($error_msg, 'Número do destino não pode ser nulo');
        }
         
        if(!isset($destino->bairro)){
          array_push($error_msg, 'Bairro do destino não pode ser nulo');
        }
        
        if(!isset($destino->cidade)){
          array_push($error_msg, 'Cidade do destino não pode ser nulo');
        }

        if(!isset($destino->estado)){
          array_push($error_msg, 'Estado do destino não pode ser nulo');
        }

        if(!isset($destino->cep)){
          array_push($error_msg, 'Cep do destino não pode ser nulo');
        }
      
          $destino->referencia;

   

  //Testa se tem erros
    if(!empty($error_msg)){
      return response()->json($error_msg, 400); 
    }else {

   //Grava na Tabela Carriho
    $gravaCarrinho = Carrinho::Create([
        'id_trayCorp'    => $carrinho->id,
        'nome'           => $carrinho->usuario->nome,
        'email'          => $carrinho->usuario->email,
        'telefone'       => $carrinho->usuario->telefone,
        'valor'          => $carrinho->valor,
        'logradouroOrig' => $origem->logradouro,
        'numeroOrig'     => $origem->numero,
        'complementoOrig'=> $origem->complemento,
        'bairroOrig'     => $origem->bairro,
        'cidadeOrig'     => $origem->cidade,
        'estadoOrig'     => $origem->estado,
        'cepOrig'        => $origem->cep,
        'referenciaOrig' => $origem->referencia,
        'logradouroDest' => $destino->logradouro,
        'numeroDest'     => $destino->numero,
        'complementoDest'=> $destino->complemento,
        'bairroDest'     => $destino->bairro,
        'cidadeDest'     => $destino->cidade,
        'estadoDest'     => $destino->estado,
        'cepDest'        => $destino->cep,
        'referenciaDest' => $destino->referencia,
        'dt_processado'  => Now(),
        'situacao'       => 'P' //Pendente
      ]);

      $last_insert_id = $gravaCarrinho->id;

      if($last_insert_id > 0){
      
      //Grava na Tabela Carriho       
      
      foreach ($carrinho_itens as $linha) {
      
        $grava_item = CarrinhoItem::Create([
          'id_carrinho' => $last_insert_id,
          'id_produto'  => $linha->id,
          'nome'        => $linha->nome,
          'sku'         => $linha->sku,
          'peso'        => $linha->peso,
          'altura'      => $linha->altura,
          'largura'     => $linha->largura,
          'comprimento' => $linha->comprimento,
          'valor'       => $linha->valor
          
        ]);
      }

        $frete = null;

        $json_proc = DB::SELECT("SET NOCOUNT ON EXEC spcRetornaVlFrete ?,?,?,?", 
                          array($last_insert_id, $frete, null, null)); 

        if($json_proc){
        
        //Retorno da Procedure
        $frete = $json_proc[0]->jsonFrete;
        $retorno = json_decode($frete);

        return response()->json($retorno, 200);
        $error_msg = null;
  
        }else {
          return response()->json(['Não foi possivel retornar um frete'], 400);
        }
    }  
      

      /* return response()->json(['Gravado com sucesso'], 200);
      $error_msg = null; */
    }
  }

}
