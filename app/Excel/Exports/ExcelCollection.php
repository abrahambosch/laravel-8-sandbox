<?php


namespace App\Excel\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExcelCollection extends ExcelBase implements FromCollection, WithTitle, WithDrawings
{
    public $collection = null;

    /**
     * ExcelCollection constructor.
     *
     * @param  array $collection
     *
     * @return void
     */
    public function __construct($collection = [])
    {
        $this->setCollection($collection);
    }

    /**
     * Set the collection from an array.
     *
     * @param  array|Collection $arr
     *
     * @return void
     */
    public function fromArray($arr)
    {
        $this->setCollection($arr);
    }

    /**
     * Get the collection.
     *
     * @return Collection|null
     */
    public function collection()
    {
        return $this->collection;
    }

    /**
     * Get the collection.
     *
     * @return Collection
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * Set the collection.
     *
     * @param  null $collection
     *
     * @return void
     */
    public function setCollection($collection): void
    {
        if ($collection instanceof Collection) {
            $this->collection = $collection;
        }
        else {
            $this->collection = new Collection($collection);
        }
    }
}
