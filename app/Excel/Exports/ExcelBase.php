<?php

namespace App\Excel\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Sheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExcelBase implements
    WithTitle,
    WithDrawings,
    WithEvents
{
    public $title = "";
    public $drawingsList = [];
    public $columnFormatList = [];
    public $freeze = "";
    public $orientation = "";

    /**
     * Set the title.
     *
     * @param  string $title
     *
     * @return void
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * Get the title.
     *
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }

    /**
     * Get array of drawings.
     *
     * @return array|Drawing[]
     */
    public function drawings()
    {
        return $this->drawingsList;
    }



    /**
     * Add a drawing.
     *
     * @param  $drawing
     *
     * @return void
     */
    public function addDrawing(Drawing $drawing)
    {
        $this->drawingsList[] = $drawing;
    }

    /**
     * Set the column format.
     *
     * @param  array $columnFormat
     *
     * @return void
     */
    public function setColumnFormat(array $columnFormat)
    {
        $this->columnFormatList = $columnFormat;
    }

    /**
     * Return the events we want to register.
     *
     * @return array
     */
    public function registerEvents(): array
    {
        $freeze = $this->freeze;
        $orientation = $this->orientation;
        return [
            AfterSheet::class => function(AfterSheet $event) use ($freeze, $orientation) {
                /** @var Sheet $sheet */
                //$sheet = $event->sheet;
                //$sheet = $event->getSheet();

                if ($freeze) {
                    $event->sheet->freezePane($freeze);
                }
                if ($orientation) {
                    $event->sheet->getPageSetup()->setOrientation($orientation);
                }
                foreach ($this->columnFormatList as $column=>$format) {
                    $column = trim($column);
                    //if (true) {
                    if (strpos($column, ":")) { // if this is a range

                        /** @var Worksheet $worksheet */
                        $worksheet = $event->sheet->getDelegate();

                        $worksheet->getStyle($column);
                        echo "selected cells: " . $worksheet->getSelectedCells() . "\n";

                        $worksheet->getStyle($column)       // TODO: This is not working for some reason. Need to figure out why.
                            ->getNumberFormat()
                            ->setFormatCode($format);

                        echo "set column $column to format: $format\n";
                    }
                    else {
                        $event->sheet->formatColumn($column, $format);
                    }
                }
                // styles
                // $worksheet->getStyle($column)->applyFromArray($style);
            },
        ];
    }

    /**
     * Freeze the rows above and to the left of cell (i.e B2 would freeze row 1 and column A).
     *
     * @param  string $cell
     *
     * @return void
     */
    public function setFreeze(string $cell)
    {
        $this->freeze = $cell;
    }

    /**
     * Get freeze
     *
     * @return string
     */
    public function getFreeze(): string
    {
        return $this->freeze;
    }



    /**
     * Set the orientation.
     *
     * @param  string $str - 'landscape' or 'portrait'
     *
     * @return void
     */
    public function setOrientation(string $str)
    {
        $this->orientation = $str;
    }

    /**
     * Get the Orientation
     *
     * @return string
     */
    public function getOrientation(): string
    {
        return $this->orientation;
    }



    /**
     * Freeze first column and row.
     *
     * @return void
     */
    public function freezeFirstRowAndColumn()
    {
        $this->freeze = "B2";
    }
}
