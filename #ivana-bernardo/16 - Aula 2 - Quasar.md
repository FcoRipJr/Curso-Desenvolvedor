# Quasar Framework

O Quasar Framework é um framework de código aberto baseado em Vue.JS para construir aplicativos, com uma única base de código, e implantá-lo na Web como SPA, PWA, SSR, para um aplicativo móvel, usando Cordova para iOS e Android, e para um App Desktop, usando Electron para Mac, Windows e Linux.

## Teste os componentes

https://quasar.dev/vue-components/

## Instalação

````sh
npm install -g @quasar/cli
````

## Criação do Projeto

````sh
quasar create nome-do-projeto
````

````sh
quasar create noticias-app
````
(Para evitar problemas de compatibilidade, esse comando não deve ser usado no ``git bash``, você deve usar o cmd, caso esteja no Windows)

````sh
? Project name (internal usage for dev) noticias-app                                                                    
? Project product name (must start with letter if building mobile apps) Noticias App                                    
? Project description Nosso aplicativo de noticias                                                                      
? Author Adéliton Fernandes <adeliton@live.com>                                                                         
? Pick your CSS preprocessor: (Use arrow keys)                                                                          
> Sass with SCSS syntax 

? Check the features needed for your project:                                                                           
>( ) ESLint (recommended)                                                                                                
( ) TypeScript                                                                                                          
(*) Vuex                                                                                                                
(*) Axios                                                                                                               
(*) Vue-i18n

? Continue to install project dependencies after the project has been created? (recommended)                              
Yes, use Yarn (recommended)                                                                                           
> Yes, use NPM                                                                                                            
No, I will handle that myself


````

## Utilização

Após instalação e criação do projeto, faça o seguinte comando

````sh
cd noticias-app
````

Adicionar a extensão dotnet
````sh
quasar ext add @quasar/dotenv
````
(Marcar o default para todas as opções)

````sh
code .
````

Após instalação e criação do projeto, faça o seguinte comando

````sh
quasar dev
````

## Tipos de arquivo do Quasar

No Quasar temos, básicamente, 5 tipos de arquivos que você pode usar para criar seus projetos.

- pages – As páginas do projeto, cada um vai responder a uma URL;
- layouts – São os “templates”, ele encapsulam as páginas em layouts com menus, headers e footers, por exemplo.
- components – Num primeiro momento são parecidos com as páginas, mas eles servem para criar recursos reaproveitáveis, como os componentes nativos do Quasar, mas que não foram implementados ainda.
- boots – São arquivos javascript que são executados no início do projeto e podem ser usados para os mais diversos fins, um exemplo é para configurar e iniciar o Axios (cliente de requisições HTTP – faz requests Ajax).
- stores – São usados para “armazenamento de dados centralizados” da aplicação, MUITO útil. É fornecido pelo Vuex.

## Teste os componentes

https://quasar.dev/vue-components/

# Arquivo .env

Vamos colocar o nosso host da API no nosso arquivo .env
````sh
# This is your .env file
# The data added here will be propagated to the client
# example:
# PORT=8080
HOST=http://noticias.test
````

# Rotas

````js

const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/Index.vue') }
    ]
  },
  {
    path: '/noticias',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/Noticias/Index.vue') }
    ],
  },
  {
    path: '/noticias/:noticia/show',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/Noticias/Show.vue') }
    ]
  },
  {
    path: '/noticias/form/:noticia?',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/Noticias/Form.vue') }
    ]
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/Error404.vue')
  }
]

export default routes

````
- o path indica qual o caminho do site que essa rota vai representar.
- o component corresponde ao componente que será montado na tela quando o link indicado no path for acessado.
- children indica um Array de sub-rotas daquela rota. Como ela é uma sub-rota, herdará também o path da rota pai

Vamos alterar a tag ``<q-item>`` dentro do arquivo ``src\components\EssentialLink.vue``

````html
  <q-item
    clickable
    tag="a"
    :to="link"
  >
````

Agora vamos ajeitar o menu lateral para trazer os links das nossas notícias no arquivo ``src\layouts\MainLayout.vue``

````js
const linksList = [
  {
    title: 'Início',
    caption: 'Dashboard do aplicativo',
    icon: 'home',
    link: '/'
  },
  {
    title: 'Noticias',
    caption: 'Gerenciamento de notícias',
    icon: 'feed',
    link: '/noticias'
  }
];
````

Antes de fazermos requisições via axios, vamos definir o host padrão com base no nosso arquivo .env dentro de ``src\boot\axios.js``

````diff
import { boot } from 'quasar/wrappers'
import axios from 'axios'

// Be careful when using SSR for cross-request state pollution
// due to creating a Singleton instance here;
// If any client changes this (global) instance, it might be a
// good idea to move this instance creation inside of the
// "export default () => {}" function below (which runs individually
// for each client)
+const api = axios.create({ baseURL: process.env.HOST })

export default boot(({ app }) => {
  // for use inside Vue files (Options API) through this.$axios and this.$api

  app.config.globalProperties.$axios = axios
  // ^ ^ ^ this will allow you to use this.$axios (for Vue Options API form)
  //       so you won't necessarily have to import axios in each vue file

  app.config.globalProperties.$api = api
  // ^ ^ ^ this will allow you to use this.$api (for Vue Options API form)
  //       so you can easily perform requests against your app's API
})

export { api }

````
# Pasta Noticias

Dentro de pages crie a pasta ``Noticias`` e dentro dela os arquivos: ``index.vue``, ``form.vue`` e ``show.vue``.

## Index

````html
<template>
    <q-page class="q-pb-lg q-mb-lg">
        <q-list padding separator>
            <q-item-label header>Listagem de Notícias</q-item-label>
            <q-item v-for="noticia in noticias" clickable tag="a" @click="exibeActionNoticia(noticia)" v-ripple :key="noticia.id">
                <q-item-section top avatar>
                <q-avatar>
                    <img :src="host + noticia.imagem"/> 
                </q-avatar>
                </q-item-section>

                <q-item-section>
                    <q-item-label>{{ noticia.titulo }}</q-item-label>
                    <q-item-label caption lines="2">{{ noticia.data_publicacao }}</q-item-label>
                </q-item-section>
            </q-item>
        </q-list>

        <q-dialog v-model="exibeAction">
            <q-card class="full-width">
                <q-card-section class="row items-center">
                    <q-avatar icon="contact_support" color="primary" text-color="white" />
                    <span class="q-ml-sm">O que você deseja fazer?</span>
                </q-card-section>

                <q-card-actions align="right">
                <q-btn flat label="Ver" color="primary" v-close-popup @click="verNoticia"/>
                <q-btn flat label="Editar" color="warning" v-close-popup @click="editarNoticia"/>
                <q-btn flat label="Excluir" color="negative" v-close-popup @click="excluirNoticia"/>
                </q-card-actions>
            </q-card>
        </q-dialog>

        <q-page-sticky position="bottom-right" :offset="[18, 18]">
            <q-btn round icon="add" color="primary" size="md" @click="$router.push('/noticias/form/')"/>
          </q-page-sticky>
    </q-page>
</template>

<script>
import { defineComponent } from 'vue'
import { api } from 'boot/axios'

export default defineComponent({
  name: 'NoticiasIndex',
// :to="'/noticias/' + noticia.id"
  data() {
    return {
      noticias: [],
      host: process.env.HOST,
      exibeAction: false,
      noticiaAction: null
    }
  },

  methods: {
      exibeActionNoticia: function(noticia) {
          this.exibeAction = true
          this.noticiaAction = noticia
      },

      verNoticia: function() {
          this.$router.push('/noticias/' + this.noticiaAction.id + '/show')
      },

      editarNoticia: function() {
          this.$router.push('/noticias/form/' + this.noticiaAction.id)
      },    

      excluirNoticia: function() {

      }
  },
  
  mounted() {
    api.get('api/noticias/')
      .then((response) => {
        this.noticias = response.data
      })
      .finally(() => {
          /* Finalizou a requisicao */
      })
  }
})
</script>

<style>

</style>
````
https://quasar.dev/style/spacing

- q-pb-lg => q-(prefix) p (padding)	b (bottom)	lg (large)	q-pb-lg
- q-mb-lg => - q-(prefix)	m (margin)	b (bottom)	lg (large)
- <q-list padding separator> => Aplica um preenchimento na parte superior e inferior e Aplica um separador entre os itens contidos


O componente q-avatar cria um elemento escalonável e colorido que pode ter texto, ícone ou imagem em sua forma. Por padrão, é circular, mas também pode ser quadrado ou ter um raio de borda aplicado para dar cantos arredondados à forma quadrada.


## Form

````html
<template>
  <q-page class="q-pt-md q-px-md">
      <q-form ref="formulario">
          <q-input 
            v-model="noticia.titulo" 
            label="Título" 
            :rules="[val => !!val || 'Este campo é obrigatório']"
           />

           <q-input
            v-model="noticia.conteudo"
            autogrow
            label="Conteúdo"
            :rules="[val => !!val || 'Este campo é obrigatório']"
            />

            <q-file 
                v-model="noticia.imagem" 
                label="Imagem" 
            />

            <q-input v-model="noticia.data_publicacao" mask="##/##/####" label="Data de Publicação">
                <template v-slot:append>
                    <q-icon name="event" class="cursor-pointer">
                    <q-popup-proxy ref="qDateProxy" transition-show="scale" transition-hide="scale">
                        <q-date v-model="noticia.data_publicacao" mask="DD/MM/YYYY">
                        <div class="row items-center justify-end">
                            <q-btn v-close-popup label="Close" color="primary" flat />
                        </div>
                        </q-date>
                    </q-popup-proxy>
                    </q-icon>
                </template>
            </q-input>

            <q-select 
                v-model="noticia.status" 
                :options="[{label: 'Ativo', value: 'A'}, {label: 'Inativo', value: 'I'}]" 
                label="Status" 
            />

            <q-btn 
                color="primary" 
                label="Salvar" 
                icon="save" 
                class="q-mt-lg"
                @click="submit"
            />
      </q-form>
  </q-page>
</template>

<script>
import { api } from 'boot/axios'

export default {
    data() {
        return {
            noticia: {
                titulo: null,
                conteudo: null,
                imagem: null,
                data_publicacao: null,
                status: null
            }
        }
    },

    methods: {
        submit: function() {
            this.$refs.formulario.validate()
                .then((sucesso) => {
                    if (sucesso) {
                        const dados = new FormData()
                        dados.append('conteudo', this.noticia.conteudo)
                        dados.append('data_publicacao', this.noticia.data_publicacao)
                        dados.append('imagem', this.noticia.imagem)
                        dados.append('status', this.noticia.status.value)
                        dados.append('titulo', this.noticia.titulo)

                        api.post('/api/noticias', dados , {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                            .then((response) => {
                                console.log(response)
                            })
                    }
                })
        }
    }
}
</script>

<style>

</style>
````


