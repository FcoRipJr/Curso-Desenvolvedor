<?php
class Aluno{
    public $nome = "Maria";
    private $idade = 25;

    public function nome()
    {
        echo "Seu nome: " . $this->nome;
        echo "<br/>";
    }

    public function idade()
    {
        echo "Sua Idade: " . $this->idade;
        echo "<br/>";
    }

    public function getIdade($novaIdade)
    {
        $this->idade = $novaIdade;
    }

}

$aluno = new Aluno();
// print_r($aluno);
$aluno->nome();
$aluno->idade();
$aluno->getIdade(30);
$aluno->idade();
