<?php

namespace App\DataTables;

use App\Models\Service;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ServiceDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('name', function ($query) {
                return ucwords($query->name);
            })
            ->addColumn('available', function ($query) {
                return isActive($query->enable_booking, 'success', 'danger');
            })
            ->addColumn('provider', function ($query) {
                return ucwords($query->provider?->name) ?? 'N/A';
            })
            ->addColumn('categories', function ($query) {
                // $categories = $query->categories;
                // $categoryLinks = '';
    
                // if ($categories->isEmpty()) {
                //     $categoryLinks = 'N/A';
                // } else {
                //     foreach ($categories as $category) {
                //     }
                // }
    
                return '<a href="' . route('admin.categories.edit', $query->id) . '"><small>' . $query->category?->name . '</small></a><br>';
                // return $categoryLinks;
            })

            ->addColumn('addresses', function ($query) {
                $addresses = $query->addresses;
                $addressLinks = '';

                if ($addresses->isEmpty()) {
                    return 'N/A';
                }

                foreach ($addresses as $address) {
                    $addressLinks .= '<small>' . setting('default_currency_code') . ' ' . number_format($address->service_charge, 2) . ' - ' . e($address->address) . '</small><br>';
                }

                return $addressLinks;
            })
            ->addColumn('price', function ($query) {
                return number_format($query->price, 2);
            })
            ->addColumn('discount', function ($query) {
                return number_format($query->discount_price, 2);
            })
            ->addColumn('updated_at', function ($query) {
                return $query->updated_at->format('Y-m-d H:i:s');
            })
            ->addColumn('action', function ($query) {
                return view('admins.services.actions', compact('query'))->render();
            })

            ->rawColumns(['available', 'action', 'categories', 'addresses'])
            ->setRowId('id');
    }

    public function query(Service $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('updated_at', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('service-table')
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
            // Column::make('image'),
            Column::make('name'),
            Column::make('provider')->addClass('text-center'),
            Column::make('price')->addClass('text-center'),
            Column::make('discount')->addClass('text-center'),
            Column::make('categories')->addClass('text-center'),
            // Column::make('addresses')->addClass('text-center'),
            Column::make('available')->addClass('text-center'),
            Column::make('updated_at')->addClass('text-center'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(75)
                ->addClass('text-center'),
        ];
    }


    protected function filename(): string
    {
        return 'Service_' . date('YmdHis');
    }
}
