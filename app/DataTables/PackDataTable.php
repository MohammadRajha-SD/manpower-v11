<?php

namespace App\DataTables;

use App\Models\Pack;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PackDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('type', function ($query) {
                return ucwords($query->type);
            })
            ->addColumn('trial_days', function ($query) {
                return $query->trial_days;
            })
            ->addColumn('num_of_months', function ($query) {
                return $query->number_of_months;
            })
            ->addColumn('num_of_ads', function ($query) {
                return $query->number_of_ads;
            })
            ->addColumn('num_of_subcategories', function ($query) {
                return $query->number_of_subcategories;
            })
            ->addColumn('display_images_on_slider', function ($query) {
                return isActive($query->not_on_image_slider, 'success', 'danger');
            })->addColumn('appear_in_the_featured_services', function ($query) {
                return isActive($query->not_in_featured_services, 'success', 'danger');
            })
            ->addColumn('text', function ($query) {
                return $query->text;
            })
            ->addColumn('price', function ($query) {
                return ($query->price == (int) $query->price ? (int) $query->price : $query->price) . setting('default_currency_code', '$');
            })
            ->addColumn('action', function ($query) {
                $editBtn = "<a href='" . route('admin.packs.edit', $query->id) . "' class='btn btn-success btn-sm'><i class='far fa-edit'></i></a>";
                $deleteBtn = "<a href='" . route('admin.packs.destroy', $query->id) . "' class='btn btn-danger btn-sm  ml-2 delete-item'><i class='fa fa-trash'></i></a>";
                return $editBtn . $deleteBtn;
            })
            ->rawColumns(['action', 'appear_in_the_featured_services', 'display_images_on_slider'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Pack $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('pack-table')
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
            Column::make('type'),
            Column::make('trial_days'),
            Column::make('num_of_months'),
            Column::make('num_of_ads'),
            Column::make('num_of_subcategories'),
            Column::make('display_images_on_slider'),
            Column::make('appear_in_the_featured_services'),
            Column::make('text'),
            Column::make('price'),
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
        return 'Pack_' . date('YmdHis');
    }
}
