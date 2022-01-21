<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    /**
     * @param $filterName
     * @param $value
     * @param $collectionName
     * @return void
     */
    public function setCollectionFilter($filterName, $value, $collectionName)
    {
        $this->collectionFilters[$collectionName][$filterName] = $value;
    }

    /**
     * @param $filterName
     * @param $collectionName
     * @return mixed|null
     */
    public function getCollectionFilter($filterName, $collectionName)
    {
        if ($this->collectionFilterExists($filterName, $collectionName)) {
            return $this->collectionFilters[$collectionName][$filterName];
        }

        return null;
    }

    /**
     * @param $filterName
     * @param $collectionName
     * @return bool
     */
    public function collectionFilterExists($filterName, $collectionName): bool
    {
        if (array_key_exists($filterName, $this->collectionFilters[$collectionName])) {
            return true;
        }

        return false;
    }

    /**
     * @param $collectionFilters
     * @param $collectionName
     * @return void
     */
    public function updateCollectionFilters($collectionFilters, $collectionName)
    {
        if ($collectionFilters) {
            foreach ($collectionFilters as $key => $value) {
                if ($this->collectionFilterExists($key, $collectionName)) {
                    $this->setCollectionFilter($key, $value, $collectionName);
                }
            }
        }
    }
}
