## 1. Desafio 01

1. Transformar o código abaixo em função
2. Permitir que a quantidade de notas seja indefinida
3. Verificar se o aluno aprovou (média igual o superior a 5)

````js
var notas = [8, 5, 4, 3];
var soma = 0;

notas.forEach(function(nota) {
  soma = soma + nota;
});

var media = soma / 4;
alert("Sua média é " + media);
````

<details> 
  <summary>Clique para visualizar a resposta</summary>

  ````js
  /* 1. Criei uma função chamada calcularMedia e coloquei todo o código inicial dentro dela. */
  function calcularMedia() {
    var notas = [8, 5, 4, 3, 6];
    var soma = 0;

    notas.forEach(function(nota) {
        soma = soma + nota;
    });

    var media = soma / notas.length;
    /* 2. Para que a quantidade de notas seja indefinida, foi necessário dividir pela quantidade de ítens do array e não pelo número 4 estaticamente. */

    alert("Sua média é " + media);

    /* Aqui eu uso o if para verificar se o aluno aprovou ou não */
    if (media >= 5) {
        alert("Aluno foi aprovado");
    } else {
        alert("Aluno foi reprovado");
    }
  }

  calcularMedia();
  /* Aqui eu executo a função recém criada. */
  ````

</details>

## 2. Desafio 02

Altere o código abaixo de forma que você consiga identificar o maior desconto e o menor desconto oferecido por uma loja comercial. Utilize o forEach para iterar o array.

````js
var descontos = [30, 45, 60, 20, 10, 75];
````

<details> 
  <summary>Clique para visualizar a resposta</summary>

````js
var descontos = [30, 45, 60, 20, 10, 75];
var maior = 0;
var menor = 100;

descontos.forEach(function(valor, index) {
  if (valor > maior) {
    maior = valor;
  }
  
  if (valor < menor) {
    menor = valor;
  }
});

alert("O menor desconto é: " + menor);
alert("O maior desconto é: " + maior);

````
</details>

## 3. Desafio 03

Vamos calcular a comissão em reais de um vendedor. A regra é a seguinte: temos um array com o valor das vendas e devemos aplicar um percentual de comissão dependendo do valor total das vendas do vendedor. A comissão inicial do vendedor é 15%, porém se ele vender mais de 100 reais a comissão será de 20% e se ele vender 200 reais ou mais a comissão será de 25%. 

Calcule a comissão em reais do vendedor e exiba para o usuário usando o alert.

````js
var vendas = [32.54, 44.56, 50.10, 11.09];
````

<details> 
  <summary>Clique para visualizar a resposta</summary>

````js
var vendas = [32.54, 44.56, 50.10, 11.09];
var totalVendas = 0;
var valorComissao = 0;

vendas.forEach(function(valor, index) {
  totalVendas = totalVendas + valor;
});

if (totalVendas <= 100) {
  valorComissao = totalVendas * 0.15;
} else if (totalVendas > 100 && totalVendas <= 200) {
  valorComissao = totalVendas * 0.2;
} else if (totalVendas > 200) {
  valorComissao = totalVendas * 0.25;
}

alert("O valor total das suas vendas é: " + totalVendas);
alert("O valor da sua comissão é: " + valorComissao);
````

</details>

## 4. Desafio 04

No array abaixo temos uma lista de sexo de pessoas. Ajude-me a descobrir quantas pessoas tem do sexo masculino e quantas pessoas tem do sexo feminino.

Você deve utilizar o forEach para resolver esse desafio.

````js
var sexos = ['M', 'M', 'F', 'M', 'F', 'F', 'F', 'M', 'F', 'M', 'F', 'M', 'F', 'F', 'M', 'M', 'M', 'F', 'F'];
````

<details> 
  <summary>Clique para visualizar a resposta</summary>

````js
var sexos = ['M', 'M', 'F', 'M', 'F', 'F', 'F', 'M', 'F', 'M', 'F', 'M', 'F', 'F', 'M', 'M', 'M', 'F', 'F'];
var masculino = 0;
var feminino = 0;

sexos.forEach(function(valor, index) {
  if (valor == 'F') {
    feminino++;
    // feminino = feminino + 1;
    // feminino += 1;
  } else if (valor == 'M') {
    masculino++;
  }
});

alert("A quantidade masculina é: " + masculino);
alert("A quantidade feminina é: " + feminino);
````

</details>

## 5. Desafio 05

Utilize o ``while`` com o  ``confirm`` com a seguinte pergunta: "Deseja cadastrar uma idade?", enquanto o usuário responder ``sim`` você deve utilizar o ``prompt`` e pedir para o usuário digitar uma idade. Após o usuário digitar uma idade, você deve atribuir o valor digitado a um array usando o ``push`` [(Você pode clicar aqui para descobrir como utilizar o push)](https://developer.mozilla.org/pt-BR/docs/Web/JavaScript/Reference/Global_Objects/Array/push). 

No final, utilize o ``forEach`` para informar para o usuário a menor idade digitada. 

<details> 
  <summary>Clique para visualizar a resposta</summary>

````js
// noprotect

var idades = [];
var menor = 999;

while (confirm("Você deseja cadastrar idade?")) {
  var idade = prompt("Digite a idade");
  idades.push(idade);
}

idades.forEach(function(valor, index) {
  if (valor < menor) {
    menor = valor;
  }
});

alert("A menor idade é: " + menor);
````

</details>

## Projeto Prático

**Nossa História de Usuário:**

> Como gerente de uma escola do ensino médio quero salvar o nome, a idade e as notas bimestrais dos meus alunos, para que, no final, eu possa saber o nome do aluno que obteve a maior média e o nome do aluno mais novo, para que eu possa premiá-los no final.

**Requisitos Técnicos:**

* Você deve utilizar Programação Orientada a Objetos
* Suas classes devem utilizar encapsulamento correto
* Seu código deve ser possível cadastrar um número indefinido de alunos

<details> 
  <summary>Clique para visualizar a resposta</summary>

````js
class Aluno {
  #notas = [];
  #totalNotas = 0;
  
  constructor(nome, anoNascimento) {
    this.nome = nome;
    this.anoNascimento = anoNascimento;
    this.#calculaIdade();
  }
  
  #calculaIdade() {
    this.idade = 2021 - this.anoNascimento;
  }
  
  adicionaNota(nota) {
    if (this.#notas.length == 4) {
      alert("Você já adicionou quatro notas. Ignorando.");
    } else {
      this.#notas.push(nota);
    }
  }
  
  calculaMedia() {
    this.#notas.forEach((valor) => {
      this.#totalNotas += valor;
    });

    this.media = this.#totalNotas / this.#notas.length;
  }
}

var alunos = [];

aluno1 = new Aluno("Adéliton", 1992);
aluno1.adicionaNota(8.9);
aluno1.adicionaNota(9);
aluno1.adicionaNota(5.5);
aluno1.adicionaNota(7.8);
aluno1.calculaMedia();
alunos.push(aluno1);

aluno2 = new Aluno("João", 1995);
aluno2.adicionaNota(5);
aluno2.adicionaNota(7);
aluno2.adicionaNota(8.3);
aluno2.adicionaNota(3.4);
aluno2.calculaMedia();
alunos.push(aluno2);

aluno3 = new Aluno("Maria", 1992);
aluno3.adicionaNota(10);
aluno3.adicionaNota(9.5);
aluno3.adicionaNota(8.9);
aluno3.adicionaNota(9.1);
aluno3.calculaMedia();
alunos.push(aluno3);

aluno4 = new Aluno("Alberto", 1990);
aluno4.adicionaNota(4.5);
aluno4.adicionaNota(5.9);
aluno4.adicionaNota(3.1);
aluno4.adicionaNota(4.3);
aluno4.calculaMedia();
alunos.push(aluno4);

var idadeMaisNova = 999;
var maiorMedia = 0;
var alunoMaisNovo = null;
var alunoMaiorMedia = null

alunos.forEach(function(aluno) {
  if (aluno.idade < idadeMaisNova) {
    idadeMaisNova = aluno.idade;
    alunoMaisNovo = aluno;
  }
  
  if (aluno.media > maiorMedia) {
    maiorMedia = aluno.media;
    alunoMaiorMedia = aluno;
  }
});

alert("O aluno mais novo é: " + alunoMaisNovo.nome);
alert("O aluno com a maior média é: " + alunoMaiorMedia.nome); 

console.log(alunos);
````

Foi feito o uso de [Arrow Functions](https://www.w3schools.com/js/js_arrow_function.asp) do JavaScript. O uso de arrow functions foi necessário para não termos problemas com o ``this`` do JavaScript.
</details>