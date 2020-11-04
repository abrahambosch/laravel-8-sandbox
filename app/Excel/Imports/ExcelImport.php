<?php

namespace App\Excel\Imports;

use Excel;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExcelImport implements WithMultipleSheets
{
    public $sheetList = [];

    /**
     * Add an excel sheet.
     *
     * @param  $sheet
     * @param  string $sheetName
     *
     * @return void
     */
    public function addSheet($sheet, $sheetName="")
    {
        if ($sheetName === "") {
            $this->sheetList[] = $sheet;
        }
        else {
            $this->sheetList[$sheetName] = $sheet;
        }
    }

    /**
     * Get the list of sheets.
     *
     * @return array
     */
    public function sheets(): array
    {
        return $this->sheetList;
    }

    /**
     * Import excel file.
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
