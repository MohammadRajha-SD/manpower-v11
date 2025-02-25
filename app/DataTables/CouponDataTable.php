<?php

namespace App\DataTables;

use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CouponDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('code', function ($query) {
                return strtoupper($query->code);
            })
            ->addColumn('description', function ($query) {
                return $query->description ?? 'N/A';
            })
            ->addColumn('discount', function ($query) {
                return $query->discount_type == 'percent' ? $query->discount . '%' : $query->discount . setting('default_currency','$');
            })
            ->addColumn('enabled', function ($query) {
                return isActive($query->enabled, $color1 = "success", $color2 = "danger");
            })
            ->addColumn('expires_at', function ($query) {
                return Carbon::parse($query->expires_at)->diffForHumans();
            })->addColumn('updated_at', function ($query) {
                return $query->updated_at?->diffForHumans();
            })
            ->addColumn('action', function ($query) {
                $editBtn = "<a href='" . route('admin.coupons.edit', $query->id) . "' class='btn btn-success btn-sm'><i class='far fa-edit'></i></a>";
                $deleteBtn = "<a href='" . route('admin.coupons.destroy', $query->id) . "' class='btn btn-danger btn-sm  ml-2 delete-item'><i class='fa fa-trash'></i></a>";
                return $editBtn . $deleteBtn;
            })
            ->rawColumns(['action', 'enabled','description'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Coupon $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('coupen-table')
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
            Column::make('code')->addClass('text-center'),
            Column::make('discount')->addClass('text-center'),
            Column::make('description')->addClass('text-center'),
            Column::make('expires_at')->addClass('text-center'),
            Column::make('enabled')->addClass('text-center'),
            Column::make('updated_at')->addClass('text-center'),
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
        return 'Coupon_' . date('YmdHis');
    }
}
