<?php

namespace App\DataTables;

use App\Models\Provider;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProviderStatisticDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('image', function ($query) {
                return image_item(null);
            })
            ->addColumn('provider', function ($query) {
                return $query->name;
            })
            ->addColumn('total_earning', function ($query) {
                return number_format($this->statistics['total_earnings'] ?? 0, 2) . setting('default_currency', '$');
            })
            ->addColumn('total_bookings', function ($query) {
                return number_format($this->statistics['total_bookings'] ?? 0, 2) . setting('default_currency', '$');
            })
            ->addColumn('total_providers', function ($query) {
                return number_format($this->statistics['total_providers'] ?? 0, 2) . setting('default_currency', '$');
            })
            ->addColumn('total_services', function ($query) {
                return number_format($this->statistics['total_services'] ?? 0, 2) . setting('default_currency', '$');
            })
            // ->addColumn('action', 'providerstatistic.action')
            ->rawColumns(['image', 'total_earning_provider'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Provider $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('providerstatistic-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(1)
            ->selectStyleSingle()
            ->parameters([
                'statistics' => $this->request()->get('statistics'),
            ])
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
            Column::make('image'),
            Column::make('provider'),
            Column::make('total_earning'),
            Column::make('total_providers'),
            Column::make('total_bookings'),
            Column::make('total_services'),
            // Column::computed('action')
            //     ->exportable(false)
            //     ->printable(false)
            //     ->width(75)
            //     ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'ProviderStatistic_' . date('YmdHis');
    }
}
