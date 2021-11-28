# Vue CLI

Vue CLI é uma ferramenta de linha de comando feita pela comunidade do Vue para facilitar ainda mais a inicialização de uma nova aplicação Vue, com ela podemos gerar nossa aplicação a partir de templates oficiais (incluindo integração com webpack, etc), diminuindo o tempo de configuração de ambiente.

De maneira simples, Webpack é um empacotador de módulos para aplicações javascript. O webpack permite a você escrever módulos e empacotar todos eles em um ou mais pacotes. Além do javascript, ele também pode incluir outros tipos de arquivos como css, font, image, HTML e etc.

## Instalação do NODE

````sh
cd ~
curl -sL https://deb.nodesource.com/setup_14.x -o nodesource_setup.sh
sudo bash nodesource_setup.sh
sudo apt install nodejs
node -v
v14.2.0
````

## Instalação do Vue CLI

NPM é o método de instalação recomendado para construção de aplicações em larga escala com o Vue. Ele combina perfeitamente com empacotadores de módulos, tais como Webpack ou Browserify (permite usar o padrão de módulos do NodeJS no navegador. Defini-se as dependências e depois o Browserify empacota tudo isso em apenas um arquivo JS, limpo e estruturado).

Execute o comando abaixo
````sh
npm install -g @vue/cli
````
Caso apareça um erro de permissão, retentar com sudo

Verifique se a instalação ocorreu corretamente
````sh
vue --version
````

## Criação de Projeto

````sh
vue create nome-do-projeto
````
(Esse comando não funciona muito bem no ``git bash``, no git bash use ``winpty vue.cmd create nome-do-projeto``)

### Preset

Vamos "setar" todas as configurações manualmente ``Manually select features``
````sh
 (*) Choose Vue version
 (*) Babel
 ( ) TypeScript
 ( ) Progressive Web App (PWA) Support
 (*) Router
 (*) Vuex
 (*) CSS Pre-processors
 ( ) Linter / Formatter
 ( ) Unit Testing
 ( ) E2E Testing
````

Pela didática do curso não vamos utilizar o Linter, porém é recomendado para projetos em equipe. 

Como parte dos plugins e components não estão preparados para o Vue3 ainda, vamos utilizar o Vue2
````sh
> 2.x
  3.x
````

Na parte em que é questionado o modo para o vue router, vamos utilizar o padrão ``Y``, pois utilizaremos ``history mode``.

O pre-processor CSS que utilizaremos é o ``Sass/SCSS (with node-sass)`` 

Para termos um projeto bem organizado, manteremos os arquivos de configuração em um arquivo dedicado ``(In dedicated config files)``.

Não precisamos salvar essas configurações para utilizar em outros projetos futuros.

### Inicialização

Após a criação do projeto, você pode abri-lo no vscode e execurar o comando de desenvolvimento

````sh
cd nome-do-projeto
````

````sh
code .
````

````sh
npm run serve
````

Agora pode abrir esse projeto no navegador
````sh
http://localhost:8080/
````

# Vue Router

Para se construir uma Single Page Application, um dos componentes essenciais é o Router, e no caso do Vue seria o Vue-Router. Ele faz todo o gerenciamento de qual conteúdo deve ser mostrado na tela com base na url que o usuário está acessando, e esse controle se for bem montado, da uma dinâmica grande para a aplicação, tornando melhor a experiência do usuário, a navegação no sistema e entre outros benefícios.

O arquivo de rotas no projeto do vue cli fica em 
````
src/router/index.js
````

## Entendendo o arquivo de rotas

A importação dos componentes é feita utilizando a seguinte sintaxe
````js
import Home from '../views/Home.vue'
````

Cada componente será renderizado através de uma rota. As rotas são um array de objetos e, neste caso, são chamados de ``const routes``

````js
const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/about',
    name: 'About',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '../views/About.vue')
  }
]
````

Portanto, por padrão, neste projeto, temos duas rotas. A rota ``/`` e a rota ``/about``. A rota ``/`` renderiza o componente ``Home`` incluído com o ``import``. Já a rota ``/about`` renderiza o componente ``About.vue`` importado em tempo de execução.

Vamos excluir essas rotas e utilizar rotas que ainda iremos criar. Portanto, faça com que o conteúdo do arquivo routes seja. 

````js
import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Inicio'
  },
  {
    path: '/noticias',
    name: 'Noticias'
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router

````

Agora nosso arquivo de rotas possui duas rotas que não apontam para componente nenhum, ainda. 

# Criação de Componente

Primeiro devemos diferenciar um componente e uma view completa. Componentes são instâncias reutilizáveis do Vue com um nome: por exemplo, ``<button-counter>``. Podemos usar esses componentes como um elemento personalizado dentro da instância Vue raiz criada com new Vue, como se fosse uma tag do HTML. Já uma view é um código HTML completo, podendo ter vários componentes vue dentro de uma mesma view. Também são chamados de ``pages``. 

Vamos criar um arquivo novo dentro de ``src/views`` chamado ``Inicio.vue``.

Vamos utilizar o emmet para criação do esqueleto de um arquivo vue. Digite ``vue`` e aperte tab.

Esse é o esqueleto padrão de um arquivo vue

````html
<template>
  
</template>

<script>
export default {

}
</script>

<style>

</style>
````

Na tag ``template`` colocaremos o código HTML que irá ser usado para renderizar o componente. Na tag ``script`` colocaremos o código JavaScript referente ao componente e na tag ``style`` colocaremos o código CSS, podendo fazer uso do SASS. 

# Atualização da rota com o componente recem criado

````diff
import Vue from 'vue'
import VueRouter from 'vue-router'
+import Inicio from '../views/Inicio.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Inicio',
+    component: Inicio
  },
  {
    path: '/noticias',
    name: 'Noticias'
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
````

# Passagem de parâmetros pela rota

Apenas para aprendizado, vamos passar parâmetros pela rota.

````diff
import Vue from 'vue'
import VueRouter from 'vue-router'
import Inicio from '../views/Inicio.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Inicio',
    component: Inicio
  },
  {
    path: '/noticias',
    name: 'Noticias'
  },
+  {
+    path: '/noticias/:id',
+    name: 'TesteParametro',
+    component: Inicio
+  }
]

// se routes = '/noticias/:id', carregar o Inicio.vue

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
````

Agora vamos exibir esse parâmetro passado na nossa view ao nosso componente ser montado

````diff
<template>
  
</template>

<script>
+export default {
+    mounted: function() {
+        alert(this.$route.params.id)
+    }
+}
</script>

<style>

</style>
````

O ``mounted``quando o componente for criado para ser exibido irá rodar a função de ciclo de vida.

Agora acesse no navegador: ``http://localhost:8080/noticias/1``

Portanto, para acessar um parâmetro passado pela rota, precisamos do ``this.$route.params``.

# Axios dentro do Vue CLI

Primeiramente vamos instalar o pacote do Axios pelo NPM, para que o pacote esteja dentro do nosso projeto e não mais em um CDN

````sh
npm install --save axios
````

Agora, no nosso arquivo ``main.js``, vamos incluir o axios para fazermos uso posteriormente.

````diff
+ import axios from 'axios'
import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'

+ Vue.prototype.$http = axios
Vue.config.productionTip = false

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')

````

Vamos, portanto, fazer uso do axios dentro da nossa view

````diff
<template>
  
</template>

<script>

export default {
    mounted: function() {
        alert(this.$route.params.id)
+        this.$http.get('https://economia.awesomeapi.com.br/last/USD-BRL')
+            .then((response) => {
+                console.log(response)
+            });
    }
}
</script>

<style>

</style>
````

O ``this.$http`` foi definido nesta linha: ``Vue.prototype.$http = axios``

# Alternando entre rotas pelo componente vue

Para alternar entre rotas de dentro de um componente vue, você precisa da prop ``to`` e do componente ``<router-link>``. Onde ``to`` é o destino, no nosso exemplo: ``Ir para o Início``.

Exemplo:

````diff
<template>
+  <router-link to="/">Ir para o início</router-link>
</template>

<script>

export default {
    mounted: function() {
        //pega $route.params.id = id enviado pela rota
        alert(this.$route.params.id)
        this.$http.get('https://economia.awesomeapi.com.br/last/USD-BRL')
            .then((response) => {
                console.log(response)
            });
    }
}
</script>

<style>

</style>
````

# Exercício Prático

* Crie uma rota principal que tenha um componente que exiba dois botões, um com a informação "Dolar para Real" e outra "Real para Dólar". 
* Crie a seguinte rota ``/converter/:de/:para``
* O componente dessa rota deve exibir um input e um botão, ao clicar no botão deve exibir o valor digitado convertido para o valor desejado. 
