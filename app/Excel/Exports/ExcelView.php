<?php


namespace App\Excel\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithDrawings;

class ExcelView extends ExcelBase  implements FromView, WithTitle, WithDrawings
{
    public $viewData = [];
    public $viewName = "";

    /**
     * ExcelView constructor.
     *
     * @param  string $viewName
     * @param  array $viewData
     *
     * @return void
     */
    public function __construct(string $viewName="", array $viewData=[])
    {
        $this->viewName = $viewName;
        $this->viewData = $viewData;
    }

    /**
     * Return the rendered view.
     *
     * @return View
     */
    public function view(): View
    {
        return view($this->viewName, $this->viewData);
    }

    /**
     * Set the view name.
     *
     * @param  $name
     *
     * @return void
     */
    public function setViewName($name)
    {
        $this->viewName = $name;
    }

    /**
     * Set the view data.
     *
     * @param  $data
     *
     * @return void
     */
    public function setViewData($data)
    {
        $this->viewData = $data;
    }

    /**
     * Set the view name and view data.
     *
     * @param  string $viewName
     * @param  array $viewData
     *
     * @return void
     */
    public function LoadView(string $viewName, array $viewData = [])
    {
        $this->setViewName($viewName);
        $this->setViewData($viewData);
    }
}
