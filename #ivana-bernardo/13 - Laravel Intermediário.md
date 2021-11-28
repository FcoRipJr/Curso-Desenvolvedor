# Rotas Resources

É a declaração de rota única que cria várias rotas para lidar com uma variedade de ações. O controlador gerado possui métodos fragmentados para cada uma dessas ações. 
Para pode obter uma visão geral rápida das rotas de seu aplicativo execute o comando ``php artisan route:list`` no container do php.
No web.php substitua as 6 rotas por:

````php
//Route::resource('objeto','controller');
Route::resource('noticias', NoticiaController::class);

//A rota acima susbstitui as 6 rotas criadas
// Route::get('noticias/create', [NoticiaController::class, 'create']);
// Route::post('noticias', [NoticiaController::class, 'store']);
// Route::get('noticias', [NoticiaController::class, 'index']);
// Route::get('noticias/{noticia}/edit', [NoticiaController::class, 'edit']);
// Route::put('noticias/{noticia}', [NoticiaController::class, 'update']);
// Route::delete('noticias/{noticia}', [NoticiaController::class, 'delete']);
````

# Confirmar exclusão

https://sweetalert.js.org/guides/

Colocar após o jQuery
````html
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
````
No ``index.blade`` colocamos o ``onsubmit="confirmarExclusao(event)`` no formulário que contém o botão Excluir, vamos agora implementar o método.

Localizar o ``dangerMode`` para visualizar o código.

Colocar após a inclusão do script
````html
<script>
    function confirmarExclusao(event) {
        event.preventDefault();
        swal({
            title: "Você tem certeza que deseja excluir o registro?",
            icon: "warning",
            dangerMode: true,
            buttons: {
                cancel: "Cancelar",
                catch: {
                    text: "Excluir",
                    value: true,
                },
            }
        })
        .then((willDelete) => {
            if (willDelete) {
                event.target.submit();
            } else {
                return false;
            }
        });
    }
</script>
````

# Seeds

O Laravel por padrão inclui um método simples para criarmos e executarmos seeders para popular rapidamente nosso banco de desenvolvimento através de classes que chamamos de seeders. Um seeder contém um array de dados que serão inseridos no banco de dados quando chamados.

O uso de seeders é muito comum, especialmente em ambientes de testes e homologação, para que você possa ter dados no banco para começar a trabalhar.

https://laravel.com/docs/8.x/seeding#introduction

Para criar uma seed use o Artisan. Iremos criar a seed NoticiaSeeder para inserir um registro.

````sh
sudo docker-compose exec --user=flexdock php7 bash

cd noticias

php artisan make:seed NoticiaSeeder

# database/seeders/NoticiaSeeder.php
````

````php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Noticia;

class NoticiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Noticia::create([
            "titulo" => "MAXIMIANO, DONO DA PRECISA, É ACUSADO DE DAR CALOTE DE R$ 8 MILHÕES EM FUNDO DE PREVIDÊNCIA DA OAB",
            "conteudo" => "Ele é o dono da empresa envolvida na negociação de compra da Covaxin, vendida por um preço mais alto ao governo Bolsonaro.",
            "status" => "A",
            "imagem" => "/storage/2021-07-07t134451z-9950647-rc2pfo9vq1no-rtrmadp-3-pope-surgery-suffering.jpg",
            "data_publicacao" => "27/07/2021"
        ]);
    }
}
````

Rodar uma seed específica
````sh
php artisan db:seed --class=NoticiaSeeder
````

Rodar todas as seeds
````sh
php artisan db:seed
````

# Relacionamentos

O que é relacionamento entre tabelas no banco de dados?

Os relacionamentos de banco de dados são associações entre tabelas que são criadas usando instruções de junção para recuperar dados. 


## HasMany

Relacionamento 1 para muitos.

![image](https://user-images.githubusercontent.com/84885503/138956354-378daae4-fd17-4159-83eb-b30480fd519d.png)
fonte: https://www.itsolutionstuff.com/post/laravel-one-to-many-eloquent-relationship-tutorialexample.html

Veja que um posts tem vários comments.

Vamos primeiramente criar a tabela comentarios.

Criar migration de comentários
````sh
php artisan make:migration create_comentarios_table
````

````php
    Schema::create('comentarios', function (Blueprint $table) {
        $table->id();
        $table->text('conteudo');

        $table->unsignedBigInteger('noticia_id')->nullable();
        $table->foreign('noticia_id')
            ->references('id')
            ->on('noticias')
            ->onUpdate('restrict')
            ->onDelete('restrict');

        $table->timestamps();
    });
````
``restrict`` significa que qualquer tentativa de excluir e/ou atualizar o pai falhará, gerando um erro. Este é o comportamento padrão no caso de uma ação onde tem uma chave estrangeira (foreign).

Execute as migrations
````sh
php artisan migrate
````

Vamos criar o relacionamento do lado das noticias.

Criar a model de comentarios (no singular)
````sh
php artisan make:model Comentario
````

Editar a model de noticias

````php
public function comentarios()
{
    return $this->hasMany(Comentario::class);
}
````
model_destino: Comentario::class

Agora iremos exibir o relacionamento na view edit.blade

````php
<h4>Comentários</h4>
@foreach ($noticia->comentarios as $comentario)
    <div>
        {{ $comentario->conteudo }}
        <p class="text-muted">Criado em: {{ $comentario->created_at->format('d/m/Y H:i') }}</p>
    </div>
@endforeach
````

## Criando Seed para comentarios

````php
php artisan make:seed ComentarioSeeder
````
````php
use App\Models\Comentario;

public function run()
{
 Comentario::create([
     "conteudo" => "Gostei :-)",
     "noticia_id" => "3"
 ]);
}
````
Observe se o noticia_id existe, pois você pode ter excluído, então, coloque um noticia_id existente na base de dados.

Essa seed será mostrada na view edit.blade

Agora vamos rodar a seed
````php
php artisan db:seed --class=ComentarioSeeder
````

## BelongsTo

Relacionamento 1 para 1.

Precisamos agora gerar o relacionamento do lado dos comentários.

![image](https://user-images.githubusercontent.com/84885503/138956354-378daae4-fd17-4159-83eb-b30480fd519d.png)
fonte: https://www.itsolutionstuff.com/post/laravel-one-to-many-eloquent-relationship-tutorialexample.html

https://laravel.com/docs/8.x/eloquent-relationships

Editar a model de comentários
````php
    public function noticia() 
    {
        return $this->belongsTo(Noticia::class);
    }
````

Editar a view edit.blade
````php
<h4>Comentários</h4>
@foreach ($noticia->comentarios as $comentario)
    <div>
        {{ $comentario->conteudo }}
        <p class="text-muted">Criado em: {{ $comentario->created_at->format('d/m/Y H:i') }}</p>
        <p class="text-muted">Noticia criada em: {{ $comentario->noticia->data_publicacao->format("d/m/Y") }}</p>
    </div>
@endforeach
````

## BelongsToMany

Relacionamento muitos para muitos.

Veja o exemplo de cargo e função.

![image](https://user-images.githubusercontent.com/84885503/138981648-d2c54818-f495-480a-bceb-a4ce397812f1.png)
Fonte: https://www.itsolutionstuff.com/post/laravel-many-to-many-eloquent-relationship-tutorialexample.html

No nosso exemplo vamos criar o relacionamento noticias e categoria. 

## Atividade

Como atividade vamos criar as migrations categorias e noticias_categorias (é a migration que relaciona as duas outras migrations), fazer o relacionamento das tabelas, povoar a migration categorias com as seguintes informações:

- Esporte
- Economia
- Eleições 
- Laser
- Entretenimento, e seguida,

povoar a migration noticias_categorias com os códigos que existem nas duas tabelas e criar as models ``Categoria`` e ``NoticiaCategoria``.

<details> 
  <summary>Clique para visualizar a resposta</summary>
    
       docker-compose exec --user=flexdock php7 bash
       cd noticias
       php artisan make:migration create_categorias_table
    
         public function up()
         {
            Schema::create('categorias', function (Blueprint $table) {
                $table->id();
                $table->string('categoria', 100);
                $table->timestamps();
            });
         }
    
       php artisan make:migration create_noticias_categorias_table
    
        public function up()
        {
            Schema::create('noticias_categorias', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('noticia_id')->nullable();
                $table->foreign('noticia_id')
                ->references('id')
                ->on('noticias')
                ->onUpdate('restrict')
                ->onDelete('restrict');

                $table->unsignedBigInteger('categoria_id')->nullable();
                $table->foreign('categoria_id')
                ->references('id')
                ->on('categorias')
                ->onUpdate('restrict')
                ->onDelete('restrict');
                $table->timestamps();
            });
        }

       Rodar as migrates
       php artisan migrate
    
       Criar a model para categoria (no singular)
       php artisan make:model Categoria
    
       Criar a model para NoticiaCategoria (no singular)
       php artisan make:model NoticiaCategoria
    
       Adicionar o método de relacionamento na model Noticias
       public function categorias()
       {
           return $this->belongsToMany(Categoria::class, 'noticias_categorias');
       }
       (model_destino, nome_tabela_intermediaria)
       (Categoria:class,'noticias_categorias')

       Criar a seed para Categorias
       php artisan make:seed CategoriaSeeder
       
       Criar a seed para Noticias_Categorias
       php artisan make:seed Noticia_CategoriaSeeder
      
       Povoar a seed Categorias
    
        use App\Models\Categoria;
        public function run()
        {
            Categoria::create([
                "categoria" => "Política"
            ]);

            Categoria::create([
                "categoria" => "Esportes"
            ]);

            Categoria::create([
                "categoria" => "Economia"
            ]);

            Categoria::create([
                "categoria" => "Eleições"
            ]);

            Categoria::create([
                "categoria" => "Laser"
            ]);

            Categoria::create([
                "categoria" => "Entretenimento"
            ]);
        }
       
        Rodar a seed CategoriaSeeder
        php artisan db:seed --class=CategoriaSeeder
               
        Povoar a seed NoticiaCategoria
        use App\Models\NoticiaCategoria;
    
         public function run()
         {
            Noticia_Categoria::create([
                "noticia_id" =>"4",
                "categoria_id" => "1"
            ]);

            Noticia_Categoria::create([
                "noticia_id" =>"4",
                "categoria_id" => "2"
            ]);

            Noticia_Categoria::create([
                "noticia_id" =>"3",
                "categoria_id" => "3"
            ]);

            Noticia_Categoria::create([
                "noticia_id" =>"2",
                "categoria_id" => "1"
            ]);
    
            Noticia_Categoria::create([
                "noticia_id" =>"2",
                "categoria_id" => "4"
            ]);
        }
      
        Rodar a seed Notica_CategoriaSeeder
        php artisan db:seed --class=Noticia_CategoriaSeeder
      
        Hummmm, deu errado?
    
        SQLSTATE[42S02]: Base table or view not found: 1146 Table 'noticias.noticia_categorias' doesn't exist (SQL: insert into `noticia_categorias` (`noticia_id`, `categoria_id`, `updated_at`, `created_at`) values (1, 1, 2021-10-27 00:10:12, 2021-10-27 00:10:12))
        Olha o nome da tabela noticia_categoriaS na mensagem de erro!
      
        Lembra que falamos que criamos a model no singular porque o laravel coloca um ``s`` no final do nome para ficar igual ao nome da tabela?
      
        Como nesse caso a tabela chama noticia_categoria, ele irá colocar noticia_categorias então tem que setar a tabela.
    
        Na model NoticiaCategoria
        class NoticiaCategoria extends Model
        {
          use HasFactory;
          protected $table = "noticias_categorias"
        }
       
 </details>

# Instalação AdminLTE

Já imaginou se, para utilizar componentes como gráficos, tabelas para visualizar grandes volumes de dados ou calendários em uma aplicação web, fosse preciso implementar do zero todos esses recursos? Felizmente, hoje existem templates administrativos, como o AdminLTE, que possibilitam o uso dessas ferramentas em uma página web de forma muito mais simples e rápida.

O AdminLTE é um template HTML responsivo para ser utilizado em painéis administrativos ou de controles em aplicações web. O objetivo é disponibilizar uma série de componentes para serem reutilizados no site, como o layout, menus de navegação, chat, timeline, formulários e muitos outros recursos capazes de tornar a página mais funcional e estilizada.

Mais detalhes você pode encontrar no https://blog.betrybe.com/tecnologia/adminlte/

## Link do repositório
https://github.com/jeroennoten/Laravel-AdminLTE

## Instalação

O AdminLTE também pode ser instalado pela linha de comando, através de gerenciadores de pacotes. Existem diferentes ferramentas disponíveis no mercado, como o NPM (Node Package Manager), do Node, o Yarn ou o Composer. Além disso, também é possível fazer um clone para a máquina por meio do Git, que é uma ferramenta de controle de versões utilizada pelo GitHub.

````sh
composer require jeroennoten/laravel-adminlte
````

Caso houver erro de memória, retire os limites com o comando abaixo:
````sh
php -d memory_limit=-1 /usr/local/bin/composer require jeroennoten/laravel-adminlte
````

Vamos agora instalar a depência para termos uma tela de login

````sh
php -d memory_limit=-1 /usr/local/bin/composer require laravel/ui
````

Vamos indicar que vamos utilizar os assets do bootstrap no sistema de login
````sh
php artisan ui bootstrap --auth
````

Agora vamos realizar a instalação do pacote adminlte
````sh
php artisan adminlte:install
````

Vamos ainda utilizar as views de login do adminlte
````sh
php artisan adminlte:install --only=auth_views
````

## Criação da seed de usuário
````sh
php artisan make:seed UsersSeeder
````

O conteúdo da Seed tem que ser 
````php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin')
        ]);
    }
}

````

Agora vamos executar a seed
````sh
php artisan db:seed --class=UsersSeeder
````

Agora vamos acessar a rota de /login e fazer login com o novo usuário logado.

## NPM

NPM (Node Package Manager)é um gerenciador de pacotes utilizado para administrar as bibliotecas e frameworks utilizados em uma aplicação. O NPM faz parte do Node.js e esse, por sua vez, é um ambiente para a execução de JavaScript no lado do servidor de hospedagem. Em outras palavras, ele permite utilizar a linguagem JavaScript no back-end da aplicação, o que não era possível antes da existência do Node.

Agora irmos usar o npm para compilar o JavaScript e o CSS do AdminLTE

````sh
npm install
````

````sh
npm run dev
````

Vamos alterar o conteúdo da view ``home.blade.php`` para o seguinte:

````html
@extends('adminlte::page')

@section('title', 'Início')

@section('content_header')
<h1 class="m-0 text-dark"><i class="fas fa-home"></i> Início
    <small class="text-muted">- Bem-vindo ao sistema do Curso Flexpeak</small>
</h1>
@stop

@section('content')
    <div class="row">
        
    </div>
@stop
````
@extends('page'), estender o ``page.blade.php`` que está no diretório ``resources/views\vendor\adminlte``.

@section('title', 'Início') = título do navegador

@section('content_header') = cabeçalho 

class="fas fa-home = ![image](https://user-images.githubusercontent.com/84885503/139489690-57936637-a566-46ac-8121-08314132d6e7.png)

cores: text-dark e text-muted = https://getbootstrap.com/docs/4.1/utilities/colors/

Agora vamos configurar o nosso menu no arquivo ``config/adminlte.php``

No índice 'menu' vamos adicionar o menu de notícias
````php
    'menu' => [
        [
            'text' => 'Notícias',
            'url'  => 'noticias',
            'icon' => 'fas fa-newspaper'
        ],
    ],
````
fas fa-newspaper = ![image](https://user-images.githubusercontent.com/84885503/139319597-288e0de3-326f-4213-b9f5-c2a1e2d65979.png)
 

Agora vamos fazer com que as views que já criamos utilizem o layout do adminlte.

Faça com que o conteúdo da view de index seja:

````php
@extends('adminlte::page')

@section('title', 'Notícias')

@section('content_header')
<h1 class="m-0 text-dark"><i class="fas fa-home"></i> Notícias
    <small class="text-muted">- Index</small>
</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            Tabela de Notícias
        </h3>
    </div>

    <div class="card-body">
        @if(session()->has('mensagem'))
            <div class="alert alert-success">
                {{ session()->get('mensagem') }}
            </div>
        @endif

        <a href="/noticias/create" class="btn btn-primary mb-5">Nova Notícia</a>
    
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Ações</th>
                    <th>Título</th>
                    <th>Status</th>
                    <th>Data Publicação</th>
                    <th>Imagem</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($noticias as $noticia)
                    <tr>
                        <td>
                            <a href="/noticias/{{ $noticia->id }}/edit" class="btn btn-primary btn-sm">Editar</a>
                            <form action="/noticias/{{ $noticia->id }}" class="d-inline-block" method="POST" onSubmit="confirmarExclusao(event)">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Excluir</button>
                            </form>
                        </td>
                        <td>{{ $noticia->titulo }}</td>
                        <td>{{ $noticia->status_formatado }}</td>
                        <td>{{ $noticia->data_publicacao->format("d/m/Y") }}</td>
                        <td><img src="{{ $noticia->imagem}}" height="40px"></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        {{ $noticias->links() }}
    </div>
</div>
@stop
````

# Fazendo a view de edição ser a mesma da view de criação

O ideal é termos apenas uma view para edição e para criação, para não termos que ficar sempre alterando em vários locais.

Faça com que o conteúdo da view edit seja

````php
@extends('adminlte::page')

@section('title', 'Notícias')

@section('content_header')
<h1 class="m-0 text-dark"><i class="fas fa-home"></i> Notícias
    <small class="text-muted">- Formulário</small>
</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            Formulário de Notícias
        </h3>
    </div>

    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <p><strong>Erro ao realizar esta operação</strong></p>
                <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
            </div>
        @endif

        @if (isset($noticia))
            <form action="/noticias/{{ $noticia->id }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
        @else
            <form action="/noticias" method="POST" enctype="multipart/form-data">
        @endif
        
            @csrf

            <div class="form-group">
                <label for="titulo">Título</label>
                <input type="text" name="titulo" placeholder="Digite o título da notícia" class="form-control" value="{{ isset($noticia) ? $noticia->titulo : '' }}">
            </div>

            <div class="form-group">
                <label for="conteudo">Conteúdo</label>
                <textarea name="conteudo" placeholder="Digite o conteúdo da notícia" class="form-control" rows="5">{{ isset($noticia) ? $noticia->conteudo : '' }}</textarea>
            </div>

            <div class="form-group">
                <label for="imagem">Imagem Destaque</label>
                <input type="file" name="imagem"/>
                @if (isset($noticia) && $noticia->imagem)
                    <img src="{{ $noticia->imagem }}" alt="" height="100px" class="d-block">
                @endif
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" class="form-control">
                    <option value="A" {{ isset($noticia) && $noticia->status == "A" ? "selected='selected'" : "" }}>Ativo</option>
                    <option value="I" {{ isset($noticia) && $noticia->status == "I" ? "selected='selected'" : "" }}>Inativo</option>
                </select>
            </div>

            <div class="form-group">
                <label for="data_publicacao">Data da Publicação</label>
                <input type="text" name="data_publicacao" class="form-control" data-provide="datepicker" data-date-language="pt-BR" value="{{ isset($noticia) ? $noticia->data_publicacao->format("d/m/Y") : '' }}">
            </div>

            <button type="submit" class="btn btn-primary">Salvar</button>

        </form>
        
        <div class="mt-5">
            <h4 class="mb-2">Comentários:</h4>
            @if (isset($noticia))
                @foreach($noticia->comentarios as $comentario)
                    {{ $comentario->conteudo }} <hr/>
                @endforeach
            @endif
        </div>
    </div>
</div>
@stop
````

# Middleware

O middleware fornece um mecanismo conveniente para inspecionar e filtrar solicitações HTTP que entram em seu aplicativo. Por exemplo, o Laravel inclui um middleware que verifica se o usuário do seu aplicativo está autenticado. Se o usuário não estiver autenticado, o middleware redirecionará o usuário para a tela de login do seu aplicativo. No entanto, se o usuário for autenticado, o middleware permitirá que a solicitação prossiga no aplicativo.

https://laravel.com/docs/8.x/middleware#introduction

Vamos utilizar a view auth em todas as rotas que precisam ter o usuário autenticado para a utilização

Faça com que o conteúdo do arquivo de rotas seja

````php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoticiaController;


Auth::routes();

Route::get('/', function () {
    return redirect('/home');
});

Route::middleware('auth')->group(function() {
    Route::resource('noticias', NoticiaController::class);
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});
````
