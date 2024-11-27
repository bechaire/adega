# Adega LTDA

### Requisitos para rodar este projeto:
* Docker (para o PostgreSQL)
* PHP >= 8.3
* Extensões PHP: Ctype, iconv, PCRE, Session, SimpleXML e Tokenizer
* `composer.phar` binário do composer em `/bin`
* Symfony CLI (para start da aplicação e validação local)
* Valide os pré requisitos com `symfony check:requirements`

### Arquivo .env completo e senhas do ambiente
> O arquivo `.env` está completo pois não possui nenhuma informação sensível que não possa ser compartilhada, os parâmetros de autenticação do PostgreSQL (container) também estão com os valores padrão da instalação  

### Para iniciar o projeto, considerando atendidos os requisitos acima, execute:
* No diretório base do projeto: `./start.sh`
* O `symfony server:start` iniciará, e um `docker compose up -d` para o banco de dados será acionado, será usada a porta `32370` do localhost pra isso
* O `app` provavelmente estará rodando em https://127.0.0.1:8000
* Alguns pacotes serão instalados via composer