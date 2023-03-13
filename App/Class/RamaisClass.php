<?php

class Ramal {

    public function __construct() {
        return $this->ramais();
    }

    function ramais() {
        header("Content-type: application/json; charset=utf-8");
        $ramais = file('lib/ramais');
        $filas = file('lib/filas');
        $status_ramais = array();
        $agentes_ramais = array();
        $fila = array();
        foreach ($filas as $linhas) {
            if (strstr($linhas, 'SIP/')) {
                $dadosFila = explode(' ', trim($linhas));
                $name = $dadosFila[0];
                $name = explode('SIP/', trim($name));
                $name = $name[1];
                if (strstr($linhas, 'no calls')) {
                    $chamadas = 0;
                } else {
                    $chamadas = $dadosFila[8];
                }
                if (strstr($linhas, '(Ring)')) {
                    $linha = explode(' ', trim($linhas));
                    list($tech, $ramal) = explode('/', $linha[0]);
                    $status_ramais[$ramal] = array('status' => 'chamando');
                    $agentes_ramais[$ramal] = end($linha);
                }
                if (strstr($linhas, '(In use)')) {
                    $linha = explode(' ', trim($linhas));
                    list($tech, $ramal) = explode('/', $linha[0]);
                    $status_ramais[$ramal] = array('status' => 'ocupado');
                    $agentes_ramais[$ramal] = end($linha);
                }
                if (strstr($linhas, '(Not in use)') && !strstr($linhas, '(paused)')) {
                    $linha = explode(' ', trim($linhas));
                    list($tech, $ramal)  = explode('/', $linha[0]);
                    $status_ramais[$ramal] = array('status' => 'disponivel');
                    $agentes_ramais[$ramal] = end($linha);
                } else if (strstr($linhas, '(paused)')) {
                    $linha = explode(' ', trim($linhas));
                    list($tech, $ramal)  = explode('/', $linha[0]);
                    $status_ramais[$ramal] = array('status' => 'pausado');
                    $agentes_ramais[$ramal] = end($linha);
                }
                if (strstr($linhas, '(Unavailable)')) {
                    $linha = explode(' ', trim($linhas));
                    list($tech, $ramal)  = explode('/', $linha[0]);
                    $status_ramais[$ramal] = array('status' => 'indisponivel');
                    $agentes_ramais[$ramal] = end($linha);
                }
                $fila[$name] = array(
                    'nomeRamal' => $name,
                    'chamadas' => $chamadas,
                    'agente' =>  $agentes_ramais[$name],
                    'status' => $status_ramais[$name]['status']
                );
            }
        }

        $info_ramais = array();
        foreach ($ramais as $linhas) {
            $linha = array_filter(explode(' ', $linhas), function ($k) {
                if ($k == 0) {
                    return TRUE;
                }
                return $k;
            });
            $arr = array_values($linha);
            if (trim($arr[1]) == '(Unspecified)' and trim($arr[5]) == 'UNKNOWN') {
                list($name, $username) = explode('/', $arr[0]);
                // caso o ramal não esteja em nenhuma fila seu status será offline
                if (isset($status_ramais[$name])) {
                    $status = $status_ramais[$name]['status'];
                } else {
                    $status = 'indisponivel';
                }
                // caso o ramal não esteja em nenhuma fila não terá agente
                if (isset($agentes_ramais[$name])) {
                    $agente = $agentes_ramais[$name];
                } else {
                    $agente = '';
                }
                $info_ramais[$name] = array(
                    'nome' => $name,
                    'ramal' => $username,
                    'online' => 0,
                    'status' => $status,
                    'agente' => $agente,
                    'IP' => trim($arr[1])
                );
            }
            if (trim($arr[5]) == "OK") {
                list($name, $username) = explode('/', $arr[0]);
                if (isset($status_ramais[$name])) {
                    $status = $status_ramais[$name]['status'];
                } else {
                    $status = 'indisponivel';
                }
                if (isset($agentes_ramais[$name])) {
                    $agente = $agentes_ramais[$name];
                } else {
                    $agente = '';
                }
                $info_ramais[$name] = array(
                    'nome' => $name,
                    'ramal' => $username,
                    'online' => 1,
                    'status' => $status,
                    'agente' => $agente,
                    'IP' => trim($arr[1])
                );
            }
        }
        $this->salvaRamais($info_ramais);
        $this->salvaFila($fila);
        $totalizador = $this->totalizador();
        $fila = $this->novaFila();
        return json_encode(['ramais' => $info_ramais, 'fila' => $fila, 'totalizador' => $totalizador]);
    }

    function connect() {
        $con = mysqli_connect('localhost', 'root', 'root', 'callcenter');
        
        if ($con->connect_error) {
            die("Connection failed: " . $con->connect_error);
        }

        mysqli_select_db($con, 'callcenter');

        return $con;
    }


    private function salvaRamais($ramais) {
        
        $con = $this->connect();
        foreach($ramais as $ramal) {
            $query = "SELECT * FROM ramais WHERE ramal = " . $ramal['ramal'];

            $result = mysqli_query($con ,$query);
            $result = mysqli_fetch_object($result);

            $ramalnome = $ramal['ramal'];
            $nome = $ramal['nome'];
            $ip = $ramal['IP'] != null ? $ramal['IP'] : '(Unspecified)';
            $online = $ramal['online'];

            if($result == null) {
                $sql = "INSERT INTO ramais (ramal, nome, IP, online)
                VALUES ('$ramalnome', '$nome', '$ip', '$online')";
                $con->query($sql);
            } else {
                $result = (array) $result;
                $ramal = (array) $ramal;
                $arrayDiff = array_diff($result, $ramal);
                if(count($arrayDiff) > 0){
                    $sql = "UPDATE ramais SET ramal='$ramalnome', IP='$ip', online='$online' WHERE nome='$nome'";
                    $con->query($sql);  
                }
            }
        }   
        $con->close();
    }

    private function salvaFila($filas) {

        $con = $this->connect();

        foreach ($filas as $fila) {
            mysqli_select_db($con, 'callcenter');
            $query = "SELECT * FROM fila WHERE nomeRamal = " . $fila['nomeRamal'];

            $result = mysqli_query($con, $query);
            $result = mysqli_fetch_object($result);
            $nomeRamal = $fila['nomeRamal'];
            $agente = $fila['agente'];
            $status = $fila['status'];
            $chamadas = $fila['chamadas'];
            if ($result == null) {
                $sql = "INSERT INTO fila (nomeRamal, agente, status, chamadas)
                VALUES ('$nomeRamal', '$agente', '$status', '$chamadas')";
                $con->query($sql);
            } else {
                $result = (array) $result;
                $id = $result['id'];
                unset($result['id']);
                $fila = (array) $fila;
                date_default_timezone_set('America/Sao_Paulo');
                $dataAtual = date('y-m-d h:m:s');
                $arrayDiff = array_diff($result, $fila);
                if (count($arrayDiff) > 0) {
                    $sql = "UPDATE fila SET nomeRamal='$nomeRamal', agente='$agente',  status='$status', chamadas='$chamadas'";
                    if(isset($arrayDiff['status'])) {
                        $sql .= " ,status_updated_at='$dataAtual'";
                    }
                    $sql .=  " WHERE id='$id'";
                    $con->query($sql);
                }
            }
        }
        $con->close();
    }

    function novosRamais($status = null, $search = null) {

        $con = $this->connect();

        $query = "SELECT * FROM ramais left join fila on ramais.nome =  fila.nomeRamal";

        if ($status != null && $search != null) {
            $query .= " WHERE status = '$status' AND (nome LIKE '%$search%' OR agente LIKE '%$search%')";
        } else if ($status != null) {
            $query .= " WHERE status = '$status'";
        } else if ($search != null) {
            $query .= " WHERE nome LIKE '%$search%' OR agente LIKE '%$search%'";
        }

        $query .= " ORDER BY nome";

        $result = mysqli_query($con, $query);

        while ($r = mysqli_fetch_array($result)) {
            //caso não esteja em nenhuma fila, agente é igual a '' e status = indisponivel
            if(!isset($r['status'])) {
                $r['status'] = 'indisponivel';
            }
            if(!isset($r['agente'])) {
                $r['agente'] = '';
            }

            $res[] = $r;
        } 

        
        $fila = $this->novaFila($status, $search);
        $totalizador = $this->totalizador();
        return ([$res, $fila, $totalizador]);
    }

    function novaFila($status = null, $search = null) {

        $con = $this->connect();

        $query = "SELECT * FROM fila";

        if ($status != null && $search != null) {
            $query .= " WHERE status = '$status' AND (nomeRamal LIKE '%$search%' OR agente LIKE '%$search%')";
        } else if ($status != null) {
            $query .= " WHERE status = '$status'";
        } else if ($search != null) {
            $query .= " WHERE nomeRamal LIKE '%$search%' OR agente LIKE '%$search%'";
        }

        $query .= " ORDER BY nomeRamal";

        $result = mysqli_query($con, $query);

        while ($r = mysqli_fetch_array($result)) {
            $res[] = $r;
        } 

        return ($res);
    }

    function totalizador() {

        $con = $this->connect();

        $query = "SELECT * FROM ramais inner join fila on ramais.nome =  fila.nomeRamal order by nome";

        $result = mysqli_query($con, $query);

        while ($r = mysqli_fetch_array($result)) {
            $res[] = $r;
        } 

        $totalRamais = count($res);
        $totalRamaisDisponiveis = 0;
        $totalRamaisIndisponiveis = 0;
        $totalRamaisPausados = 0;
        $totalRamaisOcupados = 0;
        $totalRamaisChamando = 0;
        foreach($res as $campo) {
            if($campo['status'] == 'disponivel'){
                $totalRamaisDisponiveis++;
            } else if ($campo['status'] == 'ocupado') {
                $totalRamaisOcupados++;
            } else if ($campo['status'] == 'indisponivel') {
                $totalRamaisIndisponiveis++;
            } else if ($campo['status'] == 'pausado') {
                $totalRamaisPausados++;
            } else if ($campo['status'] == 'chamando') {
                $totalRamaisChamando++;
            }
        }

        return ['totalRamais' => $totalRamais, 'totalRamaisDisponiveis' => $totalRamaisDisponiveis,  'totalRamaisIndisponiveis' => $totalRamaisIndisponiveis, 'totalRamaisPausados' => $totalRamaisPausados, 'totalRamaisOcupados' => $totalRamaisOcupados, 'totalRamaisChamando' => $totalRamaisChamando];
    }
}
