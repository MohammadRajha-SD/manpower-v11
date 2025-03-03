<?php

namespace App\DataTables;

use App\Models\ContactUs;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ContactUsDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
        ->addColumn('image', function ($query) {
            return image_item($query);
        })
        ->addColumn('name', function ($query) {
            return ucwords($query->name);
        })
        ->addColumn('type', function ($query) {
            return ucwords($query->type);
        })
        ->addColumn('email', function ($query) {
            return $query->email;
        })
        ->addColumn('phone', function ($query) {
            return $query->phone ?? 'N/A';
        })
        ->addColumn('message', function ($query) {
            return $query->message;
        })
        ->addColumn('user', function ($query) {
            return $query->user?->name ?? 'N/A';
        })

        ->addColumn('service', function ($query) {
            return $query->service?->name ?? 'N/A';
        })
        ->addColumn('updated_at', function ($query) {
            return $query->updated_at?->diffForHumans();
        })
        ->rawColumns(['image'])
        ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(ContactUs $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('contactus-table')
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
            Column::make('image'),
            Column::make('name'),
            Column::make('type'),
            Column::make('email'),
            Column::make('phone'),
            Column::make('message'),
            Column::make('user'),
            Column::make('service'),
            Column::make('updated_at'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'ContactUs_' . date('YmdHis');
    }
}
