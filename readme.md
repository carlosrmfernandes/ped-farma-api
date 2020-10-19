![alt text](https://is2-ssl.mzstatic.com/image/thumb/Purple123/v4/55/f0/d9/55f0d983-0ca7-57f7-ddd7-4a6de61e8457/AppIcon-0-1x_U007emarketing-0-0-GLES2_U002c0-512MB-sRGB-0-0-0-85-220-0-0-0-10.png/246x0w.png)
## API PedFarma

## Começando
Essas instruções farão com que você tenha uma cópia do projeto em execução na sua máquina local para fins de desenvolvimento e teste. Veja a implantação de notas sobre como implantar o projeto em um sistema ativo.

## Pré-requisitos

```php
Clonar o projecto
```

A seguir, execute o seguinte:

```php
Criar um arquivo .evn coloca os dados que estarão no arquivo .env.example;
```

Todos esses comandos que citarei a seguir, devem ser executados na linha de comando da sua máquina. Portanto, navegue até a pasta do projeto para poder executar os comandos abaixo especificados.
Para instalar o projeto em sua máquina, logo após clona-ló, na pasta raíz do projeto execute os seguintes comandos. 

```php
composer install
```
Pra quem vem do JavaScript, esse comando funcionaria como o npm, o "composer install" vai instalar todas as dependências do Laravel necessárias para executar o projeto em sua máquina

## Banco de dados

Configura o seu banco de dados e a seguir, execute o seguinte comando:

```php
php artisan migrate
```

## Executar o projecto com o seguinte comando

```php
php artisan serve
```
## Configuração de envio de email 

Para teste de preferência usar o serviço mailtrap 
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls

## Link da documentação 

https://drive.google.com/file/d/13nlU0WoKAfzrsJjuAaoG1ffH8O7BUGYP/view?usp=sharing


## Link da collections 

https://www.postman.com/collections/47a7536f2359b39d4d4a
