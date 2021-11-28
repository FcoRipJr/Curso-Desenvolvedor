# Request Validation

O Laravel possui uma classe específica para trabalhar com os dados enviados na requisição. Essa classe tem o nome de Request e fica no namespace \Illuminate\Http. Ela possui uma série de métodos que facilitam obtenção das informações enviadas pelo cliente. Podemos por exemplo fazer um item na request ser obrigatório.

Vamos primeiramente criar a Request
````sh
sudo docker-compose exec --user=flexdock php7 bash

cd noticias

php artisan make:request NoticiaRequest
````

Agora vamos usar a Request em nosso Controller, mudando o parâmetro ``Request $request`` para ``NoticiaRequest $request``. 

````php
use App\Http\Requests\NoticiaRequest;
...
public function store(NoticiaRequest $request)
{
   ...
}

...
public function update($noticia, NoticiaRequest $request) 
{
   ...
}
...
````

Agora vamos preparar a nossa Request para fazer validação de dados enviados na requisição

````php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NoticiaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'titulo' => 'required',
            'conteudo' => 'required',
            'status' => [
                'required',
                Rule::in(['A', 'I'])
            ],
            'data_publicacao' => 'date_format:d/m/Y',
            'imagem' => 'nullable|image'
        ];
    }
    
    public function messages()
    {
        return [
            'titulo.required' => 'O campo título é obrigatório',
            'conteudo.required' => 'O campo conteúdo é obrigatório',
            'status.required' => 'O campo status é obrigatório',
            'data_publicacao.required' => 'O campo data da publicação é obrigatório',
            'imagem.image' => 'O campo imagem deve ser preenchido com uma imagem',
            'status.in' => 'O status só pode ser Ativo ou Inativo'
        ];
    }
}
````


Se o método authorize() retornar false, uma resposta HTTP com um código de status 403 será automaticamente retornada e o método do controlador não será executado.

Para usar a lógica de autorização para a solicitação em outra parte do seu aplicativo, pode simplesmente retornar true no método authorize().

### required

Obrigatório - O campo em validação deve estar presente nos dados de entrada e não deve estar vazio.

### Rules

use Illuminate\Validation\Rule;

Usado quando uma regra requer vários argumentos.

````php
 Rule::in(['A', 'I'])
````

## Exibindo erros nas views create e edit 

Agora vamos exibir os erros nas views

Arquivo create.php e edit.php
````php
...

<div class="container pt-5">

    @if(session()->has('mensagem'))
        <div class="alert alert-success">
            {{ session()->get('mensagem') }}
        </div>
    @endif

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

    <form action="/noticias" method="POST" enctype="multipart/form-data">

...
````

Validação em português

Na pasta docker-flex

``sudo docker-compose exec --user=flexdock php7 bash``

``cd noticias``

``composer require lucascudo/laravel-pt-br-localization --dev``

``php artisan vendor:publish --tag=laravel-pt-br-localization``

A validação em encontra em: ``/noticias/resources/lang/pt_BR/validation.php``

Uma listagem completa de regras de validação [pode ser encontrada aqui](https://laravel.com/docs/8.x/validation#available-validation-rules).

# Accessor

Os Accessors servem para juntar múltiplos atributos em um ou apenas formatar algum atributo. É um recurso que permite alterar o valor de alguma propriedade depois de sair do banco de dados. Podemos usar para formatação, alteração de valores e etc.

Para definir um acessador, crie um método get``{Attribute}``Attribute em seu modelo, onde {Attribute} é o nome da coluna que você deseja acessar.

Vamos alterar a nossa model para o seguinte conteúdo

````php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
    use HasFactory;

    protected $table = 'noticias';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function getStatusFormatadoAttribute()
    {
        if ($this->status == "A") {
            return "Ativo";
        } else if ($this->status == "I") {
            return "Inativo";
        }
    }
}

````

Agora toda vez que chamarmos o atributo ``status_formatado`` da Model, irá retornar Ativo ou Inativo. Para acessar o valor do Accessor basta chamá-lo da seguinte forma na view index:

````html
<td>{{ $noticia->status_formatado }}</td>
````

# Atributo dates

Toda vez que temos uma data como coluna no nosso banco de dados, podemos usar o atributo dates da Model para essa coluna automaticamente ser considerada um objeto da classe Carbon:

````php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
    use HasFactory;

    protected $table = 'noticias';
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $dates = ['created_at', 'updated_at', 'data_publicacao'];

    public function getStatusFormatadoAttribute()
    {
        if ($this->status == "A") {
            return "Ativo";
        } else if ($this->status == "I") {
            return "Inativo";
        }
    }
}
````

Agora podemos atualizar na view index.

````html
@foreach($noticias as $noticia)
    <tr>
        <td style="width: 200px">
            <a href="/noticias/edit/{{ $noticia->id }}" class="btn btn-primary btn-sm">Editar</a>
            <form action="/noticias/{{ $noticia->id }}" method="POST" class="d-inline-block">
                @method('DELETE')
                @csrf
                <button class="btn btn-danger btn-sm">Excluir</button>
            </form>
        </td>
        <td>{{ $noticia->titulo }}</td>
        <td>{{ $noticia->status_formatado }}</td>
        <td>{{ optional($noticia->data_publicacao)->format("d/m/Y") }}</td>
        <td>
            <img src="{{ $noticia->imagem }}" alt="" height="50px">
        </td>
    </tr>
@endforeach
````

Agora vamos alterar o Carbon da view edit.

````html
<input type="text" name="data_publicacao" class="form-control" data-provide="datepicker" data-date-language="pt-BR" value="{{ optional($noticia->data_publicacao)->format("d/m/Y") }}">
````

# Constantes na Model

Para atributos com valores definidos é uma ótima prática especificar todos os valores dentro da model através de constantes. Da seguinte forma:

````php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
    use HasFactory;

    const STATUS_ATIVO = "A";
    const STATUS_INATIVO = "I";
    const STATUS = [
        Noticia::STATUS_ATIVO => "Ativo",
        Noticia::STATUS_INATIVO => "Inativo"
    ];

    protected $table = 'noticias';
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $dates = ['created_at', 'updated_at', 'data_publicacao'];

    public function getStatusFormatadoAttribute()
    {
        return Noticia::STATUS[$this->status];
    }
}
````

Dessa forma se eu precisar puxar apenas as noticias ativas, o meu código ficará mais limpo.

````php
    public function index()
    {
        return view('noticias.index', [
            'noticias' => Noticia::where('status', Noticia::STATUS_ATIVO)->get()
        ]);
    }
````

# Templates na view

Para não repetirmos código na view, como incorporação de CSS e JavaScript, é possível utilizarmos templates e só injetarmos os templates em nossas views. 

Crie o arquivo de template dentro de uma nova pasta components com o nome master.blade.php
````sh
touch views/components/master.blade.php
````

O conteúdo desse arquivo deve ser o seguinte:
````html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'CRUD de Notícias' }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
</head>
<body>

    {{ $slot }}
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
</body>
</html>
````

Como o componente ``master.blade`` será renderizado em slots diferentes, vamos colocar um título = ``title`` para permitir uma injeção de um slot de "título", porém, se o título não for informado mostrará o título padrão ``CRUD de Notícias``.

Os slots são componentes rederizados e repetidos na variável {{ slot }}, ou seja, conterá o conteúdo que desejamos injetar no componente.

Agora em nossas views basta reutilizarmos este template fazendo uso da tag ``x-master``, onde o ``x`` é fixo e o ``master`` é o nome do arquivo template. Portanto a view ficará assim:

````html
<x-master title="Inserção">
    <div class="container mt-5">

        <a href="/noticias/create" class="btn btn-primary mb-5">Criar Nova Notícia</a>

        @if(session()->has('mensagem'))
            <div class="alert alert-success">
                {{ session()->get('mensagem') }}
            </div>
        @endif

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
                @foreach($noticias as $noticia)
                    <tr>
                        <td style="width: 200px">
                            <a href="/noticias/edit/{{ $noticia->id }}" class="btn btn-primary btn-sm">Editar</a>
                            <form action="/noticias/{{ $noticia->id }}" method="POST" class="d-inline-block">
                                @method('DELETE')
                                @csrf
                                <button class="btn btn-danger btn-sm">Excluir</button>
                            </form>
                        </td>
                        <td>{{ $noticia->titulo }}</td>
                        <td>{{ $noticia->status_formatado }}</td>
                        <td>{{ optional($noticia->data_publicacao)->format("d/m/Y") }}</td>
                        <td><img src="{{ $noticia->imagem }}" alt="" height="50px"></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</x-master>
````

Devemos fazer o mesmo para todas as outras views.

# Mutators
Os mutators funcionam parecido com os accessors. A diferença é que os mutators alteram os valores antes de ir para o banco de dados. É indicado para alterar formatações e etc.

Para definir um acessador, crie um método set``{Attribute}``Attribute em seu modelo, onde {Attribute} é o nome da coluna que você deseja acessar.

Vamos alterar nossa model para o seguinte conteudo:
````php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Noticia extends Model
{
    use HasFactory;

    const STATUS_ATIVO = "A";
    const STATUS_INATIVO = "I";
    const STATUS = [
        Noticia::STATUS_ATIVO => "Ativo",
        Noticia::STATUS_INATIVO => "Inativo"
    ];

    protected $table = 'noticias';
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $dates = ['created_at', 'updated_at', 'data_publicacao'];

    public function getStatusFormatadoAttribute()
    {
        return Noticia::STATUS[$this->status];
    }

    public function setDataPublicacaoAttribute($value)
    {
        $this->attributes['data_publicacao'] = Carbon::createFromFormat("d/m/Y", $value)->format("Y-m-d");
    }
}
````

Agora podemos tirar a formatação de data de dentro do nosso Controller

````php
...
public function store(NoticiaRequest $request)
{
    $dados = $request->all();
    $dados['imagem']->storeAs('public', $dados['imagem']->getClientOriginalName());
    $dados['imagem'] = '/storage/' . $dados['imagem']->getClientOriginalName();

    Noticia::create($dados);

    return redirect()->back()->with('mensagem', 'Notícia criada com sucesso!');
}
...
public function update($noticia, NoticiaRequest $request) 
{
    $noticia = Noticia::find($noticia);

    $dados = $request->all();
    if ($request->imagem) {
        $dados['imagem']->storeAs('public', $dados['imagem']->getClientOriginalName());
        $dados['imagem'] = '/storage/' . $dados['imagem']->getClientOriginalName();    
    }
    
    $noticia->update($dados);

    return redirect()->back()->with('mensagem', 'Notícia atualizada com sucesso!');
}
...
````

# Paginação

Vamos adicionar a paginação na tabela de visualização dos nossos dados.

Vamos alterar o método index do nosso controller.

````php
    public function index()
    {
        return view('noticias.index', [
            'noticias' => Noticia::where('status', Noticia::STATUS_ATIVO)->paginate(10)
        ]);
    }
````

Agora vamos exibir os links da paginação na nossa view index.blade.php

````html
...
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $noticias->links() }}
    </div>

</x-master>
````

Por último vamos especificar para o Laravel que gostaríamos de usar a paginação usando Bootstrap no arquivo ``app/Providers/AppServiceProvider.php``

````php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
    }
}

````

# Criando Services

Um Service é uma camada extra no desenvolvimento de software. Atualmente trabalhamos com Models, Views e Controllers. Para uma funcionalidade nova no seu software você deve criar um Service que implementa essa funcionalidade, de forma genérica e simples. Se mais de uma funcionalidade for precisar ser criada, mais de um Service deverá ser criado. 

Vamos criar um Service que faz upload de imagem.

Para criação de Service não há comando no Artisan, portanto deve ser feito manualmente. 

1. Crie uma pasta chamada Services dentro de app.
2. Crie um novo arquivo dentro da pasta Services. Esse arquivo será a nossa classe. O nome do arquivo deve ser: ``UploadService.php``. Tenha sempre o costume de, em todos os services, você colocar o sufixo Service.
3. Vamos criar a classe com o mesmo nome do arquivo.
````php
<?php

namespace App\Services;

class UploadService
{
    
}
````
4. Agora vamos criar um método que fará o upload da imagem. Os métodos dos services, por padrão, devem ser genéricos, ou seja, devem funcionar de forma de seja reutilizavel. Tem dois padrões bastantes comuns para as classes services. 1. Usando com dependency injection; 2. Métodos estáticos. Neste curso veremos o padrão por métodos estáticos. Vamos criar o método que faz o upload da imagem.
````php
    public static function upload($arquivo)
    {
        $arquivo->storeAs('public', $arquivo->getClientOriginalName());
        return '/storage/' . $arquivo->getClientOriginalName();
    }
````
5. Por fim vamos atualizar o Controller para utilizar o Service recém criado.
````php
use App\Services\UploadService;
...
    public function store(NoticiaRequest $request)
    {
        $dados = $request->all();
        $dados['imagem'] = UploadService::upload($dados['imagem']);

        Noticia::create($dados);

        return redirect()->back()->with('mensagem', 'Notícia criada com sucesso!');
    }

    ...

    public function update($noticia, NoticiaRequest $request) 
    {
        $noticia = Noticia::find($noticia);

        $dados = $request->all();
        if ($request->imagem) {
            $dados['imagem'] = UploadService::upload($dados['imagem']);
        }
        
        $noticia->update($dados);

        return redirect()->back()->with('mensagem', 'Notícia atualizada com sucesso!');
    }
````

# Recebendo o objeto da model via rotas

Nos métodos de edit, update e delete nós podemos receber por parâmetro diretamente o objeto da model. Assim não precisamos usar o método find para achar o objeto a partir de um ID.

````php
    public function edit(Noticia $noticia)
    {
        return view('noticias.edit', [
            'noticia' => $noticia
        ]);
    }

    public function update(Noticia $noticia, NoticiaRequest $request) 
    {
        $dados = $request->all();
        if ($request->imagem) {
            $dados['imagem'] = UploadService::upload($dados['imagem']);
        }
        
        $noticia->update($dados);

        return redirect()->back()->with('mensagem', 'Notícia atualizada com sucesso!');
    }

    public function destroy(Noticia $noticia)
    {
        $noticia->delete();

        return redirect()->back()->with('mensagem', 'Notícia excluida com sucesso!');
    }
````
