<?php

namespace App\DataTables;

use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PaymentMethodDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
        ->addColumn('logo', function ($query) {
            return image_item($query->logo);
        })
        ->addColumn('name', function ($query) {
            return $query->name;
        })
        ->addColumn('description', function ($query) {
            return $query->description;
        })
        ->addColumn('default', function ($query) {
            return isActive($query->default, 'success', 'danger');
        })
        ->addColumn('enabled', function ($query) {
            return isActive($query->enabled, 'success', 'danger');
        })
        ->addColumn('updated_at', function ($query) {
            return $query->updated_at->diffForHumans();
        })
        ->addColumn('action', function ($query) {
            $editBtn = "<a href='" . route('admin.payment-methods.edit', $query->id) . "' class='btn btn-success btn-sm'><i class='far fa-edit'></i></a>";
            $deleteBtn = "<a href='" . route('admin.payment-methods.destroy', $query->id) . "' class='btn btn-danger btn-sm  ml-2 delete-item'><i class='fa fa-trash'></i></a>";
            return $editBtn . $deleteBtn;
        })
        ->rawColumns(['action', 'enabled', 'default','logo'])
        ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(PaymentMethod $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('paymentmethod-table')
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
            Column::make('logo'),
            Column::make('name'),
            Column::make('description'),
            Column::make('route'),
            Column::make('order'),
            Column::make('default'),
            Column::make('enabled'),
            Column::make('updated_at')->addClass('text-center'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(75)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'PaymentMethod_' . date('YmdHis');
    }
}
