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

class Request
{
    const API_VERSION = 'v3';

    /**
     * @var string
     */
    protected $uri;

    /**
     * @var Query|array
     */
    protected $query;

    /**
     * @var int
     */
    protected $offset;

    /**
     * @var int
     */
    protected $limit;

    /**
     * @var OrderBy|array
     */
    protected $orderBy;

    /**
     * @var string
     */
    protected $summarizeBy;

    /**
     * @var bool
     */
    protected $includeTotal = false;

    /**
     * @param string         $uri
     * @param Query|array    $query
     * @param OrderBy|array  $orderBy
     */
    public function __construct($uri, $query = null, $orderBy = null)
    {
        $this->setUri($uri);
        $this->setQuery($query);
        $this->setOrderBy($orderBy);
    }

    /**
     * Get full constructed URI.
     * @return string
     */
    public function getFullUri()
    {
        $queryString = '';

        $params = array();
        if ($this->hasQuery()) {
            $params[] = $this->getQueryParam();
        }

        if ($this->hasOrderBy()) {
            $params[] = $this->getOrderByParam();
        }

        if ($this->hasSummarizeBy()) {
            $params[] = 'summarize-by=' . $this->getSummarizeBy();
        }

        if ($this->hasOffset()) {
            $params[] = 'offset=' . $this->getOffset();
        }

        if ($this->hasLimit()) {
            $params[] = 'limit=' . $this->getLimit();
        }

        if ($this->hasIncludeTotal()) {
            $params[] = 'include-total=' . $this->getIncludeTotalAsString();
        }

        if (!empty($params)) {
            $queryString = '?' . implode('&', $params);
        }

        return '/' . static::API_VERSION . '/' . ltrim($this->getUri(), '/') . $queryString;
    }

    /**
     * @param int $offset
     * @param int $limit
     */
    public function setPaging($offset, $limit)
    {
        $this->setOffset($offset);
        $this->setLimit($limit);
        if ($offset !== null || $limit !== null) {
            $this->setIncludeTotal(true);
        }
    }

    /**
     * Get Uri.
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Set Uri.
     *
     * @param string $uri
     * @return Request
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
        return $this;
    }

    /**
     * Get Query.
     *
     * @return Query|array
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return bool
     */
    public function hasQuery()
    {
        return !empty($this->query);
    }

    /**
     * Set Query.
     *
     * @param Query|array $query
     * @return Request
     */
    public function setQuery($query)
    {
        $this->query = $query;
        return $this;
    }

    /**
     * Get Offset.
     *
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @return bool
     */
    public function hasOffset()
    {
        return $this->offset !== null;
    }

    /**
     * Set Offset.
     *
     * @param int $offset
     * @return Request
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * Get Limit.
     *
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @return bool
     */
    public function hasLimit()
    {
        return $this->limit !== null;
    }

    /**
     * Set Limit.
     *
     * @param int $limit
     * @return Request
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Get OrderBy.
     *
     * @return OrderBy|array
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * @return bool
     */
    public function hasOrderBy()
    {
        return !empty($this->orderBy);
    }

    /**
     * Set OrderBy.
     *
     * @param OrderBy|array $orderBy
     * @return Request
     */
    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    /**
     * Get SummarizeBy.
     *
     * @return string
     */
    public function getSummarizeBy()
    {
        return $this->summarizeBy;
    }

    /**
     * @return bool
     */
    public function hasSummarizeBy()
    {
        return $this->summarizeBy !== null;
    }

    /**
     * Set SummarizeBy.
     *
     * @param string $summarizeBy
     * @return Request
     */
    public function setSummarizeBy($summarizeBy)
    {
        $this->summarizeBy = $summarizeBy;
        return $this;
    }

    /**
     * Get IncludeTotal.
     *
     * @return bool
     */
    public function getIncludeTotal()
    {
        return $this->includeTotal;
    }

    /**
     * @return string
     */
    protected function getIncludeTotalAsString()
    {
        return $this->includeTotal ? 'true' : 'false';
    }

    /**
     * @return bool
     */
    public function hasIncludeTotal()
    {
        return $this->includeTotal === true;
    }

    /**
     * Set IncludeTotal.
     *
     * @param bool $includeTotal
     * @return Request
     */
    public function setIncludeTotal($includeTotal)
    {
        $this->includeTotal = $includeTotal;
        return $this;
    }

    /**
     * @return string
     */
    protected function getQueryParam()
    {
        $query = $this->getQuery();
        if ($query == null) {
            return null;
        }
        $query = is_array($query) ? new Query($query) : $query;
        return 'query=' . rawurlencode((string)$query);
    }

    /**
     * @return string
     */
    protected function getOrderByParam()
    {
        $orderBy = $this->getOrderBy();
        if ($orderBy == null) {
            return null;
        }
        $orderBy = is_array($orderBy) ? new OrderBy($orderBy) : $orderBy;
        return 'order-by=' . rawurlencode((string)$orderBy);
    }
}
