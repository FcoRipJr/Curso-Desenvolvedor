# Props

Props é um atributo Vuejs personalizado para passar dados de componentes pais para filhos. Isso é muito importante para lembrar: você não poderá compartilhar dados entre componentes usando props, a menos que sua intenção seja passar os dados de um componente pai para um componente filho.

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <div id="app">
        <botao texto='Clique em mim' estilo="background-color: red; color: #fff"></botao>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <script>
        Vue.component('botao', {
            props: ['texto', 'estilo'],
            data: function () {
                return {}
            },
            template: '<button :style="estilo">{{ texto }}</button>'
        })

        var app = new Vue({
            el: '#app',
            data: {}
        })
    </script>
</body>
</html>
````

# Dados Computados (computed)

Expressões dentro de templates são muito convenientes, mas são destinadas a operações simples. Colocar muita lógica neles pode fazer com que fiquem inchados e que a sua manutenção fique mais complicada. Por exemplo:

````html
<div id="app">
  {{ nome }} = {{ nome.split('').reverse().join('') }}
</div>
````

````html
<script>
    var app = new Vue({
        el: '#app',
        data: {
            nome: 'Full Stack'
        }
    })
</script>
````

- split(''): transforma uma string em um array, ou seja, separa cada letra em uma posição do array.
- reverse(): inverte a ordem do array.
- join(''): transforma o array em string.

Neste ponto, o template não é mais tão simples e declarativo. Você tem que olhá-lo por alguns segundos antes de entender que ele exibe o valor de message na ordem reversa. O problema é agravado quando se deseja incluir uma mensagem na ordem reversa em mais algum lugar do template, gerando-se repetições de código.

Por isso que, para qualquer lógica mais complexa, usamos dados computados (computed properties no inglês, traduzidos como “dados” pois, durante a utilização em templates, se parecem efetivamente com propriedades definidas em data).

````html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <div id="app">
        {{ nome }}  =  {{ nomeInvertido }}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                nome: 'Full Stack'
            },
            computed:{
                nomeInvertido:function(){
                    return this.nome.split('').reverse().join('');
                }
            }
        })
    </script>
</body>
</html>
````
Você deve estar se perguntando, não posso usar o ``method`` para a função inversa? Pode e não pode (rsrs).

````html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <div id="app">
        {{ nome }} +  {{ nomeInvertido() }}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                nome: 'Full Stack'
            },
            methods:{
                nomeInvertido:function(){
                    return this.nome.split('').reverse().join('');
                }
            }
        })
    </script>
</body>
</html>
````
## Computado x Método

Vamos entender quando utilizar métodos e quando utilizar propriedade computada.

Vamos criar a propriedade computada ``aleatorio``.
````html
    <div id="app">
        <div>Aletórios com a propriedade computed</div>
        <div>{{ aleatorio }}</div>
        <div>{{ aleatorio }}</div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                numero: 10
            },
            computed: {
                aleatorio:function(){
                    let al= Math.floor(Math.random()*10);
                    return this.numero+" + "+ al+" = "+(this.numero+al);
                }
            }
        })
    </script>
````
![image](https://user-images.githubusercontent.com/84885503/141170373-e7411012-8ac4-414c-a01f-b1eb0f77dac4.png)

Veja que os valores irão aparecer iguais para as duas ``div``, ou seja,

````html
        <div>Aletórios com a propriedade computed</div>
         <!-- executou -->
        <div>{{ aleatorio }}</div>
          <!-- pegou o resultado existente -->
        <div>{{ aleatorio }}</div>
````
Vamos fazer a mesma coisa em método para entender a diferença.

````html
    <div id="app">
        <div>Aletórios com a propriedade computed</div>
        <div>{{ aleatorio }}</div>
        <div>{{ aleatorio }}</div>
        <div>Aleatório com a propriedade methods</div>
        <div>{{ aleatorioFuncao() }}</div>
        <div>{{ aleatorioFuncao() }}</div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                numero: 10
            },
            computed: {
                aleatorio:function(){
                    let al= Math.floor(Math.random()*10);
                    return this.numero+" + "+ al+" = "+(this.numero+al);
                }
            },
            methods: {
                aleatorioFuncao:function(){
                    let al= Math.floor(Math.random()*10);
                    return this.numero+" + "+ al+" = "+(this.numero+al);
                }
            }
        })
    </script>
````

![image](https://user-images.githubusercontent.com/84885503/141170427-ccc1c353-5620-417e-909d-0f6c02c6cfcf.png)

O ``methods`` executou a função duas vezes. Isso gera mais processamento, ou seja, toda vez que executar vai gerar mais processamento.

Claro que o exemplo é um processamento básico mas quando você tem, por exemplo, um processamento de array grande, já vai interferir na performace.

Utilizando propriedade computada irá executar quando há uma mudança de propriedade, quando não há mudança não faz diferença. Veja que no ``computed`` os valores são iguais porque ele guardou no cache, como ``aleatorio`` só depende de ``numero`` ele só mudará quando mudar o número por ser a única propriedade associada no ``computed``. 

No ``methods`` os valores são diferentes pois ele executou todas as vezes que foi invocado.

Falando sobre performance, se você precisa de algo que só será gerado uma vez usa a propriedade computada porque evita processamento extra desnecessário. Mas se você precisa que algo seja realmente executado quando for chamado usa o método.

# Observadores (watchers)

Enquanto dados computados são mais adequados na maioria dos casos, há momentos em que um observador personalizado é necessário. Por isso o Vue fornece uma maneira mais genérica para reagir a alterações de dados, o watch. Isto é particularmente útil quando se precisa executar operações assíncronas ou operações complexas antes de responder a uma alteração de dados.

O watch é um olheiro que ficará olhando uma propriedade específica que irá execitar algo. A diferença entre watchers e propriedade computada é que a propriedade computada cria uma propriedade propriamente dita que é executada apenas quando precisa exibir a propriedade. O watch não cria uma nova propriedade, ele observa uma determinada propriedade que já existe e vai tomar uma ação quando essa propriedade for modificada.

O watch é recomendado quando tem algo assicrono acontecendo, ou seja, alguma requisição ao webService, um alt complete de uma página de busca, onde você digita e ele traz algo relacionado ao assunto (uma pesquisa do assunto).

````html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <div id="app">
        <input v-model="texto"> <span>{{ aviso }}</span>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                texto:'',
                aviso:'',
                timer:null
            },
            methods:{
                ocultarDigitando:function(){
                    this.aviso = '';
                }
            },
            watch: {
                texto: function() {
                    this.aviso = 'Digitando...';
                    
                    // zerar o timer
                    if (this.timer != null){
                        clearTimeout(this.timer);
                    }

                    //inicia o timer em 1seg
                    this.timer = setTimeout(this.ocultarDigitando,1000);
                }
            }
        })
    </script>
</body>
</html>
````

# Ciclo de Vida da Instância Vue

Cada instância Vue passa por uma série de etapas em sua inicialização - por exemplo, é necessário configurar a observação de dados, compilar o template, montar a instância no DOM, atualizar o DOM quando os dados forem alterados. Ao longo do caminho, ocorrerá a invocação de alguns gatilhos de ciclo de vida, oferecendo a oportunidade de executar lógicas personalizadas em etapas específicas.

## Ciclo de inicialização:

### beforeCreate

Invocado sincronamente logo após a instância ser inicializada, antes da observação dos dados e configuração de eventos/observadores (antes do elemento ser criado).

### created

Invocado sincronamente após a instância ser criada. Nesse estágio, a instância finalizou o processamento das opções, o que significa que a observação de dados, dados computados, métodos e callbacks de observadores/eventos foram inicializados. Entretanto, a fase de montagem não foi iniciada, e a propriedade $el não estará disponível ainda. Pode-se dizer que nesse ponto o ``data`` e ``events`` estarão disponíveis para acesso (quando o elemento foi criado na memória, ou seja, o elemento foi criado mais ainda não foi renderizado).

Eles permitem executarmos ações antes do componente ser adicionado no DOM, ou seja, antes dele ser adicionado na página.

## Ciclos de inserção na página:

### beforeMount

Invocado logo antes de a montagem iniciar: a função render está prestes a ser invocada pela primeira vez (antes de renderizar) .

### mounted

Invocado após a instância ser montada, onde ``el``, passado para o Vue.createApp({}).mount(), é substituído pelo recém criado ``app.$el``. Se a instância raiz for montada em um elemento presente no documento, ``app.$el`` também estará no documento quando o mounted for invocado (o elemento foi renderizado).

## Ciclos de comparação e atualização:

### beforeUpdate

Invocado quando há mudança nos dados e antes de o DOM ser atualizado. Esse é um bom momento para acessar a DOM existente antes de uma atualização, por exemplo, para remover escutas de evento adicionadas manualmente. Esse ciclo de vida é disparado após alguma atualização no ``data`` e antes do DOM ser atualizado, ou seja, antes de atualizar o componente na página.

### updated

Invocado após uma mudança nos dados causar uma re-renderização do DOM.

O DOM do componente terá sido atualizado quando esse gatilho for invocado, de forma que você pode realizar operações dependentes do DOM neste gatilho. Entretanto, na maioria dos casos você deve evitar mudanças de estado dentro do gatilho. Para reagir à mudanças de estado, normalmente é melhor usar uma dado computado ou um observador.

## Exemplos de uso:

beforeCreate e created: Geralmente usamos os ciclos de inicialização para setarmos valores para nosso componente, ou seja, algo que precisamos inicializar.

beforeMount e mounted: O beforeMount em geral é pouco usado, geralmente usamos o mounted para buscar dados de uma API ou servidor e setar no estado do componente.

beforeUpdate e updated: Geralmente usamos quando precisamos saber quando o componente foi atualizado, para realizar algum debug, profile ou buscar informações atualizados de um servidor.

## Exemplo

````html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <div id="app">
        <input type="text" v-model="nome">
    </div>

    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                nome: null
            },
            beforeCreate: function() {
                alert('Executou o beforeCreate')
            },
            created: function() {
                alert('Executou o created')
            },
            beforeMount: function() {
                alert('Executou o beforeMount')
            },
            mounted: function() {
                alert('Executou o mounted')
            },
            beforeUpdate: function() {
                alert('Executou o beforeUpdate')
            },
            updated: function() {
                alert('Executou o updated')
            }
        })
    </script>
</body>
</html>
````

# Axios

Há diversos momentos quando você está desenvolvendo uma aplicação Web que podem necessitar consumir e exibir dados de uma API.   Há várias maneiras de se fazer isso, mas a maneira mais popular é usando axios, um cliente HTTP baseado em Promises, ou seja, são requisições ao webservice externo, um API externo que você deseja importar para a sua aplicação.

A Promise é um objeto usado para processamento assíncrono. Um Promise (de "promessa") representa um valor que pode estar disponível agora, no futuro ou nunca.

Uma promessa pendente pode se tornar realizada com um valor ou rejeitada por um motivo (erro). Quando um desses estados ocorre, o método ``then`` do Promise é chamado, e ele chama o método de tratamento associado ao estado (``rejected`` ou ``resolved``).  Se a promessa foi realizada ou rejeitada quando o método de tratamento correspondente for associado, o método será chamado, desta forma não há uma condição de competição entre uma operação assíncrona e seus manipuladores que estão sendo associados.


* Por baixo dos panos faz requisições Ajax no browser via XMLHttpRequests;
* Faz requisições http no Node.js;
* Suporta a API de Promises;
* Intercepta requisições e respostas (request & response);
* Cancela requisições;
* Transforma os dados em JSON automaticamente;
* No lado cliente suporta a proteção contra XRSF;
* Transforma os dados da requisição e da resposta.

## CDN
````html
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
````

````html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <div id="app">
        <input type="text" v-model="cep">
        <button @click="pesquisarCep">Pesquisar CEP</button>
        <p v-if="dadosCep">
            Endereço: {{ dadosCep.logradouro }} - {{ dadosCep.bairro }} - {{ dadosCep.localidade }}/{{ dadosCep.uf }}
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                cep: null,
                dadosCep: null
            },
            methods: {
                pesquisarCep: function() {
                    if (this.cep) {
                        axios.get('https://viacep.com.br/ws/' + this.cep + '/json/')
                            .then(({data}) => {
                                this.dadosCep = data
                            })
                    } else {
                        alert("Digite um CEP primeiro")
                    }
                }
            }
        })
    </script>
</body>
</html>
````

# Vuex

O Vuex é um padrão de gerenciamento de estado + biblioteca para aplicações Vue.js. Ele serve como um store centralizado para todos os componentes em uma aplicação, com regras garantindo que o estado só possa ser mutado de forma previsível. 

link: https://vuex.vuejs.org/ptbr/

## O que é um "Padrão de Gerenciamento do Estado"?

É uma aplicação independente com as seguintes partes:

- O estado (**state**), que é a fonte da verdade que direciona nossa aplicação;
- A **view**, que é apenas um mapeamento declarativo do estado;
- As **ações** (actions), que são as possíveis maneiras pelas quais o estado pode mudar em reação às interações dos usuários da view.

## Mutações

A única maneira de realmente mudar de estado em um store Vuex é por confirmar (ou fazer commit de) uma mutação. As mutações do Vuex são muito semelhantes aos eventos: cada mutação tem uma cadeia de caracteres tipo e um manipulador. Para invocar um manipulador de mutação, você precisa chamar ``store.commit`` com seu tipo.

````html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <div id="app">
        <input type="text" v-model="nome">
        {{ nome }}
        <saudacao></saudacao>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <script src="https://unpkg.com/vuex@2.0.0"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
    <script>

        const store = new Vuex.Store({
            state: {
                nome: 'Adéliton Fernandes'
            },
            
            mutations: {
                alterarNome(state, novoNome) {
                    state.nome = novoNome
                }
            }
        })

        Vue.component('saudacao', {
            computed: {
                nome: function() {
                    return this.$store.state.nome
                }
            },
            template: '<p>Olá {{ nome }}</p>'
        })

        var app = new Vue({
            el: '#app',
            store: store,
            data: {
                nome: null
            },
            watch: {
                nome: function(novoNome) {
                    this.$store.commit('alterarNome', novoNome)
                }
            }
        })
    </script>
</body>
</html>
````
