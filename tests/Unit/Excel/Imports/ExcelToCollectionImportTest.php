<?php

namespace Tests\Unit\Excel\Imports;

use Carbon\Carbon;
use Tests\Unit\TestCase;
use Illuminate\Support\Collection;
use App\Excel\Imports\ExcelToCollectionImport;

class ExcelToCollectionImportTest extends TestCase
{
    /**
     * Test ability to read from Excel with multiple sheets.
     *
     * @return void
     */
    public function testExcelImportReadAllSheet()
    {
        $fullPathFileName = base_path("/tests/data/excel/example.xlsx");
        $sheet = new ExcelToCollectionImport();
        $sheet->import($fullPathFileName);
        /** @var Collection $collection */
        $collections = $sheet->getCollections();

        $expectedResultsSheet1 = [
            ['name', 'age', 'phone'],
            ['Chuck Norris', '70', '714-555-6666'],
            ['Bob Ross', '75', '949-666-7777']
        ];
        $expectedResultsSheet2 = [
            ['food', 'color'],
            ['banana', 'yellow'],
            ['apple', 'red'],
            ['grapes', 'green']
        ];
        $expectedResults = [$expectedResultsSheet1, $expectedResultsSheet2];
        foreach ($expectedResults as $sheetIndex=>$sheetRecord) {
            foreach ($sheetRecord as $row=>$record) {
                foreach ($record as $column => $value) {
                    $this->assertEquals($collections[$sheetIndex][$row][$column], $value);
                }
            }
        }
    }

    /**
     * Test ability to read from Excel with multiple sheets.
     *
     * @return void
     */
    public function testExcelImportReadCsv()
    {
        $fullPathFileName = base_path("/tests/data/excel/example.csv");
        $sheet = new ExcelToCollectionImport();
        $sheet->import($fullPathFileName);
        /** @var Collection $collection */
        $collections = $sheet->getCollections();

        $expectedResults = [
            ['food', 'color'],
            ['banana', 'yellow'],
            ['apple', 'red'],
            ['grapes', 'green']
        ];

        foreach ($expectedResults as $row=>$record) {
            foreach ($record as $column => $value) {
                $this->assertEquals($collections[0][$row][$column], $value);
            }
        }

    }

    /**
     * Test ability to detect date fields
     *
     * @return void
     */
//    public function testExcelImportDateField()
//    {
//        $fullPathFileName = base_path("/tests/data/excel/example2.xls");
//        $sheet = new ExcelToCollectionImport();
//        $sheet->import($fullPathFileName);
//        /** @var Collection $collection */
//        $collections = $sheet->getCollections();
//        print_r($collections);
//        //$this->assertInstanceOf(Carbon::class, $collections[0][1][4]);
//        $this->assertEquals("9/28/2020", $collections[0][1][4]);
//    }
}
