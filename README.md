# Adega LTDA

### Requisitos para rodar este projeto:
* Docker (para o PostgreSQL)
* PHP >= 8.3
* Extensões PHP: Ctype, iconv, PCRE, Session, SimpleXML e Tokenizer
* `composer.phar` binário do composer em `/bin`
* Symfony CLI (para start da aplicação e validação local)

### Clonar este repositório

### Arquivo .env completo e senhas do ambiente
> O arquivo `.env` está completo pois não possui nenhuma informação sensível que não possa ser compartilhada, os parâmetros de autenticação do PostgreSQL (container) também estão com os valores padrão da instalação  

### Para iniciar o projeto, considerando atendidos os requisitos acima:
* Na raiz do repositório clonado...
```bash
# validade os requisitos do sistema com
symfony check:requirements

# inicie o banco (container)
docker compose up -d

# instale as dependências
symfony composer install --dev

# execute as migrações
bin/console doctrine:migrations:migrate

# inicie o servidor embutido do symfony
symfony server:start -d

# se quiser uma carga de dados de vinhos para testes, execute:
bin/console doctrine:fixtures:load --group=radom_wines -q

# verifique se o docker está rodando com o container do banco de dados
docker ps
```
* O container do PostgreSQL iniciará na porta `32370` do localhost
* O `app` provavelmente estará rodando em https://127.0.0.1:8000
* O script `start.sh` e o script `stop.sh`, só servem para parar e inicar o webserver e o container do banco de dados
* O script `run-tests.sh` faz rodar os testes do **PHPUnit**

### Para testes
> Se usado o `Postman`, uma collection com as rotas e dados exemplos está [disponível aqui](https://github.com/bechaire/adega/blob/main/postman_collection.json)
* Executar o `./run-tests.sh`
