<?php

namespace App\DataTables;

use App\Models\Agreement;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;

class AgreementDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('id', function ($query) {
                return "AG_11" . $query->id;
            })
            ->addColumn('commission', function ($query) {
                return $query->commission . '%';
            })
            ->addColumn('signed', function ($query) {
                return $query->signed ? __('lang.yes') : __('lang.no');
            })
            ->addColumn('terms', function ($query) {
                return $query->terms ? __('lang.yes') : __('lang.no');
            })
             ->addColumn('agreed_at', function ($query) {
                return Carbon::parse($query?->created_at)->format('Y/m/d');
            })
            ->addColumn('action', function ($request) {
                return view('admins.providers.agreements.actions', compact('request'))->render();
            })
            ->rawColumns(['action',])
            ->setRowId('id');
    }

    public function query(Agreement $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('agreement-table')
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

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('#'),
            Column::make('name'),
            Column::make('email'),
            Column::make('phone'),
            Column::make('commission'),
            Column::make('license_number'),
            Column::make('signed'),
            Column::make('terms'),
            Column::make('agreed_at'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(75)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Agreement_' . date('YmdHis');
    }
}
