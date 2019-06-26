<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Carrinho extends Model
{
    protected $table = "Carrinho";

    protected $fillable = [
        'id_trayCorp', 
        'nome', 
        'email', 
        'telefone', 
        'valor',
        'logradouroOrig',
        'numeroOrig',
        'complementoOrig',
        'bairroOrig',
        'cidadeOrig',
        'estadoOrig',
        'cepOrig',
        'referenciaOrig',
        'logradouroDest',
        'numeroDest',
        'complementoDest',
        'bairroDest',
        'cidadeDest',
        'estadoDest',
        'cepDest',
        'referenciaDest',
        'dt_processado',
        'situacao',
    ];
    public $timestamps = false;
}
