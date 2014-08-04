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
namespace KmbPuppetDb;

use KmbPuppetDb\Exception\InvalidArgumentException;
use KmbPuppetDb\Exception\RuntimeException;
use KmbPuppetDb\Options\ClientOptionsInterface;
use Zend\Http;
use Zend\Json\Json;
use Zend\Log\Logger;

class Client implements ClientInterface
{
    /**
     * @var ClientOptionsInterface
     */
    protected $options;

    /**
     * @var Http\Client
     */
    protected $httpClient;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @param Request $request
     * @return Response
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     */
    public function send(Request $request)
    {
        $uri = $request->getFullUri();
        $response = new Response();
        $httpRequest = new Http\Request();

        $headers = ['Accept' => 'application/json'];
        $httpRequest->setUri($this->getUri($uri));
        if ($request->getMethod() == Http\Request::METHOD_POST) {
            $httpRequest->setMethod($request->getMethod());
            $httpRequest->setContent(Json::encode($request->getData()));
            $headers['Content-Type'] = 'application/json';
        }
        $newHeaders = new Http\Headers();
        $newHeaders->addHeaders($headers);
        $httpRequest->setHeaders($newHeaders);

        $start = microtime(true);
        $httpResponse = $this->getHttpClient()->send($httpRequest);
        $this->logRequest($start, $httpResponse->renderStatusLine(), $uri);
        $body = $httpResponse->getBody();

        if (!$httpResponse->isSuccess()) {
            $this->getLogger()->err('[' . $httpResponse->renderStatusLine() . '] ' . $body);
            throw new RuntimeException('Unexpected PuppetDB Response: ' . $httpResponse->renderStatusLine());
        }

//        $this->getLogger()->debug($body);
        $data = Json::decode($body);
        $response->setData($data);

        if (isset($data->error)) {
            throw new InvalidArgumentException($data->error);
        }

        $responseHeaders = $httpResponse->getHeaders();
        if ($responseHeaders->has('X-Records')) {
            $response->setTotal(intval($responseHeaders->get('X-Records')->getFieldValue()));
        }

        return $response;
    }

    /**
     * Get options.
     *
     * @return ClientOptionsInterface
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set options.
     *
     * @param $options
     * @return Client
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Get HTTP Client.
     *
     * @return Http\Client
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * Set HTTP Client.
     *
     * @param $httpClient
     * @return Client
     */
    public function setHttpClient($httpClient)
    {
        $this->httpClient = $httpClient;
        return $this;
    }

    /**
     * Get logger.
     *
     * @return Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * Set logger.
     *
     * @param $logger
     * @return Client
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * @param $uri
     * @return string
     */
    protected function getUri($uri)
    {
        $baseUri = rtrim($this->options->getBaseUri(), '/');
        $apiVersion = '/' . trim($this->options->getApiVersion(), '/');
        return $baseUri . $apiVersion . $uri;
    }

    /**
     * @param $start
     * @param $statusLine
     * @param $uri
     */
    protected function logRequest($start, $statusLine, $uri)
    {
        $duration = intval((microtime(true) - $start) * 1000);
        $splittedUri = explode('?', $uri);
        $uriToLog = array_shift($splittedUri);
        $this->getLogger()->debug("[$duration ms] [" . $statusLine . "] $uriToLog");
//        $this->getLogger()->debug("[$duration ms] [" . $statusLine . "] $uri");
    }
}
