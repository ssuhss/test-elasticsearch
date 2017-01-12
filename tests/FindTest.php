<?php
use Guzzle\Http\Client;

class FindTest extends PHPUnit_Framework_TestCase
{
    protected $client;

    protected function setUp()
    {
        $this->client = new Client('http://localhost:8181/find');
    }

    /**
     *  Teste de retorno de todos os registro
     *
     *  TOTAL: 1200
     */
    public function testFindAll()
    {
        $request = $this->client->get('?format=json');
        $response = $request->send();
        $allJobs = json_decode($response->getBody());
        $this->assertEquals(1200, count($allJobs));
    }

    /**
     *   Teste de retorno de todos as vagas com salário decrescente
     *
     *   Teste com desc minusculo e DESC maiusculo
     *
     *  Maior salario: 11000
     *
     */
    public function testFindAllDesc()
    {
        //LOWER DESC
        $request = $this->client->get('?format=json&order=desc');
        $response = $request->send();
        $allJobs = json_decode($response->getBody());
        $this->assertEquals(11000, $allJobs[0]->_source->salario);

        //UPPER DESC
        $requestUpper = $this->client->get('?format=json&order=DESC');
        $responseUpper = $requestUpper->send();
        $allJobsUpper = json_decode($responseUpper->getBody());
        $this->assertEquals(11000, $allJobsUpper[0]->_source->salario);
    }

    /**
     *   Teste de retorno de todas as vagas com salário ascendente
     *
     *   Menor salário: 750
     */
    public function testFindAllAsc()
    {
        $request = $this->client->get('?format=json&order=asc');
        $response = $request->send();
        $allJobs = json_decode($response->getBody());
        $this->assertEquals(750, $allJobs[0]->_source->salario);
    }

    /**
     *
     *  Teste de retorno de vagas com a pesquisa baseada no "title" e "description"
     *
     *  Teste de CaseSensitive
     *
     *  Teste de busca nula
     *
     */
    public function testFindByTitleOrDescription()
    {
        $search = 'óleo';
        $request = $this->client->get('text/?format=json&q='.$search);
        $response = $request->send();
        $titleOrDescription = json_decode($response->getBody());
        $this->assertEquals(2, count($titleOrDescription));

        $searchUpper = 'ComerCial';
        $requestUpper = $this->client->get('text/?format=json&q='.$searchUpper);
        $responseUpper = $requestUpper->send();
        $titleOrDescriptionUpper = json_decode($responseUpper->getBody());
        $this->assertEquals(115, count($titleOrDescriptionUpper));

        $searchNull = ' ';
        $requestNull = $this->client->get('text/?format=json&q='.$searchNull);
        $responseNull = $requestNull->send();
        $titleOrDescriptionNull = json_decode($responseNull->getBody());
        $this->assertEquals(0, count($titleOrDescriptionNull));

    }

    /**
     *  Teste de retorno de vagas com a pesquisa baseada no "title" e "description"
     *
     *  Ordenação Decrescente
     *
     *  Maior salário para "analista" = 5600
     */
    public function testFindByTitleOrDescriptionDesc()
    {
        $search = 'analista';
        $request = $this->client->get('text/?format=json&order=desc&q='.$search);
        $response = $request->send();
        $titleOrDescription = json_decode($response->getBody());
        $this->assertEquals(5600, $titleOrDescription[0]->_source->salario);

    }

    /**
     *  Teste de retorno de vagas com a pesquisa baseada no "title" e "description"
     *
     *  Ordenação Ascendente
     *
     *  Menor salário para "analista" = 900
     */
    public function testFindByTitleOrDescriptionAsc()
    {
        $search = 'analista';
        $request = $this->client->get('text/?format=json&order=asc&q='.$search);
        $response = $request->send();
        $titleOrDescription = json_decode($response->getBody());
        $this->assertEquals(900, $titleOrDescription[0]->_source->salario);
    }

    /**
     *  Teste de retorno de vagas com a pesquisa baseada na "cidade"
     *
     *  Quantidade de vagas para "Chapeco" = 36
     */
    public function testFindByCity()
    {
        $search = 'Chapeco';
        $request = $this->client->get('city/?format=json&q='.$search);
        $response = $request->send();
        $city = json_decode($response->getBody());
        $this->assertEquals(36, count($city));
    }

    /**
     *  Teste de retorno de vagas com a pesquisa baseada na "cidade"
     *
     *  Ordenação Decrescente
     *
     *  Maior salário para "Chapeco" = 8000
     */
    public function testFindByCityDesc()
    {
        $search = 'Chapeco';
        $request = $this->client->get('city/?format=json&order=desc&q='.$search);
        $response = $request->send();
        $city = json_decode($response->getBody());
        $this->assertEquals(8000, $city[0]->_source->salario);
    }

    /**
     *  Teste de retorno de vagas com a pesquisa baseada na "cidade"
     *
     *  Ordenação Ascendente
     *
     *  Menor salário para "Chapeco" = 1000
     */
    public function testFindByCityAsc()
    {
        $search = 'Chapeco';
        $request = $this->client->get('city/?format=json&order=asc&q='.$search);
        $response = $request->send();
        $city = json_decode($response->getBody());
        $this->assertEquals(1000, $city[0]->_source->salario);
    }


}
