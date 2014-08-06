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
namespace KmbPuppetDb\Query;

class Query
{
    /** @var array */
    protected $data = [];

    public function __construct(array $data = array())
    {
        $this->setData($data);
    }

    public function __toString()
    {
        return $this->convertQueryDataToString($this->getData());
    }

    /**
     * Get Data.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set Data.
     *
     * @param array $data
     * @return Query
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param $data
     * @return array
     */
    private function processElement($data)
    {
        return array_map(
            function ($element) {
                if ($element instanceof Query) {
                    return (string)$element;
                } elseif (is_array($element)) {
                    $subQuery = new Query($element);
                    return (string)$subQuery;
                } elseif (is_bool($element)) {
                    return $element ? 'true' : 'false';
                }
                return '"' . $element . '"';
            },
            $data
        );
    }

    /**
     * @param $data
     * @return string
     */
    private function convertQueryDataToString($data)
    {
        $body = implode(', ', $this->processElement($data));
        return empty($body) ? '' : '[' . $body . ']';
    }
}
