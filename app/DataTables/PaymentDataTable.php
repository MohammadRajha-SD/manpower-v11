<?php

namespace App\DataTables;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;

class PaymentDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
        ->addColumn('amount', function ($query) {
            return ($query->amount == (int)$query->amount ? (int)$query->amount : $query->amount ). setting('default_currency_code', '$');
        })
        ->addColumn('description', function ($query) {
            return $query->description;
        })
        ->addColumn('user', function ($query) {
            return ucwords($query->user?->name);
        })
        ->addColumn('payment_method', function ($query) {
            return ucwords($query->payment_method?->name);
        })
        ->addColumn('payment_status', function ($query) {
            return ucwords($query->payment_status?->status);
        })
        ->addColumn('updated_at', function ($query) {
            return $query->updated_at?->diffForHumans();
        })
        ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Payment $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('updated_at', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('payment-table')
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
            Column::make('amount'),
            Column::make('description'),
            Column::make('user'),
            Column::make('payment_method'),
            Column::make('payment_status'),
            Column::make('updated_at'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Payment_' . date('YmdHis');
    }
}
