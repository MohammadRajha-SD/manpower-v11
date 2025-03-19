<?php

namespace App\DataTables;

use App\Models\ProviderSchedule;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProviderScheduleDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('SUN', function ($query) {
                $data = json_decode($query->data, true);
                if (isset($data['SUN']['start'], $data['SUN']['end'])) {
                    $start = Carbon::createFromFormat('H:i', $data['SUN']['start'])->format('g:i A');
                    $end = Carbon::createFromFormat('H:i', $data['SUN']['end'])->format('g:i A');
                    return $start . ' - ' . $end;
                }
                return 'N/A';
            })
            ->addColumn('MON', function ($query) {
                $data = json_decode($query->data, true);
                if (isset($data['MON']['start'], $data['MON']['end'])) {
                    $start = Carbon::createFromFormat('H:i', $data['MON']['start'])->format('g:i A');
                    $end = Carbon::createFromFormat('H:i', $data['MON']['end'])->format('g:i A');
                    return $start . ' - ' . $end;
                }
                return 'N/A';
            })
            ->addColumn('TUE', function ($query) {
                $data = json_decode($query->data, true);
                if (isset($data['TUE']['start'], $data['TUE']['end'])) {
                    $start = Carbon::createFromFormat('H:i', $data['TUE']['start'])->format('g:i A');
                    $end = Carbon::createFromFormat('H:i', $data['TUE']['end'])->format('g:i A');
                    return $start . ' - ' . $end;
                }
                return 'N/A';
            })
            ->addColumn('WED', function ($query) {
                $data = json_decode($query->data, true);
                if (isset($data['WED']['start'], $data['WED']['end'])) {
                    $start = Carbon::createFromFormat('H:i', $data['WED']['start'])->format('g:i A');
                    $end = Carbon::createFromFormat('H:i', $data['WED']['end'])->format('g:i A');
                    return $start . ' - ' . $end;
                }
                return 'N/A';
            })
            ->addColumn('THU', function ($query) {
                $data = json_decode($query->data, true);
                if (isset($data['THU']['start'], $data['THU']['end'])) {
                    $start = Carbon::createFromFormat('H:i', $data['THU']['start'])->format('g:i A');
                    $end = Carbon::createFromFormat('H:i', $data['THU']['end'])->format('g:i A');
                    return $start . ' - ' . $end;
                }
                return 'N/A';
            })
            ->addColumn('FRI', function ($query) {
                $data = json_decode($query->data, true);
                if (isset($data['FRI']['start'], $data['FRI']['end'])) {
                    $start = Carbon::createFromFormat('H:i', $data['FRI']['start'])->format('g:i A');
                    $end = Carbon::createFromFormat('H:i', $data['FRI']['end'])->format('g:i A');
                    return $start . ' - ' . $end;
                }
                return 'N/A';
            })
            ->addColumn('SAT', function ($query) {
                $data = json_decode($query->data, true);
                if (isset($data['SAT']['start'], $data['SAT']['end'])) {
                    $start = Carbon::createFromFormat('H:i', $data['SAT']['start'])->format('g:i A');
                    $end = Carbon::createFromFormat('H:i', $data['SAT']['end'])->format('g:i A');
                    return $start . ' - ' . $end;
                }
                return 'N/A';
            })
            ->addColumn('provider', function ($query) {
                return ucwords($query->provider?->name);
            })
            ->addColumn('action', function ($query) {
                $editBtn = "<a href='" . route('admin.provider-schedules.edit', $query->id) . "' class='btn btn-success btn-sm'><i class='far fa-edit'></i></a>";
                $deleteBtn = "<a href='" . route('admin.provider-schedules.destroy', $query->id) . "' class='btn btn-danger btn-sm  ml-2 delete-item'><i class='fa fa-trash'></i></a>";
                return $editBtn . $deleteBtn;
            })
            ->rawColumns(['action', 'data'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(ProviderSchedule $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('providerschedule-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('provider'),
            Column::make('SUN'),
            Column::make('MON'),
            Column::make('TUE'),
            Column::make('WED'),
            Column::make('THU'),
            Column::make('FRI'),
            Column::make('SAT'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(75)
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'ProviderSchedule_' . date('YmdHis');
    }
}
