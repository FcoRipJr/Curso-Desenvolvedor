# Estrutura do Laravel

Vamos criar um novo projeto Laravel para utilizarmos pelo restante do nosso curso. 

Entrar na pasta ~Documentos/Projetos/docker-flex

````sh
docker-compose exec --user=flexdock php7 bash
composer create-project laravel/laravel noticias
````

Vamos conhecer a estrutura de pasta do Laravel

## app

Neste diretório é onde certamente irá ficar a maior parte do nosso código, ou seja, é neste diretório que fica a parte lógica da aplicação que você irá desenvolver. E é neste diretório que tem a maior estrutura de diretórios e subdiretórios.

### /app/Http

As classes para tratamento das requisições Http ficam dentro deste diretório, neste diretório fica inclusive a outra camada do MVC que é o Controller.

#### /app/Http/Controllers

É onde fica as classes do MVC do Controller, ou seja, os controladores da aplicação ficam neste diretório. A Controladora (controller), como o nome já sugere, é responsável por controlar todo o fluxo de informação que passa pelo site/sistema. É na controladora que se decide “se”, “o que”, “quando” e “onde” deve funcionar. Define quais informações devem ser geradas, quais regras devem ser acionadas e para onde as informações devem ir, é na controladora que essas operações devem ser executadas. Em resumo, é a controladora que executa uma regra de negócio (modelo) e repassa a informação para a visualização (visão). 

#### /app/Http/Middleware

É onde fica os middlewares (filtros) personalizados. O Middleware é apenas um mecanismo de filtragem de requisição HTTP. Ou seja, ele permite ou barra determinados fluxos de requisição que entram na sua aplicação, baseado em regras definidas.

#### /app/Http/Requests

Classes com as validações (Este diretório não vem por default (padrão), ele é criado quando roda o comando: php artisan make:request NomeDaRequest). É um recurso nativo para validação de dados recebidos via http, pode ser via formulário, ajax, via requisição de API, etc.

A Request é o pedido que um cliente realiza ao nosso servidor. Esse pedido contém uma série de dados que são usados para descrever exatamente o que o cliente precisa. Exemplo, um cliente precisa cadastrar uma nova notícia em um site de notícias, todos os dados necessários devem ser passados corretamente para efetivar o cadastro, inclusive os dados que foram digitados pelo usuário em um formulário, no caso de uma aplicação web. No navegador toda vez que trocamos ou atualizarmos a página, é feita uma nova request independente se estamos apenas pedindo a exibição de uma página, cadastrando um novo recurso, atualizando ou excluindo alguma informação.

Como podemos utilizar a classe Request?

Geralmente o local onde mais usamos os dados enviados na requisição é o Controller. No Laravel, podemos injetar instâncias diretamente nos métodos do controller, isso nos permite usar facilmente a instância da classe Request.

### Models

É a camada do MVC Model, por padrão todos os modelos (Models) ficam armazenados dentro de app/Models. O modelo (Model) é utilizado para manipular informações de forma mais detalhada, sendo recomendado que, sempre que possível, se utilize dos modelos para realizar consultas, cálculos e todas as regras de negócio do nosso site ou sistema.

## bootstrap

É onde fica os arquivo(s) de inicialização do framework, o arquivo app.php tem a configuração de bootstrap da aplicação, ou seja, é este arquivo que inicia os serviços que o Laravel precisa para começar a rodar. Neste diretório também tem outro chamado “cache” que contém os arquivos gerados para otimização de cache.

## config

Como o próprio nome sugere é onde fica os arquivos de configuração do Framework. Os nomes dos arquivos de configuração são bastante sugestivos e cada um tem uma responsabilidade única e específica. 

### app.php 
Configuração da aplicação de forma geral, configurações nome da app, timezone, debug, url, internacionalização, log, providers e etc.
### auth.php 
Configuração de autenticação (login)
### broadcasting.php 
Configuração de Broadcast
### cache.php 
Configuração do cache, como por exemplo tipo de armazenamento (local) e prefixo.
### filesystems.php 
Configurações de upload de arquivos no Laravel (Local de armazenamento!). 
### mail.php 
Configurações do envio de e-mail, desde a escolha do driver (provedor) até informações de autenticação e porta.
### queue.php 
Configurações de filas, o Laravel permite trabalhar com filas para organizar rotinas pesadas e gerar menor gargalo de consumo na aplicação, sendo assim faz a aplicação trabalhar de forma mais inteligente utilizando os recursos a medida que estiverem disponíveis.
### services.php 
Configurações de acesso a serviços externos possíveis de trabalhar no Laravel, como por exemplo Stripe, Mailgun e etc.
### session.php 
Configurações de sessões, desde a escolha do driver (como será armazenado), até tempo de expiração, criptografia da sessão e etc.
### view.php 
Configuração de armazenamento das views (path), por default neste arquivo está configurado para resources/views/, se quiser um caminho diferente deste basta alterar neste arquivo. Os arquivos de view do Laravel são renderizados pelo Blade e por este motivo eles são compactados para serem exibidos de forma mais rápida, e é neste arquivo que define também o local onde ficará estes arquivos de view compilados, por default (padrão) fica em storage/framework/views/

## database

É neste diretório que fica os arquivos de definição de banco de dados, em alguns casos fica até mesmo o próprio banco de dados.

### /database/factories
Aqui é possível criar arquivos para fazer inserções dinâmicas com vários registros de uma única vez, ótimo para testar com registros no database, porque insere uma sequência definida de registros no banco de dados.
### /database/migrations
É onde fica os arquivos com a definição das estruturas das tabelas. A migração contém a data e a hora que permite ao framework determinar a ordem das migrações.
### /database/seeders
É onde fica os arquivos com algumas rotinas automáticas para banco de dados, como por exemplo inserir um registro assim que uma tabela for criada.

## public
É o Document Root do Laravel, ou seja, é o ponto de partida. O arquivo index.php é o start do Laravel, é a partir deste arquivo que tudo inicia (O arquivo autoload.php é incluído neste ponto). É dentro deste diretório que os arquivos públicos devem ficar, como por exemplo arquivos de imagens, fonts, css e JS.

## resources

Neste diretório fica alguns arquivos visuais da aplicação, como as próprias views, arquivos de internacionalização (tradução) e arquivos de assets não compilados como LESS, SASS e JavaScript
### /resources/assets
Arquivos não compilados de CSS e JS.
### /resources/lang
Arquivos de internacionalização (tradução)
### /resources/views
Seguindo a regra do MVC aqui é onde fica por padrão os arquivos de views

## routes

Como o próprio nome sugere é onde fica os arquivos de rotas do sistema. O sistema de rotas do Laravel é muito robusto.
### /routes/web.php 
Ficam as rotas da aplicação usando o MVC.
### /routes/api.php 
São as rotas para API, ou seja, rotas para criar Webservice no Laravel, as rotas aqui passam por alguns middlewares com o “api” que limita o total de requisição por um determinado intervalo de tempo. As rotas aqui NÃO passam pelo middleware CSRF. E rotas neste arquivo tem o prefixo “api”.
### /routes/channels.php 
É onde fica registrado todos os canais de transmissão de eventos de Broadcasting
### /routes/console.php 
Neste arquivo é onde define IO de comandos no Laravel

## storage
Em resumo neste diretório contém conteúdos que são armazenados e utilizados durante o processamento da aplicação. É aqui que fica os arquivos compilados do template blade do laravel. Que devem ficar os arquivos de UPLOAD e etc.

## tests

É neste diretório que deve ficar os arquivos para testes automatizados.

## vendor

Este diretório e tudo o que há nele é gerado pelo composer (que é o gerenciador de dependências do PHP), tudo o que há neste diretório é gerenciado pelo composer e por esse motivo não é válido fazer nenhuma alteração manual. O que há neste diretório é arquivos configuração de autoload do composer e pacotes de terceiros utilizados em seu projeto.

# Criando o Banco de Dados

Ainda no projeto ``Parte1``, vamos criar o banco de dados, mas antes disso configuraremos o arquivo ``.env`` do projeto Parte1.

````sh
#APP_NAME é o nome da sua aplicação
APP_NAME=Parte1 

#DB_HOST é o host da sua conexão do mysql. Para docker, utilize "mysql", para Laragon utilize "127.0.0.1" ou "localhost"
DB_HOST=mysql

#DB_DATABASE é o nome do banco de dados. O nome que deve ser utilizado aqui é o do banco de dados criado com o comando 'create database'
DB_DATABASE=parte1

#DB_PASSWORD é a senha do usuário no banco de dados. Por padrão no docker é root. No Laragon é vazio.
DB_PASSWORD=root

````

Agora vamos criar o banco de dados parte1.

````sh
cd docker-flex # entre no projeto docker-flex

sudo docker-compose exec mysql bash # entre no container do mysql

mysql -u root -p # digite a senha do usuário root. Por padrão é simplesmente root

Criando um banco de dados chamado parte1

create database parte1;

show databases;
````

# Comandos do Artisan

Artisan é o nome da interface da linha de comando incluída no Laravel. Esta interface fornece um bom número de comandos auxiliares para que você use durante o desenvolvimento de sua aplicação. 

Vamos conhecer os comandos mais utilizados do artisan. Para ter a lista completa de comandos do artisan faça o seguinte comando (dentro do container do php7: sudo docker-compose exec --user=flexdock php7 bash). **Para testar os comandos, utilize o projeto `parte1`**.

````sh
php artisan
````

Você deve receber uma listagem de comandos que começa assim:

````
Laravel Framework 8.49.1

Usage:
  command [options] [arguments]

Options:
  -h, --help            Display help for the given command. When no command is g
iven display help for the list command
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi|--no-ansi  Force (or disable --no-ansi) ANSI output
  -n, --no-interaction  Do not ask any interactive question
      --env[=ENV]       The environment the command should run under
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output,
 2 for more verbose output and 3 for debug

Available commands:
````
## make:migration
Cria uma nova migration que permite gerenciar as tabelas do banco de dados. Com este recurso é possível criar as tabelas, alterar a estrutura, definir a ordem de criação de cada tabela e fazer relacionamentos entre elas.

````sh
# no comando abaixo o nome da tabela deve ficar após create e antes de table, para padronização
php artisan make:migration create_tabela_table
````
No /database/migrations ele gerará um arquivo nesse formato:
 - database/migrations/ano_mes_dia_timestamps_create_tabela_table.php

## migrate
Executa as migrations que estão pendentes.

Uma vez que já criou as migrations e sua aplicação já está conectada ao banco de dados o próximo passo é gerar (criar) as tabelas.

````sh
php artisan migrate
````

Esse comando vai gerar as tabelas com as estruturas definidas (Roda os métodos up() das migrations que ainda não foram rodadas).
A primeira vez que roda este comando ele cria uma tabela chamada “migrations”, essa tabela basicamente tem o nome de todas as migrations geradas e qual foi a sequência (passo) de criação.

Vamos visualizar a tabela "migrations"

````sh
cd docker-flex # entre no projeto docker-flex

sudo docker-compose exec mysql bash # entre no container do mysql

mysql -u root -p # digite a senha do usuário root. Por padrão é simplesmente root

Usando o banco de dados parte1

use parte1;
 
visualizando as tabelas 

show tables;

+------------------------+
| Tables_in_parte1       |
+------------------------+
| failed_jobs            |
| migrations             |
| noticias               |
| password_resets        |
| personal_access_tokens |
| users                  |
+------------------------+
6 rows in set (0.00 sec)

Vamos selecionar todos os campos da tabela migrations.

select * from migrations;

+----+-------------------------------------------------------+-------+
| id | migration                                             | batch |
+----+-------------------------------------------------------+-------+
|  1 | 2014_10_12_000000_create_users_table                  |     1 |
|  2 | 2014_10_12_100000_create_password_resets_table        |     1 |
|  3 | 2019_08_19_000000_create_failed_jobs_table            |     1 |
|  4 | 2019_12_14_000001_create_personal_access_tokens_table |     1 |
|  5 | 2021_10_02_015951_create_noticias_table               |     1 |
+----+-------------------------------------------------------+-------+
5 rows in set (0.00 sec)
````

## model
São classes responsáveis pela administração dos dados.

````sh
php artisan make:model NomeDaModel
````

## make:seeder
Cria uma nova seeder. Com este recurso é possível definir algumas rotinas pré-configuradas para manipular banco de dados. Exemplo, é possível criar uma Seeder com os dados de um usuário default para ter acesso ao sistema ou uma Seeder para inserir os Estados do Brasil, entre outras.

````sh
php artisan make:seeder NomeDaSeeder
````

## db:seed
Após criadas as seeds, precisam ser executadas.

````sh
# executa todas as seeds
php artisan db:seed

# executa uma seed especifica
php artisan db:seed --class=NomeDaSeed
````

## make:request
Cria uma nova request

Um recurso nativo para validação de dados recebidos via http, pode ser via formulário, ajax, via requisição de API, etc.

````sh
php artisan make:request NomeDaRequest
````

## make:controller
Cria um novo Controller

O Controller é onde manipulamos a lógica de tratamento das requisições recebendo os dados do model e transmitindo-os para a view. É ele que abstrairá toda a complexidade da rota que, como já diz o nome, apenas roteará a Request feita para sua devida lógica.

````sh
php artisan make:controller NomeDoController
````

## migrate:fresh
Limpa todas as migrations e executa novamente (não executar em ambiente de produção)

````sh
php artisan migrate:fresh
````

## migrate:rollback
Desfaz a última migration executada, com base no método down dela.

````sh
php artisan migrate:rollback
````

## route:list
Lista todas as rotas dentro do Laravel, assim como os métodos e nomes delas

````sh
php artisan route:list
````

## storage:link
Cria um link simbólico para o storage dentro da pasta public

````sh
php artisan storage:link
````
The [/var/www/parte1/public/storage] link has been connected to [/var/www/parte1/storage/app/public].

No Laravel, seus arquivos acessíveis ao público devem ser colocados no diretório storage/app/public

Para torná-los acessíveis na web, você deve criar um link simbólico de public/storage para storage/app/public
