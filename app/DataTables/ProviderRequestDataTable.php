<?php

namespace App\DataTables;

use App\Models\ProviderRequest;
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
            ->addColumn('company_name', function ($query) {
                return ucwords($query->company_name);
            })
            ->addColumn('number_employees', function ($query) {
                return $query->number_employees;
            })
            ->addColumn('phone', function ($query) {
                return $query->phone_number ?? 'N/A';
            })
            ->addColumn('services', function ($query) {
                $services = is_array($query->services) ? $query->services : json_decode($query->services);
                $addressLinks = '';

                if (!empty($services)) {
                    foreach ($services as $address) {
                        $addressLinks .= '<small>' . $address . '</small></a><br>';
                    }
                } else {
                    $addressLinks = 'N/A';
                }

                return $addressLinks;
            })
            ->addColumn('cities', function ($query) {
                $cities = is_array($query->cities) ? $query->cities : json_decode($query->cities);
                $addressLinks = '';

                if (!empty($cities)) {
                    foreach ($cities as $address) {
                        $addressLinks .= '<small>' . $address . '</small></a><br>';
                    }
                } else {
                    $addressLinks = 'N/A';
                }

                return $addressLinks;
            })
            ->addColumn('plans', function ($query) {
                $plans = is_array($query->plans) ? $query->plans : json_decode($query->plans);
                $addressLinks = '';

                if (!empty($plans)) {
                    foreach ($plans as $address) {
                        $addressLinks .= '<small>' . $address . '</small></a><br>';
                    }
                } else {
                    $addressLinks = 'N/A';
                }

                return $addressLinks;
            })
            ->addColumn('accepted', function ($query) {
                $checked = $query->accepted ? 'checked' : '';
                $route = route('admin.provider-requests.toggleAccepted', $query->id);

                return '
                    <form method="POST" action="' . $route . '" class="d-inline toggle-accepted-form" title="Toggle accepted status">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <div class="form-check form-switch">
                            <input 
                                class="form-check-input" 
                                type="checkbox" 
                                name="accepted" 
                                id="switch-accepted-' . $query->id . '"
                                onchange="this.form.submit()" 
                                ' . $checked . '>
                            <label class="form-check-label small" for="switch-accepted-' . $query->id . '">
                                ' . ($query->accepted ? 'Accepted' : 'Pending') . '
                            </label>
                        </div>
                    </form>';
            })
            ->addColumn('licence', function ($query) {
                return '<a href="' . route('admin.provider-requests.streamLicence', $query->id) . '" 
                    target="_blank" 
                    class="btn btn-sm btn-outline-primary" 
                    title="Preview Licence">
                        <i class="fas fa-eye"></i>
                    </a>';
            })
            ->addColumn('action', function ($query) {
                return "<a href='" . route('admin.provider-requests.destroy', $query->id) . "' class='btn btn-danger btn-sm  ml-2 delete-item'><i class='fa fa-trash'></i></a>";
            })
            ->rawColumns(['action', 'cities', 'services', 'plans', 'accepted', 'licence'])
            ->setRowId('id');
    }

    public function query(ProviderRequest $model): QueryBuilder
    {
        return $model->newQuery()
            ->orderBy('accepted')
            ->orderBy('updated_at', 'desc');
    }

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

    public function getColumns(): array
    {
        return [
            Column::make('company_name'),
            Column::make('services'),
            Column::make('cities'),
            Column::make('plans'),
            Column::make('notes'),
            Column::make('contact_person'),
            Column::make('contact_email'),
            Column::make('phone'),
            Column::make('company_website'),
            Column::make('accepted'),
            Column::make('licence'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'ProviderRequest_' . date('YmdHis');
    }
}
