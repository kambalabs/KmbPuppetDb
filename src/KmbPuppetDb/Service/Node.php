<?php
/**
 * @copyright Copyright (c) 2014 Orange Applications for Business
 * @link      http://github.com/multimediabs/kamba for the canonical source repository
 *
 * This file is part of kamba.
 *
 * kamba is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * kamba is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with kamba.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace KmbPuppetDb\Service;

use KmbPuppetDb;
use KmbPuppetDb\Exception\InvalidArgumentException;
use KmbPuppetDb\Model;
use KmbPuppetDb\Options\NodeServiceOptionsInterface;
use KmbPuppetDb\Service;
use Zend\Http;
use Zend\Log\Logger;
use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\Hydrator\HydratorInterface;

class Node implements NodeInterface
{
    /**
     * @var NodeServiceOptionsInterface
     */
    protected $options;

    /**
     * @var KmbPuppetDb\ClientInterface
     */
    protected $puppetDbClient;

    /**
     * @var HydratorInterface
     */
    protected $nodeHydrator;

    /** @var Logger */
    protected $logger;

    /**
     * Retrieves a node by its name.
     *
     * @param string $name
     * @return Model\NodeInterface
     * @throws InvalidArgumentException
     */
    public function getByName($name)
    {
        $response = $this->getPuppetDbClient()->send(new KmbPuppetDb\Request('/nodes/' . $name));
        return $this->createNodeFromData($response->getData());
    }

    /**
     * Retrieves all nodes matching with specified query, paging and sorting.
     * All nodes are returned if parameters are null.
     * $query can be omitted :
     *    $nodeService->getAll(10, 10);
     *
     * @param mixed $query
     * @param int   $offset
     * @param mixed $limit
     * @param array $orderBy
     * @return Model\NodesCollection
     */
    public function getAll($query = null, $offset = null, $limit = null, $orderBy = null)
    {
        if (is_int($query)) {
            $orderBy = $limit;
            $limit = $offset;
            $offset = $query;
            $query = null;
        }
        $request = new KmbPuppetDb\Request('/nodes', $query, $orderBy);
        $request->setPaging($offset, $limit);
        $response = $this->getPuppetDbClient()->send($request);

        $nodes = array();
        foreach ($response->getData() as $data) {
            $node = $this->createNodeFromData($data);
            $nodes[] = $node;
        }
        return Model\NodesCollection::factory($nodes, $response->getTotal());
    }

    /**
     * @param string $nodeName
     * @return string
     */
    public function getNodeStatus($nodeName)
    {
        $request = new KmbPuppetDb\Request('/event-counts', array(
            'and',
            array('=', 'certname', $nodeName),
            array('=', 'latest-report?', true),
        ));
        $request->setSummarizeBy('certname');

        $response = $this->getPuppetDbClient()->send($request);

        $events = (array)$response->getData();
        if (empty($events)) {
            return Model\NodeInterface::UNCHANGED;
        }
        if ($events[0]->failures > 0) {
            return Model\NodeInterface::FAILED;
        }

        return Model\NodeInterface::CHANGED;
    }

    /**
     * @param string $nodeName
     * @return array
     */
    public function getNodeFacts($nodeName)
    {
        $request = new KmbPuppetDb\Request("/nodes/$nodeName/facts");

        $response = $this->getPuppetDbClient()->send($request);

        $facts = array();
        foreach ($response->getData() as $fact) {
            $facts[$fact->name] = $fact->value;
        }

        return $facts;
    }

    /**
     * @param Model\NodeInterface $node
     * @return Node
     */
    public function replaceFacts(Model\NodeInterface $node)
    {
        $request = new KmbPuppetDb\Request('/commands');
        $request->setMethod(Http\Request::METHOD_POST);
        $request->setData([
            'command' => 'replace facts',
            'version' => 2,
            'payload' => $this->getNodeHydrator()->extract($node),
        ]);

        $response = $this->getPuppetDbClient()->send($request);
        $this->getLogger()->info('[' . $response->getData()->uuid . ']' . ' Replacing facts for ' . $node->getName());

        return $this;
    }

    /**
     * Get options.
     *
     * @return NodeServiceOptionsInterface
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set options.
     *
     * @param $options
     * @return Service\Node
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Get PuppetDB Client.
     *
     * @return \KmbPuppetDb\ClientInterface
     */
    public function getPuppetDbClient()
    {
        return $this->puppetDbClient;
    }

    /**
     * Set PuppetDB Client.
     *
     * @param KmbPuppetDb\ClientInterface $puppetDbClient
     * @return Service\Node
     */
    public function setPuppetDbClient(KmbPuppetDb\ClientInterface $puppetDbClient)
    {
        $this->puppetDbClient = $puppetDbClient;
        return $this;
    }

    /**
     * Set Logger.
     *
     * @param Logger $logger
     * @return Node
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * Get Logger.
     *
     * @return Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @return HydratorInterface
     */
    protected function getNodeHydrator()
    {
        if ($this->nodeHydrator == null) {
            $nodeHydratorClass = $this->getOptions()->getNodeHydratorClass();
            $this->nodeHydrator = new $nodeHydratorClass;
        }
        return $this->nodeHydrator;
    }

    /**
     * @param $data
     * @return Model\NodeInterface
     */
    protected function createNodeFromData($data)
    {
        $nodeEntityClass = $this->getOptions()->getNodeEntityClass();
        /** @var Model\NodeInterface $node */
        $node = new $nodeEntityClass;

        $nodeEntityProxyClass = $this->getOptions()->getNodeEntityProxyClass();
        /** @var KmbPuppetDb\Proxy\NodeProxy $nodeProxy */
        $nodeProxy = new $nodeEntityProxyClass;
        $nodeProxy->setNode($node);
        $nodeProxy->setNodeService($this);

        return $this->getNodeHydrator()->hydrate((array)$data, $nodeProxy);
    }
}
