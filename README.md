#Guia de Instalação do Projeto 
Este guia detalha o processo de configuração e execução do ambiente de desenvolvimento local usando Docker e Laravel Sail.

Demonstração na pasta vídeo, localizada na raíz do projeto.

Pré-requisitos
Para seguir este guia, certifique-se de que os seguintes softwares estão instalados em sua máquina:

Git

Docker Desktop

Windows Subsystem for Linux (WSL 2): Para usuários de Windows, é recomendado utilizar o WSL 2.

Passo 1: Clonar o Repositório
Primeiro, clone o projeto a partir do repositório Git e navegue até a pasta criada:

Passo 2: Configurar o Ambiente
O projeto requer um arquivo de configuração de ambiente (.env). Copie o arquivo de exemplo para criar sua própria 

Abra o arquivo .env e configure as chaves do Google reCAPTCHA, essenciais para as funcionalidades de login e registro:

NOCAPTCHA_SITE_KEY=SUA_CHAVE_DO_SITE
NOCAPTCHA_SECRET_KEY=SUA_CHAVE_SECRETA

As configurações do banco de dados já estão pré-definidas para funcionar com o Sail, não sendo necessário alterá-las.

Passo 3: Iniciar o Ambiente Docker
Utilize o Sail para construir e iniciar os contêineres Docker da aplicação. 

O comando abaixo irá iniciar o ambiente em segundo plano (-d):
./vendor/bin/sail up -d

Para verificar se os contêineres foram iniciados com sucesso, use o comando:
./vendor/bin/sail ps

Passo 4: Instalar as Dependências e Gerar a Chave da Aplicação
Com o ambiente em execução, utilize o Sail para rodar os comandos de instalação de dependências e configuração da aplicação dentro do contêiner:

# Instala as dependências do PHP com o Composer
./vendor/bin/sail composer install

# Gera a chave de encriptação da aplicação
./vendor/bin/sail artisan key:generate

Passo 5: Configurar o Banco de Dados
Finalize a configuração do banco de dados executando as migrações e o seeder. É crucial rodar as migrações primeiro, seguidas pelo seeder.

# 1. Executa as migrações para criar a estrutura das tabelas
./vendor/bin/sail artisan migrate

# 2. Executa o seeder para popular o banco de dados com um usuário administrador
./vendor/bin/sail artisan db:seed --class=AdminUserSeeder
Sua aplicação estará acessível em localhost.

Detalhes Adicionais do Projeto

Banco de Dados: Desenvolvimento vs. Testes

Ambiente de Desenvolvimento: O projeto utiliza MySQL 8.0 em um contêiner Docker para o ambiente de desenvolvimento. A conexão com o banco pode ser feita através de um cliente externo em localhost:3307.

Testes Automatizados: Para garantir a rapidez e a isolamento dos testes, a suíte de testes (executada via ./vendor/bin/sail test) é configurada para usar SQLite em memória, criando um banco de dados temporário para cada execução.

Comandos Úteis do Sail
A seguir, alguns comandos úteis do Sail para o seu fluxo de trabalho:

Comando	Descrição
./vendor/bin/sail up -d	Inicia os contêineres do projeto em segundo plano.
./vendor/bin/sail down	Interrompe e remove os contêineres do projeto.
./vendor/bin/sail artisan <comando>	Executa qualquer comando do Artisan dentro do contêiner.
./vendor/bin/sail test	Executa a suíte de testes do PHPUnit.
./vendor/bin/sail shell	Abre uma sessão de terminal no contêiner da aplicação.


Tecnologias e Endpoints
Front-end
O front-end da aplicação foi desenvolvido utilizando HTML, CSS e JavaScript. Para a estilização, foi adotado o framework Tailwind CSS, que permite a construção de interfaces de forma ágil com uma abordagem utility-first. A interação com o back-end é realizada através de requisições assíncronas (AJAX), garantindo uma experiência de usuário fluida e dinâmica, sem a necessidade de recarregar a página para operações como cadastrar, editar ou excluir dados.

API Endpoints
A aplicação expõe uma API RESTful para gerenciar as operações. Abaixo estão detalhados os endpoints disponíveis.

Autenticação
Método HTTP	Endpoint	Descrição
POST	/login	Autentica um usuário e retorna um token de acesso Sanctum.

Com certeza. Aqui está o complemento para adicionar ao seu README.md.

Tecnologias e Endpoints
Front-end
O front-end da aplicação foi desenvolvido utilizando HTML, CSS e JavaScript. Para a estilização, foi adotado o framework Tailwind CSS, que permite a construção de interfaces de forma ágil com uma abordagem utility-first. A interação com o back-end é realizada através de requisições assíncronas (AJAX), garantindo uma experiência de usuário fluida e dinâmica, sem a necessidade de recarregar a página para operações como cadastrar, editar ou excluir dados.

API Endpoints
A aplicação expõe uma API RESTful para gerenciar as operações. Abaixo estão detalhados os endpoints disponíveis.

Autenticação
Método HTTP	Endpoint	Descrição
POST	/login	Autentica um usuário e retorna um token de acesso Sanctum.

Exportar para as Planilhas
Rotas Protegidas (Requerem Autenticação)
Os endpoints a seguir só podem ser acessados com um token de autenticação válido.

Método HTTP	Endpoint	Descrição
GET	/users	Lista todos os usuários, com suporte a paginação.
POST	/users	Cria um novo usuário.
GET	/users/{id}	Exibe os detalhes de um usuário específico.
PUT	/users/{id}	Atualiza os dados de um usuário existente.
DELETE	/users/{id}	Exclui um usuário específico.
POST	/users/bulk-delete	Exclui múltiplos usuários em massa a partir de uma lista de IDs.
POST	/logout	Invalida o token de acesso do usuário (logout).