<?php

namespace App\DataTables;

use App\Models\Exam;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ResultsDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('created_at', function($data) {
                return Carbon::createFromTimeString($data->created_at)->format('Y-m-d H:i:s');//date('YmdHis', $data->created_at);
            })
            ->addColumn('percentage', function($data) {
                return $data->percentage;
            })
            ->addColumn('name', function($data) {
                return $data->name;
            })
            ->addColumn('attempt', function($data) {
                return $data->attempt;
            })
            ->addColumn('Result', function($data) {
                return $data->percentage >= $this->exam->pass_percentage ? 'Pass' : 'Fail';
            })
            ->filterColumn('Result', function($query, $keyword) {
                if (preg_match('/\s*'.$keyword.'\s*/i', 'pass')) {
                    $query->where('percentage', '>=', $this->exam->pass_percentage);
                } elseif (preg_match('/\s*'.$keyword.'\s*/i', 'fail')) {
                    $query->where('percentage', '<', $this->exam->pass_percentage);
                } else {

                }
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Exam $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Exam $model)
    {
        return $this->exam->students_shallow()->select(['name', 'percentage', 'exam_user.created_at', 'attempt', DB::raw("'Result' as Result")]);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('results-table')
                    ->addTableClass('table-striped bg-white')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('<"row"<"col-md-12"B><"col-md-6"l><"col-md-6"f>>rt<"bottom"ip>')
                    ->orderBy(2, 'asc')
                    ->buttons(
                        Button::make('export'),
                        Button::make('print'),
                        Button::make('reload'),
                    );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::computed('id'),
            Column::make('created_at', 'exam_user.created_at')
             ->title('Date'),
            Column::make('name', 'users.name')
            ->title('Name'),
            Column::make('percentage', 'exam_user.percentage')
            ->title('Percentage'),
            Column::make('attempt', 'exam_user.attempt')
            ->title('Attempt'),
            Column::make('Result')
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Results_' . date('YmdHis');
    }

    /**
     * PDF version of the table using print preview blade template.
     *
     * @return mixed
     */
    public function snappyPdf()
    {
        /** @var \Barryvdh\Snappy\PdfWrapper $snappy */
        $snappy      = app('snappy.pdf.wrapper');
        $options     = config('datatables-buttons.snappy.options');
        $orientation = config('datatables-buttons.snappy.orientation');
        $snappy->setTemporaryFolder('/home/admin/tmp');
        $snappy->setOptions($options)->setOrientation($orientation);

        return $snappy->loadHTML($this->printPreview())->download($this->getFilename() . '.pdf');
    }
}
