<?php

use App\Models\GudangPJModel;

function getIdGudangByIdUser($id_user)
{
    $modelGudangPJ = new GudangPJModel();
    return $modelGudangPJ->getGudangByPJ($id_user);
}
