# Introdução

O Laravel é um dos Frameworks PHP mais utilizado no mercado. Um framework é um facilitador no desenvolvimento de diversas aplicações e, sem dúvida, sua utilização poupa tempo e custos para quem o utiliza, pois de forma mais básica, é um conjunto de bibliotecas utilizadas para criar uma base onde as aplicações são construídas, um otimizador de recursos. Tem como principal objetivo resolver problemas recorrentes com uma abordagem mais genérica. Ele permite ao desenvolvedor focar nos “problemas” da aplicação, não na arquitetura e configurações.

# Instalação

A melhor maneira de utilizar o Laravel é através do composer. Com o composer instalado, entre na pasta de projetos e execute os seguintes comandos.

* Entre na pasta onde ficam os seus projetos.
````sh
cd ~/projetos
````

* Crie um novo projeto usando o composer
````sh
composer create-project laravel/laravel parte1
````

Esse comando criará um novo projeto chamado ``parte1``. Utilizaremos ele para aprender o básico do Laravel. Entre no projeto e abra com o vscode.

````sh
cd parte1

code .
````

Faça a criação do projeto dentro do container do PHP usando o [docker-flex](https://github.com/flexpeak/docker-flex). Dentro do nginx você deve criar um nvo arquivo .conf que apontará justamente para o nosso novo projeto criado ``(/var/www/parte1/public)``

# Introdução ao Laravel

## Adequação ao código

Vamos começar falando de conceitos de PHP e da orientação a objetos utilizados pelo Laravel. Tudo o que vimos até agora continua valendo, porém o Laravel faz uso de mais conceitos.

Abra o arquivo ``app/Models/User.php``. Vamos falar um pouco da estrutura dele.

### Namespace

São declarados utilizando a palavra-chave namespace. Um arquivo que contenha namespace deve realizar a declaração do mesmo logo no inicio, antes de qualquer código, com exceção de comentários, espaços em branco e declare.

O namespace é usado para indicar para o composer onde a classe está localizada. Perceba que o arquivo User.php está localizado na pasta ``app/Models``, não é coincidência que o namespace seja ``App\Models``. 

Seguindo o pensamento dos diretórios abaixo, o recurso de namespaces também permite adicionar nomes com estrutura hierárquica, ou seja, sub-namespaces.

````php
<?php
namespace App\Models;
````

### PSR-4

É uma recomendação da comunidade PHP para organização e carregamento de arquivos e classes PHP. 

### autoload

É utilizada no PHP para fazer o carregamento automático das classes.

### composer.json

No arquivo ``composer.json`` na raiz do projeto Laravel é feito o mapeamento do autoload. Observe:

Na linha 3 vemos o uso do Namespace.

````json
    ...
    "autoload": {
        "psr-4": {
            "App\\": "app/",
    ...
````

Ou seja, todo o namespace ``App`` é procurado para pasta ``app`` do projeto. Então sempre o namespace vai indicar onde o composer poderá fazer autoload da classe:

````php
namespace App\Models; // indica que a classe está em app/Models
namespace App\Http\Controllers; // indica que a classe está em app/Http/Controllers
namespace Database\Seeders; // indica que a classe está em database/seeders - sempre de acordo com o composer.json
````

### Use

Agora abra o arquivo ``app/database/factories/UserFactory.php`` e observe que da linha 5 até a linha 7 o Laravel usou o ``use``. 

````php
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
````

O ``use`` é usado para incluir as classes, dado o ``namespace``, dentro do arquivo .php atual. Quando o Laravel faz ``use App\Models\User;``, ele está incluindo a classe que está localizada em ``app/Models/User.php`` dentro da classe atual. 

Observe ainda que ele está usando o ``use`` em ``Illuminate\Database\Eloquent\Factories\Factory``, porém a pasta Illuminate não existe. Isso é possível por que a pasta está localizada na pasta ``vendor``(~\Projetos\parte1\vendor\laravel\framework\src\Illuminate\Database\Eloquent\Factories\Factory.php), pois ela foi baixada pelo Laravel usando o ``composer``. 

````php
<?php

namespace Illuminate\Database\Eloquent\Factories;
...
````

### Nome da Classe

Ainda no arquivo ``UserFactory``, observe que ele está nomeando a classe com o mesmo nome do arquivo:

````php
class UserFactory extends Factory {
    ...
````

Esta é uma prática indispensável, pois o composer também valida se a classe tem o mesmo nome do arquivo, principalmente na versão 2.

### Extends

A classe ``UserFactory`` está usando o ``extends`` na classe ``Factory``. Observe que a classe ``Factory`` foi mapeada usando o ``use`` logo no início do arquivo. O ``extends`` significa que todos os métodos e atributos da classe ``Factory`` passam a ser acessíveis na classe ``UserFactory``. Observe, por exemplo, o método ``unverified()``. Ele está usando o método ``state`` no objeto ``$this``, porém o método ``state`` não existe na classe ``UserFactory``. Isso é possível por que o método ``state`` existe na classe ``Factory``. Como está sendo feito o extends, o método passa a ficar disponível na classe ``UserFactory``. 

### Use Dentro da Classe

Voltando para a model de ``User`` (\app\Models\User.php), observe que há um ``use`` dentro da ``class User`` e não mais fora.

````php
use HasApiTokens, HasFactory, Notifiable;
````

Isso é possível por que a classe ``HasFactory`` e ``Notifiable`` são ``traits``. Nesse momento basta você entender que, neste caso, isso foi feito para incluir os métodos das traits dentro da classe ``User``, parecido com o que é feito com o ``extends``. 

### Encapsulamento dos Atributos da Classe

````php
protected $fillable = [
    'name',
    'email',
    'password',
];
````

Observe que o atributo ``$fillable`` está encapsulado com ``protected``. O tipo ``public`` e ``private`` já vimos na aula de lógica. 

* **Public**: Visível, acessível e alterável em todo lugar
* **Private**: Visível, acessível e alterável apenas dentro da classe
* **Protected**: Visível, acessível e alterável apenas dentro da classe e em classes que extenderam ela

### Métodos Estáticos

Abra o arquivo ``app/Providers/RouteServiceProvider.php``. Ele tem alguns conceitos que já vimos antes, mas observe o método ``boot()``. 

````php
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
    }
````

Esse método faz uso de métodos não estáticos e métodos estáticos. O método ``configureRateLimiting()`` é não estático, pois ele está sendo chamado a partir do objeto ``$this``. Métodos estáticos permitem ser chamados a partir da própria classe, sem necessidade de um objeto instanciado. Observe o método ``prefix()``. Esse método está dentro da classe ``Route``. Observe que ele está sendo chamado diferentemente de como o método ``configureRateLimiting()`` está sendo. 

````php
$this->configureRateLimiting(); // Métodos não estáticos precisam de um objeto... nesse caso $this é o objeto
Route::prefix('api'); // Métodos estáticos são chamados direto da classe usando o ::
````

## Padrão MVC
O padrão MVC é um dos padrões mais utilizados para desenvolvimento Web. A aplicação web passa a ser dividida em camadas. 

* **M - Model**: Nesta camada deverão ficar as classes que fazem toda a regra de negócio da sua aplicação, inclusive as entidades e classes de acesso ao banco de dados. As models no Laravel ficam na pasta ``app/Models``.
* **V - View**: Nesta camada deverá ficar a parte visual do seu site, como HTML, CSS e JavaScript. É a camada de apresentação da sua aplicação. É a resposta que o framework envia para o navegador, que normalmente é um HTML. As views do Laravel ficam na pasta ``resources/views``.
* **C - Controller**: Esta camada é responsável por receber as requisições da web e enviá-las para a model (quais models devem ser executados para a ação escolhida) e para qual view será encaminhada a resposta, essa camada quem faz o link entre todas as outras. Os controllers do Laravel ficam na pasta ``app/Http/Controllers``.

![image](https://user-images.githubusercontent.com/84885503/133870281-01eb9075-f94f-4a35-aafc-27c70ef483e2.png)


## Métodos HTTP
O HTTP (Hypertext Transfer Protocol - Protocolo de Transferência de Hipertexto) é o protocolo responsável pela comunicação de sites na web. O protocolo HTTP define um conjunto de métodos de requisição responsáveis por indicar a ação a ser executada para um dado recurso. 

### GET
O método GET solicita a representação de um recurso específico. Elas podem ser acessadas pelo navegador sem configuração adicional. Quando utilizamos o GET os parâmetros são passados no **cabeçalho da requisição**. Por isso, podem ser vistos pela URL.

````html
<form method="GET">
  <!--código-->
</form>

````

![aula6_método GET](https://user-images.githubusercontent.com/84885503/133871685-2cb05595-909d-4d64-84b7-0e122c5cabe8.png)

### POST
O método POST é utilizado para submeter uma requisição a um servidor. Os dados da requisição são enviados no **corpo da requisição** do HTTP, portanto não visíveis a um usuário comum. 

````html
<form method="POST">
  <!--código-->
</form>
````

![aula6_método POST](https://user-images.githubusercontent.com/84885503/133871617-69f8d0e9-8965-4c5c-b227-41f50c999c1f.JPG)

Só porque o usuário não vê os dados na URL não quer dizer que eles estão protegidos.

![aula6_método POST - parte 2](https://user-images.githubusercontent.com/84885503/133871623-f64f750e-941e-4fa0-b48d-a2f3bac7db5a.png)

### PUT
O método PUT funciona semelhante ao método POST, porém é usado para alteração de dados.

### DELETE
O método DELETE é usado semelhantemente ao método POST, porém para exclusão de dados. 

=======
# Ações pós-instalação
Após instalar o projeto do Laravel, você deve dar permissão de escrita em duas pastas: storage e bootstrap/cache. Os comandos estão abaixo:

````sh
sudo chmod 0777 -R storage
sudo chmod 0777 -R bootstrap/cache
````

# Ações pós-clone

Após clonar um projeto Laravel é necessário primeiramente entrar no container do php
````sh
sudo docker-compose exec --user=flexdock php7 bash
```` 

Entrar na pasta parte1
````
cd parte1
````

Rodar o comando do composer install para baixar as depedências do projeto

````sh
composer install
````

Criar o arquivo ``.env`` com base no arquivo ``.env-example``. Você pode usar o comando cp para isso:
````sh
cp .env.example .env
````

Laravel Key Generate é um comando que auxilia na configuração do valor APP_KEY no arquivo .env. Este comando é executado diretamente e por padrão quando um comando composer create-project do Laravel é gerado. 

Quando você tende a usar o git para lidar com o projeto que está desenvolvendo no Laravel, ele fará uma cópia do seu projeto e o colocará no espaço para onde quer que você o esteja enviando, mas o **arquivo .env não será copiado**. Logo, terá que inserir a chave artesanal do PHP: gerar o arquivo manualmente.
````sh
php artisan key:generate
````

# Rotas

As rotas da nossa aplicação ficam na pasta ``routes`` do nosso projeto Laravel. Inicialmente vamos falar das rotas ``web.php``. No arquivo ``web.php`` devem ficar todas as rotas da nossa aplicação quando usadas no padrão MVC. Para explicar o funcionamento das rotas, vamos criar uma nova rota. Copie o código abaixo e coloque no final do arquivo web.php

````php
Route::get('/teste', function() {
    echo "Este é um teste";
});
````

Na linha 1 acessamos o método estático ``get`` do objeto ``Route``. ``/`` é o endereço que acessaremos, referente a página inicial da aplicação.

Continuando na Linha 1, ``function ()`` é a declaração de uma função anônima (sem nome).

Na Linha 3 ``echo`` exibirá o texto "Este é um teste" quando a rota for acessada.

Agora abra o navegador no seu projeto ``/teste``. Você deverá ver exatamente a mensagem enviada pelo echo. Basicamente as rotas servem para isso. Servem para executar um código PHP dado uma URL da sua aplicação. A rota que acabamos de criar é do tipo GET. Então se, usando o GET, o usuário acessar a rota ``/teste``, o código PHP dentro da ``function()`` será executado. 
