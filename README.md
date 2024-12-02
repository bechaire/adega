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
* O script `start.sh` e o script `stop.sh`, só servem para parar e inicar o webserver e o container do banco de dados depois do processo acima ser realizado a primeira vez
* O script `run-tests.sh` faz rodar os testes do **PHPUnit**

### Para testes
> Se usado o `Postman`, uma collection com as rotas e dados exemplos está [disponível aqui](https://github.com/bechaire/adega/blob/main/postman_collection.json)
* Executar o `./run-tests.sh`

### Sobre o desenvolvimento
> \- A abordagem adotada foi criar controladores enxutos, delegando a lógica de negócios para serviços dedicados, de forma a manter o código o mais limpo possível, com o mínimo de exposição. Em alguns casos, as respostas são geradas por DQL; em outros, por um filtro de normalização. Essa escolha foi feita intencionalmente para balancear desempenho e flexibilidade. As validações de dados foram centralizadas nos DTOs, garantindo a integridade das informações e desacoplando as entidades da lógica de apresentação. Embora atributos (asserts) pudessem ser usados diretamente nas entidades para este protótipo, a abordagem com DTOs oferece maior flexibilidade para cenários mais complexos e facilita a manutenção a longo prazo (escopo do desafio).  
> \- Busquei manter a lógica bem dividida entre os componentes, mas, ao final do desenvolvimento, a lógica de cálculo do frete foi alocada no SalesService. Seus resultados são replicados para a entidade Sales (e não consultados sob demanda), visando otimizar o desempenho e evitar consultas redundantes no futuro. Também usei DQL em vez de SQL, já que não seria possível obter um SQL mais otimizado, mas mantendo a compatibilidade com múltiplos bancos.  
> \- Para as consultas, utilizei tanto o QueryBuilder quanto DQL de forma intencional, com o objetivo de demonstrar as duas abordagens. O código que se repetiria entre as entidades foi isolado em traits, garantindo a reutilização de forma eficiente e organizada.