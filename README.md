# Adega LTDA

### Requisitos para rodar este projeto:
* Docker (para o PostgreSQL)
* PHP >= 8.3
* Extensões PHP: Ctype, iconv, PCRE, Session, SimpleXML e Tokenizer
* `composer.phar` binário do composer em `/bin`
* Symfony CLI (para start da aplicação e validação local)

### Arquivo .env completo e senhas do ambiente
> O arquivo `.env` está completo pois não possui nenhuma informação sensível que não possa ser compartilhada, os parâmetros de autenticação do PostgreSQL (container) também estão com os valores padrão da instalação  

### Para iniciar o projeto, considerando atendidos os requisitos acima, execute:
* Valide o ambiente, revise os pré-requisitos com `symfony check:requirements`
* PRIMEIRO ACESSO: No diretório base do projeto, execute: `./caution-first-init.sh`
    * Container do PostgreSQL iniciará na porta `32370` do localhost
    * Composer fará a instalação das dependências
    * As migrations serão executadas
    * `symfony server:start` executa e o web server é disponibilizado
    * Dados básicos de Vinhos serão carregados (dez itens)
* O `app` provavelmente estará rodando em https://127.0.0.1:8000

### Para testes
> Se usado o `Postman`, uma collection com as rotas e dados exemplos está [disponível aqui](https://github.com/bechaire/adega/blob/main/postman_collection.json)
* Executar o `./run-tests.sh`
