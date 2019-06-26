<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cartao extends Model
{
    ## Alterando 
    protected $table = "CarrinhoCard";

    public $timestamps = false;

    protected $fillable  = [
        'id_carrinho',
        'cardFlag',
        'cardName',
        'cardNumber',
        'cardExpYear',
        'cardExpMonth',
        'cardSegCod',
        'situacao'
    ];
}
