<?php

// Classe dashboard
class Dashboard {
    public $data_inicio;
    public $data_fim;
    public $numeroVendas;
    public $totalVendas;

    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
        return $this;
    }
}

// Conexão com o banco de dados
class Conexao {
    private $host = 'localhost';
    private $dbname = 'dashboard';
    private $user = 'root';
    private $pass = '';

    public function conectar() {
        try {
            $conexao = new PDO( "mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->pass );

            $conexao->exec('set names utf8');

            return $conexao;

        } catch (PDOException $e) {
            echo '<p>'.$e->getMessage().'</p>';
        }
    }
}

// Classe model
class Bd {
    private $conexao;
    private $dashboard;

    public function __construct(Conexao $conexao, Dashboard $dashboard) {
        $this->conexao = $conexao->conectar();
        $this->dashboard = $dashboard;
    }

    public function getNumeroVendas() {
        $query = "SELECT count(*) as numero_vendas FROM tb_vendas WHERE data_venda between :data_inicio and :data_fim ";

        $stmt = $this->conexao->prepare($query);

        $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
        $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->numero_vendas;
    }

    public function getTotalVendas() {
        $query = "SELECT SUM(total) as total_vendas FROM tb_vendas WHERE data_venda between :data_inicio and :data_fim ";

        $stmt = $this->conexao->prepare($query);

        $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
        $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->total_vendas;
    }



}

    // lógica do script
    $dashboard = new Dashboard();

    $conexao = new Conexao();

    $competencia = explode('-', $_GET['competencia']);
    $ano = $competencia[0];
    $mes = $competencia[1];

    $dias_do_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);

    $dashboard->__set('data_inicio', $ano.'-'.$mes.'-01');
    $dashboard->__set('data_fim', $ano.'-'.$mes.'-'.$dias_do_mes);

    $bd = new Bd($conexao, $dashboard);

    $dashboard->__set('numeroVendas', $bd->getNumeroVendas());
    $dashboard->__set('totalVendas', $bd->getTotalVendas());

    echo json_encode($dashboard);


?>
