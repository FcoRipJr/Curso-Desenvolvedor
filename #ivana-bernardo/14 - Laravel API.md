# API

As APIs são um conjunto de padrões que fazem parte de uma interface e que permitem a criação de plataformas de maneira mais simples e prática para desenvolvedores. A partir de APIs é possível criar softwares, aplicativos, programas e plataformas diversas. Por exemplo, apps desenvolvidos para celulares Android e iPhone (iOS) são criados a partir de padrões definidos e disponibilizados pelas APIs de cada sistema operacional.

Nós utilizaremos uma API para acessar os recursos do nosso sistema de notícias a partir do vueJS.

# JSON

JSON é basicamente um formato leve de troca de informações/dados entre sistemas. Mas JSON significa JavaScript Object Notation, ou seja, só posso usar com JavaScript correto? Na verdade não e alguns ainda caem nesta armadilha.

O JSON além de ser um formato leve para troca de dados é também muito simples de ler. Mas quando dizemos que algo é simples, é interessante compará-lo com algo mais complexo para entendermos tal simplicidade não é? Neste caso podemos comparar o JSON com o formato XML.

````xml
<mensagem>
    <para>João</para>
    <de>Maria</de>
    <titulo>Lembrete</titulo>
    <conteudo>Não se esqueça do nosso encontro no final de semana</conteudo>
</mensagem>
````

````json
{
    "mensagem": {
        "para": "João",
        "de": "Maria",
        "titulo": "Lembrete",
        "conteudo": "Não se esqueça do nosso encontro no final de semana"
    }
}
````

# Rotas

O arquivo de rotas indicado para a criação das rotas dentro do Laravel é o ``routes/api.php``

Abra esse arquivo e vamos criar as rotas responsáveis pelo CRUD das notícias.

````php
Route::resource('/noticias', \App\Http\Controllers\Api\NoticiaController::class);
````

Precisaremos criar um Controller novo para as rotas de API, visto que o Controller que já temos (``NoticiaController``) já possui métodos que fazem uso do MVC. 

````sh
php artisan make:controller Api/NoticiaController
````

Um novo Controller será criado no diretório ``app/Http/Controllers/Api/NoticiaController.php``

# Método index

Vamos criar o método index dentro do Controller. Assim como no Controller MVC, o método index do Controller da API irá listar todas as notícias cadastradas. Basta fazermos da seguinte forma e o Laravel já irá retornar os dados em formato de json.

````php
    public function index()
    {
        return Noticia::all();
    }
````

# Testando a API

Como o método index é renderizado a partir de um método GET, podemos fazer teste dessa rota simplesmente usando o navegador. 

``http://meu-projeto.teste/api/noticias``

Para os métodos POST, PUT e DELETE será necessário utilizamos uma ferramenta para testar os endpoints. Utilizaremos o [POSTMAN](https://www.postman.com/downloads/?utm_source=postman-home)

# Método show

O método show será responsável por exibir uma única notícia. 

````php
    public function show(Noticia $noticia)
    {
        return $noticia;
    }
````

# Método store

O método store irá criar uma notícia nova e irá retornar para o client

````php
    public function store(Request $request)
    {
        $dados = $request->all();
        $dados['imagem'] = UploadService::upload($dados['imagem']);
        
        return Noticia::create($request->all());
    }
````

# Método update

O método update irá atualizar a notícia e irá retorná-la atualizada para o client

````php
    public function update(Request $request, Noticia $noticia) 
    {
        $noticia->update($request->all());
        
        if ($request->imagem) {
             $dados['imagem'] = UploadService::upload($dados['imagem']);
        }
        return $noticia;
    }
````

# Método destroy

O método destroy irá excluir a notícia e irá retornar true ou false.

````php
    public function destroy(Noticia $noticia)
    {
        return $noticia->delete();
    }
````

# Controller Completo

Portanto o Controller completo irá ter o seguinte conteúdo

````php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Noticia;
use Illuminate\Http\Request;
use App\Services\UploadService;


class NoticiaController extends Controller
{
    public function index()
    {
        return Noticia::all();
    }

    public function store(Request $request)
    {
        $dados = $request->all();
        $dados['imagem'] = UploadService::upload($dados['imagem']);
        
        return Noticia::create($request->all());
    }

    public function update(Request $request, Noticia $noticia) 
    {
        $dados = $request->all();

        if ($request->imagem) {
            $dados['imagem'] = UploadService::upload($dados['imagem']);
        }
        $noticia->update($dados);
        
        return $noticia;
    }

    public function destroy(Noticia $noticia)
    {
        return $noticia->delete();
    }

    public function show(Noticia $noticia)
    {
        return $noticia;
    }
}

````
