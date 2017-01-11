<?php
use Library\ElasticsearchClient;
use Phalcon\Http\Request;

class FindController extends \Phalcon\Mvc\Controller
{
    /**
     * Função para listar todas as vagas
     *
     */
    public function indexAction()
    {
        $elasticsearchClient = new ElasticsearchClient();
        $order = $this->getOrder();
        $jobs = $elasticsearchClient->findAllJobs($order);
        if($this->getFormat() == 'json'){
            $this->view->disable();
            return json_encode($jobs['hits']['hits']);
        }
        $this->view->jobs = $jobs['hits']['hits'];
    }

    /**
     *  Controller para busca por Titulo e Descrição
     */
    public function textAction(){
        $elasticsearchClient = new ElasticsearchClient();
        $text = $this->getText();
        $order = $this->getOrder();
        $jobs = $elasticsearchClient->findDescriptionOrTitle($text, $order);

        if($this->getFormat() == 'json'){
            $this->view->disable();
            return json_encode($jobs['hits']['hits']);
        }

        $this->view->jobs = $jobs['hits']['hits'];
        $this->view->pick("find/index");
    }


    /**
     *  Controller para busca por Cidade
     */
    public function cityAction(){
        $elasticsearchClient = new ElasticsearchClient();

        $text = $this->getText();
        $order = $this->getOrder();
        $jobs = $elasticsearchClient->findCity($text, $order);

        if($this->getFormat() == 'json'){
            $this->view->disable();
            return json_encode($jobs['hits']['hits']);
        }
        $this->view->jobs = $jobs['hits']['hits'];
        $this->view->pick("find/index");
    }


    /**
     *
     *  Função para resgate e tratamento da ordenação
     *
     * @return null|string
     */
    public function getOrder(){
        $request = new Request();
        $ordenations = array('asc', 'desc');

        $order = null;
        if($request->get("order")){
            if(in_array(strtolower($request->get("order")),$ordenations)){
                $order = strtolower($request->get("order"));
            }
        }

        return $order;
    }

    /**
     *  Função para resgate e tratamento do texto da pesquisa
     *
     * @return null|string
     */
    public function getText(){
        $request = new Request();
        $q = null;
        if($request->get("q")){
            $q = strtolower($request->get("q"));
        }

        return $q;
    }


    public function getFormat(){
        $request = new Request();
        $validFormats = array('json');
        $format = null;
        if($request->get("format")){
            if(in_array(strtolower($request->get("format")),$validFormats)){
                $format = strtolower($request->get("format"));
            }
        }
        return $format;
    }

}

