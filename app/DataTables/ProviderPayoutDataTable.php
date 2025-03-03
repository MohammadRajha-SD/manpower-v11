<?php

namespace App\DataTables;

use App\Models\ProviderPayout;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;

class ProviderPayoutDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('provider', function ($query) {
                return ucwords($query->provider?->name);
            })
            ->addColumn('amount', function ($query) {
                return ($query->amount == (int)$query->amount ? (int)$query->amount : $query->amount ). setting('default_currency_code', '$');
            })
            ->addColumn('method', function ($query) {
                return $query->method;
            })
            ->addColumn('paid_date', function ($query) {
                return Carbon::parse($query->paid_date)->diffForHumans();
            })
            ->addColumn('note', function ($query) {
                return $query->note;
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(ProviderPayout $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('providerpayout-table')
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
            Column::make('method'),
            Column::make('amount'),
            Column::make('paid_date'),
            Column::make('note'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'ProviderPayout_' . date('YmdHis');
    }
}
