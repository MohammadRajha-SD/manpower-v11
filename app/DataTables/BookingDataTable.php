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
            ->addColumn('user', function ($query) {
                return $query->user->name;
            })
            ->addColumn('service', function ($query) {
                return $query->service->name;
            })
            ->addColumn('address', function ($query) {
                return ucwords($query->address);
            })
            ->addColumn('total', function ($query) {
                return "<span class='text-bold text-success'>" . getPrice($query->getTotal()) . "</span>";
            })
            ->addColumn('coupon', function ($query) {
                $coupon = $query->couponx;
                $value = 0;
                if($coupon) {

                    if ($coupon->discount_type === 'fixed') {
                        $value = $coupon->discount . setting('default_currency', 'AED');
                    } elseif ($coupon->discount_type === 'percent') {
                        $value = $coupon->discount . '%';
                    }
                }
                return $query->coupon ? $query->coupon . " <span class='text-bold'>(" . $value . ")</span>" : 'N/A';
            })
            ->addColumn('tax', function ($query) {
                return "<span class='text-bold'>" . getPrice($query->getTax()) . "</span>";
            })
            ->addColumn('booking_status', function ($query) {
                return ($query->booking_status?->status);
            })
            ->addColumn('payment_status', function ($query) {
                return ($query->payment?->payment_status?->status);
            })
            ->addColumn('booking_at', function ($query) {
                return Carbon::parse($query->booking_at)->diffForHumans();
            })
            ->addColumn('action', function ($query) {
                $editBtn = "<a href='" . route('admin.bookings.edit', $query->id) . "' class='btn btn-success btn-sm'><i class='far fa-edit'></i></a>";
                $deleteBtn = "<a href='" . route('admin.bookings.destroy', $query->id) . "' class='btn btn-danger btn-sm  ml-2 delete-item'><i class='fa fa-trash'></i></a>";
                return $editBtn . $deleteBtn;
            })
            ->rawColumns(['action', 'total', 'booking_status', 'payment_status', 'coupon', 'tax'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Booking $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('updated_at', 'desc');
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
            Column::make('id'),
            Column::make('user'),
            Column::make('service'),
            Column::make('address'),
            Column::make('coupon'),
            Column::make('booking_status'),
            Column::make('payment_status'),
            Column::make('total'),
            Column::make('tax')->title('Taxes & Fees'),
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
