<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarrinhoItem extends Model
{
    protected $fillable = [
        'id_carrinho',
        'id_produto',
        'nome',
        'sku',
        'peso',
        'altura',
        'largura',
        'comprimento',
        'valor'
    ];
    
    protected $table = 'carrinhoItem';
    public $timestamps = false;

}
