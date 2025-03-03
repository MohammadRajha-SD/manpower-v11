<?php

namespace App\DataTables;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class BookingDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('id', function ($query) {
                return '#' . $query->id;
            })
            ->addColumn('provider', function ($query) {
                return json_decode($query->provider)?->name;
            })
            ->addColumn('services', function ($query) {
                return json_decode($query->services)?->name;
            })
            ->addColumn('address', function ($query) {
                return ucwords(json_decode($query->address)?->address);
            })
            ->addColumn('customer', function ($query) {
                return ucwords($query->user->name) ?? 'N/A';
            })
            ->addColumn('coupen', function ($query) {
                return $query->coupen ?? 'N/A';
            })
            ->addColumn('booking_status', function ($query) {
                return ucwords($query->booking_status?->status);
            })
            ->addColumn('booking_at', function ($query) {
                return Carbon::parse($query->booking_at)->diffForHumans();
            })
            ->addColumn('action', function ($query) {
                $editBtn = "<a href='" . route('admin.bookings.edit', $query->id) . "' class='btn btn-success btn-sm'><i class='far fa-edit'></i></a>";
                $deleteBtn = "<a href='" . route('admin.bookings.destroy', $query->id) . "' class='btn btn-danger btn-sm  ml-2 delete-item'><i class='fa fa-trash'></i></a>";
                return $editBtn . $deleteBtn;
            })
            ->rawColumns(['action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Booking $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('booking-table')
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
            // TODO: total-taxes && fees-payment statut
            Column::make('id'),
            Column::make('provider'),
            Column::make('services'),
            Column::make('address'),
            Column::make('coupen'),
            Column::make('customer'),
            Column::make('booking_status'),
            Column::make('booking_at'),
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
        return 'Booking_' . date('YmdHis');
    }
}
