<?php

namespace Tests\Unit\Excel\Imports;

use Tests\Unit\TestCase;
use Illuminate\Support\Collection;
use App\Excel\Imports\ExcelImport;
use App\Excel\Imports\ExcelToCollectionImport;

class ExcelImportTest extends TestCase
{
    /**
     * Test ability to read from the first sheet by name.
     *
     * @return void
     */
    public function testExcelImportReadFirstSheet()
    {
        $fullPathFileName = base_path("/tests/data/excel/example.xlsx");
        $sheet = new ExcelToCollectionImport();
        $excelImport = new ExcelImport();
        $excelImport->addSheet($sheet, 'Contacts');
        $excelImport->import($fullPathFileName);
        /** @var Collection $collection */
        $collection = $sheet->getCollection();

        $expectedResults = [
          ['name', 'age', 'phone'],
          ['Chuck Norris', '70', '714-555-6666'],
          ['Bob Ross', '75', '949-666-7777']
        ];
        foreach ($expectedResults as $row=>$record) {
            foreach ($record as $column=>$value) {
                $this->assertEquals($collection[$row][$column], $value);
            }
        }
    }

    /**
     * Test ability to read from the second sheet by name.
     *
     * @return void
     */
    public function testExcelImportReadSecondSheet()
    {
        $fullPathFileName =  base_path("/tests/data/excel/example.xlsx");
        $sheet = new ExcelToCollectionImport();
        $excelImport = new ExcelImport();
        $excelImport->addSheet($sheet, 'Foods');
        $excelImport->import($fullPathFileName);
        /** @var Collection $collection */
        $collection = $sheet->getCollection();

        $expectedResults = [
            ['food', 'color'],
            ['banana', 'yellow'],
            ['apple', 'red'],
            ['grapes', 'green']
        ];
        foreach ($expectedResults as $row=>$record) {
            foreach ($record as $column=>$value) {
                $this->assertEquals($collection[$row][$column], $value);
            }
        }
    }
}
