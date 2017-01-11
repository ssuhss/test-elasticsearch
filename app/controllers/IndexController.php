<?php
use Library\ElasticsearchClient;

class IndexController extends \Phalcon\Mvc\Controller
{

    /**
     *  Função para limpar dados do Elasticsearch em massa
     *  E atualizar dados com Vagas.json
     */
    public function updateElasticSearchAction()
    {
        $elasticSearchClient = new ElasticsearchClient();
        if($elasticSearchClient->deleteElasticSearchData()){
            $elasticSearchClient->updateElasticSearchData();
        }

        echo "UPDATED";
        $this->view->disable();
    }
}

