# transferrencia bancaria de js para php
````
<?php
class Conta{
    private $numeroConta;
    private $titular;
    private $saldo;
        
    public function __construct($numeroConta, $titular,$saldo){
        $this->numeroConta = $numeroConta;
        $this->titular = $titular;
        $this->saldo = $saldo;
    }

    public function sacar($valor){
        if ($valor<=$this->saldo){
            $this->saldo -= $valor;
            echo "Saque realizadocom sucesso!<br/>";
        } else {echo "Saldo insuficiete!<br/>";}
        return $valor;
    }

    public function depositar($valor){
        if ($valor <=0){
            return;
        }
        $this->saldo += $valor;
    }

    public function getSaldo(){
        return $this->saldo;
    }

    public function transferir($valor, $recebedor){
        if ($valor > $this->saldo){
            echo "Você não tem sado suficiente para essa transferência<br/>";  
            return;
        }
        $this->sacar($valor);
        $recebedor->depositar($valor);
    }
}

//classe filha

class contaCorrente extends Conta{
    
}

class contaPoupanca extends Conta{
    
}

 $conta1 = new contaCorrente(4536,"Ivana Bernardo",1000);
 $conta2 = new contaPoupanca(14512, "Ivana Bernardo",500);

$conta1->transferir(250,$conta2);
echo "Saldo conta 1:  ". $conta1->getSaldo(); echo "<br/>";
echo "Saldo conta 2:  ". $conta2->getSaldo();  echo "<br/>";


````
