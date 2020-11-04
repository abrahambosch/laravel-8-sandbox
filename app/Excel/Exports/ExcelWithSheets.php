<?php


namespace App\Excel\Exports;;

use Excel;
use App\Servo\Exceptions\ExcelExportException;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Helpers\FileTypeDetector;
use Maatwebsite\Excel\Excel as ExcelBase;

class ExcelWithSheets implements WithMultipleSheets, WithProperties
{
    use Exportable;

    const XLS = ExcelBase::XLS;
    const XLSX = ExcelBase::XLSX;
    const CSV = ExcelBase::CSV;

    public $fileName = "";
    public $propertyData = [];
    public $sheetData = [];

    /**
     * Get the list of sheets.
     *
     * @return array
     */
    public function sheets(): array
    {
        return $this->sheetData;
    }

    /**
     * Add a sheet.
     *
     * @param  $sheet
     *
     * @return $this
     */
    public function addSheet($sheet)
    {
        $this->sheetData[] = $sheet;
        return $this;
    }

    /**
     * Set a property.
     *
     * @param  $name
     * @param  $value
     *
     * @return $this
     */
    public function setProperty($name, $value)
    {
        $this->propertyData[$name] = $value;
        return $this;
    }

    /**
     * Set the title.
     *
     * @param  $value
     *
     * @return $this
     */
    public function setTitle($value)
    {
        $this->propertyData['title'] = $value;
        return $this;
    }

    /**
     * Set the creator.
     *
     * @param  string $value
     *
     * @return $this
     */
    public function setCreator(string $value)
    {
        $this->propertyData['creator'] = $value;
        return $this;
    }

    /**
     * Set the company.
     *
     * @param  string $value
     *
     * @return $this
     */
    public function setCompany(string $value)
    {
        $this->propertyData['company'] = $value;
        return $this;
    }

    /**
     * Set the description.
     *
     * @param  string $value
     *
     * @return $this
     */
    public function setDescription(string $value)
    {
        $this->propertyData['description'] = $value;
        return $this;
    }

    /**
     * Get the properties.
     *
     * @return array
     */
    public function properties(): array
    {
        return $this->propertyData;
    }

    /**
     * Get the filename.
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Get the filename.
     *
     * @param  string $fileName
     *
     * @return void
     */
    public function setFileName(string $fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * Create and add a new sheet.
     *
     * @param  string $sheetName
     * @param  callable $callable
     *
     * @return void
     */
    public function sheet(string $sheetName, callable $callable)
    {
        $sheet = new ExcelView();
        $sheet->setTitle($sheetName);
        $callable($sheet);
        $this->addSheet($sheet);
    }

    /**
     * Create and add a new sheet from a collection.
     *
     * @param  string $sheetName
     * @param  callable $callable
     *
     * @return void
     */
    public function sheetFromCollection(string $sheetName, callable $callable)
    {
        $sheet = new ExcelCollection();
        $sheet->setTitle($sheetName);
        $callable($sheet);
        $this->addSheet($sheet);
    }

    /**
     * Create a new object.
     *
     * @param  string $fileName
     * @param  callable $callable
     *
     * @return ExcelWithSheets
     */
    public static function create(string $fileName, callable $callable)
    {
        $excel = new self;
        $excel->setFileName($fileName);
        $callable($excel);
        return $excel;
    }


    /**
     * Save to the local filesystem.
     *
     * @param  $fullPathFileName - filename with the full path
     * @param  null $writerType
     *
     * @throws ExcelExportException
     * @throws \Maatwebsite\Excel\Exceptions\NoTypeDetectedException
     *
     * @return void
     */
    public function save($fullPathFileName, $writerType=null)
    {
        if (empty($writerType)) {
            $writerType = FileTypeDetector::detectStrict($fullPathFileName, $writerType);
        }
        $contents = $this->raw($writerType);
        $fp = fopen($fullPathFileName, "w");
        if (!fwrite($fp, $contents)) {
            throw new ExcelExportException("failed to save excel file: $fullPathFileName");
        }
        fclose($fp);
    }
}
