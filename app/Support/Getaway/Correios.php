<?php

namespace App\Support\Getaway;

use App\Models\Track;
use DateTime;

class Correios {

    private function Request($code)
    {

        $ch = curl_init('https://proxyapp.correios.com.br/v1/sro-rastro/'.$code);

        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => true,
        ));
        $output = curl_exec($ch);

        return json_decode($output);
    }

    public function getDataWebServer($tracking_number)
    {

        $return = $this->Request($tracking_number);
        $status = Track::getStatusTypes();

        $data['tracking_number'] = $tracking_number;

        if ( isset($return->objetos[0]->eventos) ) {
            $data['history'] = json_encode($return->objetos[0]->eventos);

            $descricao = $return->objetos[0]->eventos[0]->descricao;
            $data_hora = new DateTime($return->objetos[0]->eventos[0]->dtHrCriado);

            $data['last_update'] = $data_hora->format('d/m/Y - h:m') .' - ' .$descricao;

            //when statusType is not defined in Track::getStatusTypes the field $descricao is set
            $data['status'] = ( isset($status[$descricao]) ) ? $status[$descricao] : $descricao;

        } else {
            $data['last_update'] = 'Aguardando Postagem';
            $data['status'] = 'Aguardando Postagem';

        }

        return $data;

    }
}
