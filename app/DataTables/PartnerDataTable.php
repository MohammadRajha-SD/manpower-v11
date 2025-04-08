<?php

namespace App\DataTables;

use App\Models\Partner;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PartnerDataTable extends DataTable
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
                return image_item($query, '100%', '200px', 'object-fit:cover;', 'rounded shadow');
            })
            ->addColumn('title', function ($query) {
                return ucwords($query->title);
            })
            ->addColumn('enabled', function ($query) {
                return isActive($query->enabled, 'success', 'danger');
            })
            ->addColumn('updated_at', function ($query) {
                return $query->updated_at->diffForHumans();
            })
            ->addColumn('action', function ($query) {
                $editBtn = "<a href='" . route('admin.partners.edit', $query->id) . "' class='btn btn-success btn-sm'><i class='far fa-edit'></i></a>";
                $deleteBtn = "<a href='" . route('admin.partners.destroy', $query->id) . "' class='btn btn-danger btn-sm  ml-2 delete-item'><i class='fa fa-trash'></i></a>";
                return $editBtn . $deleteBtn;
            })
            ->rawColumns(['action', 'image','enabled'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Partner $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('partner-table')
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
            Column::make('title'),
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
        return 'Partner_' . date('YmdHis');
    }
}
