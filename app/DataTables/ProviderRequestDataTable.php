<?php

namespace App\DataTables;

use App\Models\Provider;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProviderRequestDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('name', function ($query) {
                $featured = isFeatured($query->featured);
                $featured = isFeatured($query->featured);

                return ucwords($query->name) . $featured;
            })
            ->addColumn('provider_type', function ($query) {
                return ucwords($query->providerType->name);
            })
            ->addColumn('employees', function ($query) {
                $users = $query->users;
                $userLinks = '';

                if (count($users) > 0) {
                    foreach ($users as $user) {
                        $userLinks .= '<a href="#"><small>' . $user->name . '</small></a><br>';
                    }
                } else {
                    $userLinks = 'N/A';
                }

                return $userLinks;
            })
            ->addColumn('phone', function ($query) {
                return $query->phone_number ?? 'N/A';
            })
            ->addColumn('mobile', function ($query) {
                return $query->mobile_number ?? 'N/A';
            })
            ->addColumn('available', function ($query) {
                return isActive($query->available, 'success', 'danger');
            })
            ->addColumn('accepted', function ($query) {
                return isActive($query->accepted, 'success', 'danger');
            })
            ->addColumn('addresses', function ($query) {
                $addreses = $query->addresses;
                $addressLinks = '';

                if (count($addreses) > 0) {
                    foreach ($addreses as $address) {
                        $addressLinks .= '<a href="' . route('admin.addresses.edit', $address->id) . '"><small>' . $address->address . '</small></a><br>';
                    }
                } else {
                    $addressLinks = 'N/A';
                }

                return $addressLinks;
            })
            ->addColumn('taxes', function ($query) {
                $taxes = $query->taxes;
                $taxLinks = '';

                if (count($taxes) > 0) {
                    foreach ($taxes as $tax) {
                        $taxLinks .= '<a href="' . route('admin.taxes.edit', $tax->id) . '"><small>' . $tax->name . '</small></a><br>';
                    }
                } else {
                    $taxLinks = 'N/A';
                }

                return $taxLinks;
            })
            ->addColumn('range', function ($query) {
                return $query->availability_range ?? 'N/A';
            })
            ->addColumn('updated_at', function ($query) {
                return $query->updated_at->diffForHumans();
            })
            ->addColumn('action', function ($query) {
                $editBtn = "<a href='" . route('admin.providers.edit', $query->id) . "' class='btn btn-success btn-sm'><i class='far fa-edit'></i></a>";
                $deleteBtn = "<a href='" . route('admin.providers.destroy', $query->id) . "' class='btn btn-danger btn-sm  ml-2 delete-item'><i class='fa fa-trash'></i></a>";
                return $editBtn . $deleteBtn;
            })
            ->rawColumns(['available', 'action', 'employees', 'accepted', 'name', 'addresses', 'taxes'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Provider $model): QueryBuilder
    {
        // TODO:
        // if (auth()->user()->is_admin == 1) {
            return $model->newQuery()->where('accepted', '0');
        // }
        // else {
        //     return $model->newQuery()
        //         ->with("eProviderType")
        //         ->join("e_provider_users", "e_provider_id", "=", "e_providers.id")
        //         ->where('e_provider_users.user_id', auth()->id())
        //         ->where('e_providers.accepted', '0')
        //         ->groupBy("e_providers.id")
        //         ->select("$model->table.*");
        // }
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('providerrequest-table')
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
            Column::make('provider_type')->addClass('text-center'),
            Column::make('employees')->addClass('text-center'),
            Column::make('phone')->addClass('text-center'),
            //  Column::make('mobile')->addClass('text-center'),
            Column::make('addresses')->addClass('text-center'),
            Column::make('range')->addClass('text-center'),
            Column::make('taxes')->addClass('text-center'),
            Column::make('available')->addClass('text-center'),
            Column::make('updated_at')->addClass('text-center'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'ProviderRequest_' . date('YmdHis');
    }
}
