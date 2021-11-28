# Introdução

Docker é uma plataforma de código aberto que tem como objetivo criar, testar e implementar aplicações em um ambiente separado da máquina original, chamado de container. Dessa forma, o desenvolvedor consegue empacotar o software de maneira padronizada. 

A grande vantagem no uso da plataforma é a rapidez em que o software pode ser disponibilizado — em uma frequência até 7 vezes mais rápida do que a virtualização convencional.

Outro benefício oferecido pela plataforma é a possibilidade de configurar diferentes ambientes de forma rápida, além de diminuir o número de incompatibilidades entre os sistemas disponíveis.

# Instalação do Docker

## Instalação no Windows
É recomendado pelo menos 8gb de memória RAM no computador para melhor experiência com Docker no Windows. Além disso, é recomenando a versão PRO do Windows 10. Se seu computador não satisfaz a estes requisitos, sugerimos o uso do [Laragon](https://laragon.org/) para servir suas aplicações web.

Baixe o Docker para Windows no [site oficial](https://docs.docker.com/docker-for-windows/install/) do Docker, através do botão "Docker Desktop for Windows". A instalação é bem simples e não precisa de configuração. Ao mesmo tempo a instalação disponibilizará o **docker cli** e o **docker-compose**. 

### Erro na Instalação do Windows - WSL2
O erro de wsl2 acontece quando este está desatualizado. Para atualizar você deve baixar esse instalador: https://wslstorestorage.blob.core.windows.net/wslblob/wsl_update_x64.msi

Instalar e, após instalar, executar este comando no terminal: ``wsl --set-default-version 2``

Depois disso tem que reiniciar o docker e tentar novamente. Se persistir o erro, reinicie o PC.

## Instalação no Linux
Esteja sempre atento às atualizações na [documentação oficial](https://docs.docker.com/engine/install/ubuntu/). Aqui reproduziremos os comandos, mas saiba que eles poderão ficar desatualizados. A documentação oficial é sempre a melhor fonte.

Execute os comandos abaixo exatamente na mesma ordem (apenas para Ubuntu e alguns derivados do Debian):

1. Desistale possíveis instalações antigas do Docker
````sh
sudo apt-get remove docker docker-engine docker.io containerd runc
````
2. Atualize as listas do apt
````sh
sudo apt-get update
````
3. Adicione os certificados e dependências para instalação do Docker
````sh
sudo apt-get install \
    apt-transport-https \
    ca-certificates \
    curl \
    gnupg \
    lsb-release
````
4. Adicione a chave oficial do Docker
````sh
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg
````
5. Adicione a lista de instalação do Docker
````sh
echo \
  "deb [arch=amd64 signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu \
  $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
````
6. Atualize novamente as listas do apt
````sh
sudo apt-get update
````
7. Instale o Docker propriamente dito
````sh
sudo apt-get install docker-ce docker-ce-cli containerd.io
````
8. Valide a instalação do Docker
````sh
sudo docker run hello-world
````
A mensagem que deverá exibir é mais ou menos essa
````sh
Hello from Docker!
This message shows that your installation appears to be working correctly.

To generate this message, Docker took the following steps:
 1. The Docker client contacted the Docker daemon.
 2. The Docker daemon pulled the "hello-world" image from the Docker Hub.
    (amd64)
 3. The Docker daemon created a new container from that image which runs the
    executable that produces the output you are currently reading.
 4. The Docker daemon streamed that output to the Docker client, which sent it
    to your terminal.

To try something more ambitious, you can run an Ubuntu container with:
 $ docker run -it ubuntu bash

Share images, automate workflows, and more with a free Docker ID:
 https://hub.docker.com/

For more examples and ideas, visit:
 https://docs.docker.com/get-started/
````

# Instalação do docker-compose
A instalação do Windows já trás o docker-compose, porém, para Linux, é necessário instalar. Execute os comandos abaixo:
````sh
sudo curl -L "https://github.com/docker/compose/releases/download/1.29.2/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
````

````sh
sudo chmod +x /usr/local/bin/docker-compose
````

````sh
sudo ln -s /usr/local/bin/docker-compose /usr/bin/docker-compose
````

Teste a instalação
````sh
docker-compose --version
````

É para exibir algo semelhante a:
````sh
docker-compose version 1.29.2, build 1110ad01
````

# Instalação do docker-flex
Para o manual de instalação do docker-flex, por favor leia o readme do projeto da Flexpeak - [docker-flex](https://github.com/flexpeak/docker-flex).

# Importante

> Alguns lembretes para esclarecer mais as coisas.
>
>O servidor HTTP é o servidor que vai fazer o seu projeto Laravel funcionar. Existem vários disponíveis no mercado. O laragon instala um servidor, o docker instala outro e o built in do php é outro. Então se você usar o Laragon, não precisa usar nem o docker e nem o servidor builtin do PHP. Se você usar o docker, não precisa do laragon e assim vice-versa. 
>
>Caso você decida usar o laragon, você precisa startar os serviços (assim como no docker-compose up -d). Ou seja, você precisa startar o nginx (ou apache) e o mysql... tem um botão no laragon que faz tudo isso de uma vez (> Iniciar Tudo).
>
>A pasta de projetos no laragon é a www (C:/laragon/www). Então os seus projetos devem ficar nessa pasta. O laragon cria o apontamento no arquivo hosts automaticamente com o sufixo .test
>
>Se você tem um projeto chamado noticias dentro da pasta www ao Laragon, ao iniciar ele você já conseguirá acessar o projeto no navegador assim: http://noticias.test
