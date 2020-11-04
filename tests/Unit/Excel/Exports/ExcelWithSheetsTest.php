<?php

namespace Tests\Unit\Excel\Exports;

use Random;
use Storage;
use Tests\Unit\TestCase;
use App\Excel\Exports\ExcelView;
use App\Excel\Exports\ExcelWithSheets;
use App\Excel\Exports\ExcelCollection;
use App\Excel\Imports\ExcelToCollectionImport;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ExcelWithSheetsTest extends TestCase
{
    /**
     * Test the export via collection.
     *
     * @return void
     */
    public function testExportExcelCollection()
    {
        $data = [
            ['food', 'color'],
            ['banana', 'yellow'],
            ['apple', 'red'],
            ['grapes', 'green']
        ];

        // create the excel file contents
        $excel = ExcelWithSheets::create('Foods', function(ExcelWithSheets $excel) use ($data) {
            $excel->sheetFromCollection('First Sheet', function(ExcelCollection $sheet) use ($data) {
                $sheet->setCollection($data);
            });
        })->raw(ExcelWithSheets::XLS);


        // save the file as an excel file
        $newFileName = $this->tempPath() . DIRECTORY_SEPARATOR .  'testExportViaCollection' . $this->guidv4() . ".xls";
        $fh = fopen($newFileName, "w");
        fwrite($fh, $excel);
        fclose($fh);

        // read the excel file
        $sheet = new ExcelToCollectionImport();
        $sheet->import($newFileName);
        $collections = $sheet->getCollections();

        // compare the contents
        foreach ($data as $row=>$record) {
            foreach ($record as $column => $value) {
                $this->assertEquals($collections[0][$row][$column], $value);
            }
        }

        unlink($newFileName);
    }
//
//    /**
//     * Test the excel export using a view.
//     *
//     * @returns void
//     */
//    public function testExcelExportExcelView()
//    {
//        $data = [
//            ['food', 'color'],
//            ['banana', 'yellow'],
//            ['apple', 'red'],
//            ['grapes', 'green']
//        ];
//        $excel = ExcelWithSheets::create('Food Test', function(ExcelWithSheets $excel) use ($data) {
//            $excel->sheet('Food', function(ExcelView $sheet) use ($data) {
//                $dataCopy = $data;
//                $header = array_shift($dataCopy);
//                $sheet->loadView('tests.table', ['header' => $header, 'data' => $dataCopy]);
//            });
//        })->raw(ExcelWithSheets::XLS);
//
//        // save the file as an excel file
//        $newFileName = $this->tempPath() . DIRECTORY_SEPARATOR .  'testExcelExportViaView' . $this->guidv4() . ".xls";
//
//        $fh = fopen($newFileName, "w");
//        fwrite($fh, $excel);
//        fclose($fh);
//
//        // read the excel file
//        $sheet = new ExcelToCollectionImport();
//        $sheet->import($newFileName);
//        $collections = $sheet->getCollections();
//
//        // compare the contents
//        foreach ($data as $row=>$record) {
//            foreach ($record as $column => $value) {
//                $this->assertEquals($collections[0][$row][$column], $value);
//            }
//        }
//
//        unlink($newFileName);
//    }
//
//    /**
//     * Test the ExcelWithSheets save function.
//     *
//     * @return void
//     */
//    public function testExportExcelWithSheetsSaveFunction()
//    {
//        $data = [
//            ['food', 'color'],
//            ['banana', 'yellow'],
//            ['apple', 'red'],
//            ['grapes', 'green']
//        ];
//
//        $newFileName = $this->tempPath() . DIRECTORY_SEPARATOR .  'testExportExcelWithSheetsSaveFunction' . $this->guidv4() . ".xlsx";
//
//        // create the excel file contents
//        $excel = ExcelWithSheets::create('Foods', function(ExcelWithSheets $excel) use ($data) {
//            $excel->sheetFromCollection('First Sheet', function(ExcelCollection $sheet) use ($data) {
//                $sheet->setCollection($data);
//            });
//        })->save($newFileName, ExcelWithSheets::XLSX);
//
//        // read the excel file into a collection.
//        $sheet = new ExcelToCollectionImport();
//        $sheet->import($newFileName);
//        $collections = $sheet->getCollections();
//
//        // compare the contents
//        foreach ($data as $row=>$record) {
//            foreach ($record as $column => $value) {
//                $this->assertEquals($collections[0][$row][$column], $value);
//            }
//        }
//
//        unlink($newFileName);
//    }
//
    /**
     * Test the setFreeze function.
     *
     * @return void
     */
    public function testExportExcelSetFreeze()
    {
        $data = [
            ['food', 'color'],
            ['banana', 'yellow'],
            ['apple', 'red'],
            ['grapes', 'green']
        ];

        /** @var ExcelWithSheets $excel */
        $excel = ExcelWithSheets::create('Foods', function(ExcelWithSheets $excel) use ($data) {
            $excel->sheetFromCollection('First Sheet', function(ExcelCollection $sheet) use ($data) {
                $sheet->setCollection($data);
                $sheet->setFreeze('A2');
            });
        });

        /** @var ExcelCollection $sheet */
        $sheet = $excel->sheets()[0];
        $this->assertEquals($sheet->getFreeze(), 'A2');
    }

    /**
     * Test the setFreeze function.
     *
     * @return void
     */
    public function testExportExcelFormat()
    {
        $datetime = new\DateTime();
        $data = [
            ['food', 'color', 123456.7777777, 188.444444444, 188.444444444, 123456.7777777, 55, $datetime],
            ['banana', 'yellow', 123456.7777777, 188.555555555, 188.444444444, 123456.555555555, 66, $datetime],
            ['apple', 'red', 123456.7777777, 188.444444444, 188.444444444, 123456.7777777, 77, $datetime],
            ['grapes', 'green', 123456.7777777, 188.44444444, 188.444444444, 123456.7777777, 88, $datetime]
        ];

        $newFileName = $this->tempPath() . DIRECTORY_SEPARATOR .  'testExportExcelFormat' . $this->guidv4() . ".xlsx";

        /** @var ExcelWithSheets $excel */
        $excel = ExcelWithSheets::create('Foods', function(ExcelWithSheets $excel) use ($data) {
            $excel->sheetFromCollection('First Sheet', function(ExcelCollection $sheet) use ($data) {
                $sheet->setCollection($data);
                $sheet->setColumnFormat([
                    'A:B'     => NumberFormat::FORMAT_TEXT, //'@',             // Text
                    'C' => NumberFormat::FORMAT_ACCOUNTING_USD, //'_(#,##0.00_);_(\(#,##0.00\);_("-"??_);_(@_)', // Amounts: Accounting
                    'D:E' => NumberFormat::FORMAT_ACCOUNTING_USD, //'_(#,##0.00_);_(\(#,##0.00\);_("-"??_);_(@_)', // Amounts: Accounting
                    'F' => '_(#,##0_);_(\(#,##0\);_("-"??_);_(@_)', // Amounts: Accounting (Integer)
                    'G' => '0', // Just plain numbers
                    'H'   => 'm/d/yy h:mm',
                ]);
            });
        });

        $excel->save($newFileName, ExcelWithSheets::XLSX);

        $this->assertFileExists($newFileName);

        $method = __METHOD__;

        echo <<<__THIS
##########################################################
Method: $method
Created this Excel file: $newFileName
Note that Column C has the correct format.
Column D:E should also have the same formatting but it doesn't.
This is the problem. The library doesn't handle ranges out of the box.
For example, this does not work: \$sheet->setColumnFormat(['D:E' => NumberFormat::FORMAT_ACCOUNTING_USD])

The code responsible for formatting the numbers is app/Excel/Exports/ExcelBase.php,  registerEvents() function, line 113
##########################################################

__THIS;

        //unlink($newFileName);
    }



    /**
     * Test the setOrientation function.
     *
     * @return void
     */
    public function testExportExcelSetOrientation()
    {
        $data = [
            ['food', 'color'],
            ['banana', 'yellow'],
            ['apple', 'red'],
            ['grapes', 'green']
        ];

        /** @var ExcelWithSheets $excel */
        $excel = ExcelWithSheets::create('Foods', function(ExcelWithSheets $excel) use ($data) {
            $excel->sheetFromCollection('First Sheet', function(ExcelCollection $sheet) use ($data) {
                $sheet->setCollection($data);
                $sheet->setOrientation('landscape');
            });
        });

        /** @var ExcelCollection $sheet */
        $sheet = $excel->sheets()[0];
        $this->assertEquals($sheet->getOrientation(), 'landscape');
    }

    /**
     * Append the path to the filename.
     *
     * @param  string $disk
     * @param  string $filename
     *
     * @return string
     */
    protected function getFullPathFileName($disk='temp', $filename="")
    {
        if ($filename) {
            return sprintf('%s%s', Storage::disk($disk)->getDriver()->getAdapter()->getPathPrefix(), $filename);
        }
        return Storage::disk($disk)->getDriver()->getAdapter()->getPathPrefix();
    }

    /**
     * Make a random excel filename
     * @return string
     */
    protected function makeRandomExcelFileName()
    {
        return sprintf('%s.xls', $this->guidv4());
    }

    function guidv4()
    {
        if (function_exists('com_create_guid') === true)
            return trim(com_create_guid(), '{}');

        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
