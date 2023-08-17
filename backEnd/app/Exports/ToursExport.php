<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromView;


class ToursExport implements FromView
{
    use Exportable;

    protected $tours;
    protected $columns;
    public function __construct($tours = null, $columns = null)
    {
        $this->tours = $tours;
        $this->columns = $columns;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
        return $this->tours;

    }

    public function view():View
    {

        //dd($this->columns);
        Log::info('info to export',[$this->tours, $this->columns]);
        return view('export.tours', [
            'tours' => $this->tours,
            'columns'=>$this->columns
        ]);
    }
}
