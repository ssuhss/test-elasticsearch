<?php
use Library\ElasticsearchClient;

class IndexController extends \Phalcon\Mvc\Controller
{

    /**
     *  Função para limpar dados do Elasticsearch em massa
     *  E atualizar dados do Elasticsearch com Vagas.json
     */
    public function updateElasticSearchDataAction()
    {
        $elasticSearchClient = new ElasticsearchClient();
        $elasticSearchClient->deleteElasticSearchData();
        $elasticSearchClient->updateElasticSearchData();

        echo "UPDATED";
        $this->view->disable();
    }
}

