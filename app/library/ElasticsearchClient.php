<?php
/**
 * Created by PhpStorm.
 * User: michel
 * Date: 10/01/17
 * Time: 10:32
 */
namespace Library;

use Phalcon\Di;

class ElasticsearchClient
{
    public $client;
    public $json_decoded;

    public function __construct()
    {
        $json = Di::getDefault()->getJson();
        $this->setJsonDecoded(json_decode($json));
        $this->setClient(Di::getDefault()->getElasticsearch());
    }

    /**
     * Atualização dos dados do arquivo JSON no ElasticSearch
     */
    public function updateElasticSearchData(){
        foreach ($this->getJsonDecoded()->docs as $key => $docs){
            $params = [
                'index' => 'catho',
                'type' => 'vagas',
                'id' => $key,
                'body' => [
                    'title' => $docs->title,
                    'description' => $docs->description,
                    'salario' => $docs->salario,
                    'cidade' => $docs->cidade,
                    'cidadeFormated' => $docs->cidadeFormated
                ]
            ];

            $this->getClient()->index($params);
        }
    }

    /**
     *
     *  Função para excluir dados em massa do Elasticsearch
     *
     * @return mixed
     */
    public function deleteElasticSearchData(){
        $ch = curl_init('http://172.17.0.4:9200/catho');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        return curl_exec($ch);
    }

    /**
     *
     *  Função de retorno de todos os resultados
     *
     * @param null $order
     * @return array
     */
    public function findAllJobs($order = null)
    {
        $ordenationQuery = $this->getQueryOrder($order);
        $query = '{ "match_all" : {} }';
        $jobs = $this->getJobs($query, $ordenationQuery);

        return $jobs;
    }


    /**
     *
     *  Função de pesquisa do Título e Descrição
     *
     * @param $text
     * @param null $order
     * @return array
     */
    public function findDescriptionOrTitle($text, $order = null){
        $ordenationQuery = $this->getQueryOrder($order);
        $query = '{ "multi_match" : { "query":    "'.$text.'", "fields": [ "title", "description" ]  } }';
        $jobs = $this->getJobs($query, $ordenationQuery);

        return $jobs;
    }


    /**
     *
     *  Função de pesquisa de cidade
     *
     * @param $text
     * @param null $order
     * @return array
     */
    public function findCity($text, $order = null){
        $ordenationQuery = $this->getQueryOrder($order);
        $query = '{ "match" : { "cidade" : { "query" : "'.$text.'", "type" : "phrase" } } }';
        $jobs = $this->getJobs($query, $ordenationQuery);

        return $jobs;
    }


    /**
     *
     *  Função de montagem da query de pesquisa
     *  Retorno do resultado da pesquisa
     *
     * @param $query
     * @param null $ordenationQuery
     * @return array
     */
    private function getJobs($query, $ordenationQuery = null){
        $query = '{
            "size" : 2000,
            "query" : '.$query.$ordenationQuery.'
        }';

        $params = [
            'index' => 'catho',
            'type' => 'vagas',
            'body' => json_decode($query)
        ];

        $response = $this->getClient()->search($params);
        return $response;
    }

    /**
     *
     * Função de ordenação
     *
     * @param $order
     * @return null|string
     */
    private function getQueryOrder($order){
        $ordenationQuery = null;
        if (!is_null($order)) {
            $ordenationQuery = ',
            "sort"  : { "salario": { "order": "' . $order . '" } }';
        }

        return $ordenationQuery;
    }

    /**
     * @return \Elasticsearch\Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param \Elasticsearch\Client $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return mixed
     */
    public function getJsonDecoded()
    {
        return $this->json_decoded;
    }

    /**
     * @param mixed $json_decoded
     */
    public function setJsonDecoded($json_decoded)
    {
        $this->json_decoded = $json_decoded;
    }

}