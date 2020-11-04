<?php

namespace App\Excel\Imports;


use Excel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class ExcelToCollectionImport implements ToCollection, WithCalculatedFormulas
{
    public $collections = null;

    public function __construct()
    {
        $this->collections = new Collection([]);
    }

    /**
     * mThis method called by excel library once for each Excel sheet with the data from the sheet.
     *
     * @param  Collection $collection
     *
     * @return void
     */
    public function collection(Collection $collection)
    {
        $this->collections->push($this->fixDates($collection));
    }

    public function fixDates(Collection $collection)
    {
        return $collection;
        $newCollection = new Collection();
        $isDateMap = [];
        $header = $collection->shift();
        foreach ($header as $k=>$v) {
            $isDateMap[$k] = $this->isDate($v);
        }
        $newCollection->push($header);
        foreach ($collection as $row) {
            $newRow = new Collection();
            foreach ($row as $i=>$v) {
                if (!empty($v) && $isDateMap[$i]) {
                    $newRow[$i] = Carbon::instance(Date::excelToDateTimeObject($v));
                }
                else {
                    $newRow[$i] = $v;
                }
            }
            $newCollection->push($newRow);
        }
        return $newCollection;
    }

    public function isDate($fieldName)
    {
        $fieldName = strtolower($fieldName);
        if (preg_match('/date/i', $fieldName)) {
            return true;
        }
        $dateFields = ['dob'];
        if (in_array($fieldName, $dateFields)) {
            return true;
        }
        return false;
    }

    /**
     * Get the imported collection by index.
     *
     * @param integer|void $index
     *
     * @return Collection
     */
    public function getCollection($index=0): Collection
    {
        return $this->collections[$index];
    }


    /**
     * Get the imported collections - use this if multiple sheet import.
     *
     * @return Collection
     */
    public function getCollections(): Collection
    {
        return $this->collections;
    }

    /**
     * Import an excel file.
     *
     * @param string $filename
     *
     * @return Excel
     */
    public function import(string $filename)
    {
        return Excel::import($this, $filename);
    }
}
