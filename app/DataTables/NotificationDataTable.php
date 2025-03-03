<?php

namespace App\DataTables;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Str;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class NotificationDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('title', function ($query) {
                return json_decode($query->data, true)['title'];
            })
            ->addColumn('read_at', function ($query) {
                return $query->read_at ? Carbon::parse($query->read_at)->diffForHumans() : '-';
            })
            ->addColumn('notified_at', function ($query) {
                return $query->updated_at?->diffForHumans();
            })
            ->addColumn('action', function ($query) {
                // $editBtn = "<a href='" . route('admin.notifications.edit', $query->id) . "' class='btn btn-success btn-sm'><i class='far fa-edit'></i></a>";
                $deleteBtn = "<a href='" . route('admin.notifications.destroy', $query->id) . "' class='btn btn-danger btn-sm  ml-2 delete-item'><i class='fa fa-trash'></i></a>";
                return $deleteBtn;
            })
            ->rawColumns(['action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Notification $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('notifications.updated_at', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('notification-table')
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
            Column::make('title'),
            Column::make('read_at'),
            Column::make('notified_at'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(175)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Notification_' . date('YmdHis');
    }
}
