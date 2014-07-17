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

class Response
{
    /**
     * Body of PuppetDB response decoded from JSON.
     *
     * @var mixed
     */
    protected $data;

    /**
     * Total of matching records if provided (used for paging).
     *
     * @var int
     */
    protected $total;

    /**
     * @param array $data
     * @param int   $total
     */
    public function __construct(array $data = null, $total = null)
    {
        $this->setData($data);
        $this->setTotal($total);
    }

    /**
     * Get Data.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set Data.
     *
     * @param mixed $data
     * @return Response
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Get total records count.
     *
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set total records count.
     *
     * @param int $total
     * @return Response
     */
    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }
}
