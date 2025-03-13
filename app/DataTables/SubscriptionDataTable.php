<?php

namespace App\DataTables;

use App\Models\Subscription;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class SubscriptionDataTable extends DataTable
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
                return ucwords($query->user?->name);
            })
            ->addColumn('email', function ($query) {
                return $query->user?->email;
            })
            ->addColumn('plan', function ($query) {
                return $query->plan?->text;
            })
            ->addColumn('status', function ($query) {
                return $query->status == 'active' ?
                    "<span class='badge badge-success'>" . $query->status . "</span>"
                    : "<span class='badge badge-danger'>" . $query->status . "</span>";
            })
            ->addColumn('subscribed_at', function ($query) {
                return $query->updated_at->format('Y-m-d');
            })
            ->addColumn('ends_at', function ($query) {
                return $query->ends_at;
            })
            ->addColumn('action', function ($subscription) {
                $paymentButton = '';
                if (in_array($subscription->stripe_status, ['disabled'])) {
                    $paymentButton = '<form action="' . route('admin.subscriptions.generate-payment-link', $subscription->id) . '" method="POST" style="display: inline;">
                                <a href="' . route("admin.subscriptions.generate-payment-link", $subscription->id) . '" class="dropdown-item text-warning generate-payment-link"
                                    data-toggle="tooltip" title="Create payment link">
                                   <i class="fas fa-credit-card"></i> Create payment link
                                </a></form>';
                }

                return '<td class="text-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-outline-secondary  btn-sm dropdown-toggle"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-cogs"></i> ' . __('lang.actions') . '
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                  ' . $paymentButton . '
                                    <a class="dropdown-item text-primary"
                                        href="' . route('admin.subscriptions.edit', $subscription->id) . '"
                                        data-toggle="tooltip" title="' . __('lang.edit_subscription') . '">
                                        <i class="fas fa-edit"></i> ' . __('lang.edit_subscription') . '
                                    </a>
                                    
                                    <form action="' . route('admin.subscriptions.destroy', $subscription->id) . '" method="DELETE" style="display: inline;">
                                        <a href="' . route('admin.subscriptions.destroy', $subscription->id) . '" class="dropdown-item text-danger delete-item"
                                            data-toggle="tooltip"
                                            title="' . __('lang.delete_subscription') . '">
                                            <i class="fas fa-trash-alt"></i> ' . __('lang.delete_subscription') . '
                                        </a>
                                    </form>
                                </div>
                            </div>
                        </td>';
            })
            ->rawColumns(['action', 'status',])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Subscription $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('subscription-table')
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
            Column::make('name'),
            Column::make('email'),
            Column::make('plan'),
            Column::make('status'),
            Column::make('subscribed_at'),
            Column::make('ends_at'),
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
        return 'Subscription_' . date('YmdHis');
    }
}
