### Teste Elasticsearch


## Ambiente utilizado:

* Nginx 1.4.6
* PHP5-FPM - PHP5.6.29
* Composer
* Framework Phalcon - https://docs.phalconphp.com/pt/latest/reference/install.html
* Elasticsearch - https://www.elastic.co/guide/en/elasticsearch/reference/2.3/_installation.html
* PhpUnit 5.7.5

##Configuração

 Necessário alterar host do elasticsearch em:
 * app/config/config.php
 * porta padrão: 9200
 
 Necessário apontar link de acesso em:
 * tests/FindTest.php * Utilizo porta 8181
 
## Funcionamento

* Primeiramente é necessário alimentar elasticsearch com os dados do vagas.Json, a função irá apagar 
todos os dados correspondentes ao Index 'catho' e adicionar os novos dados.
 A idéia é deixar uma Cron atualizando os dados.

http://localhost:8181/index/updateElasticSearchData 

## Pesquisa

É possivel retornar os dados em formato JSON adicionando a variavel "&format=json" no final dos links

##### Pesquisa que retorna todos os resultados sem ordenação de salário:

* http://localhost:8181/find/?format=json  
* http://localhost:8181/find/

##### Pesquisa que retorna todos os resultados com ordenação de salário:

* http://localhost:8181/find/?order=desc - Decrescente   
* http://localhost:8181/find/?order=asc - Ascendente

##### Pesquisa que retorna os resultados pesquisando em 'title' e 'description':

* http://localhost:8181/find/text/?q=analista   
* http://localhost:8181/find/text/?q=analista&order=asc   
* http://localhost:8181/find/text/?q=analista&order=desc

##### Pesquisa que retorna os resultados pesquisando em 'cidade':

* http://localhost:8181/find/city/?q=chapeco
* http://localhost:8181/find/city/?q=chapeco&order=asc
* http://localhost:8181/find/city/?q=chapeco&order=desc

## Testes

Testes criados em PHPUnit 

* Teste de retorno de todos os registro - Verifica a quantidade total dos registros
* Teste de retorno de todos as vagas com salário decrescente - Verifica maior salário
* Teste de retorno de todas as vagas com salário ascendente - Verifica menor salário
* Teste de retorno de vagas com a pesquisa baseada no "title" e "description" - Teste de quantidade de vagas com as palavras 'óleo', 'ComerCial' e busca vazia 
* Teste de retorno de vagas com a pesquisa baseada no "title" e "description" - Teste de maior salario para a busca 'analista'
* Teste de retorno de vagas com a pesquisa baseada no "title" e "description" - Teste de menor salario para a busca 'analista'
* Teste de retorno de vagas com a pesquisa baseada na "cidade" - Teste de quantidade de vagas para a cidade 'Chapeco'
* Teste de retorno de vagas com a pesquisa baseada na "cidade" - Teste do maior salário para a cidade 'Chapeco'
* Teste de retorno de vagas com a pesquisa baseada na "cidade" - Teste do menor salário para a cidade 'Chapeco'

##### Comando para execução dos testes:   
* php vendor/bin/phpunit tests/FindTest.php
