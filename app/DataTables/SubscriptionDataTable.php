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
use Carbon\Carbon;

class SubscriptionDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('name', function ($query) {
                return ucwords($query->provider?->name);
            })
            ->addColumn('email', function ($query) {
                return $query->provider?->email;
            })
            ->addColumn('plan', function ($query) {
                return $query->plan?->text;
            })
            ->addColumn('status', function ($query) {
                return in_array($query->stripe_status, ['active', 'paid']) ?
                    "<span class='badge badge-success'>" . $query->stripe_status . "</span>"
                    : "<span class='badge badge-danger'>" . $query->stripe_status . "</span>";
            })
            ->addColumn('subscribed_at', function ($query) {
                return $query->updated_at->format('Y-m-d');
            })
            ->addColumn('ends_at', function ($query) {
                return Carbon::parse($query->ends_at)->format('Y-m-d');
            })
            ->addColumn('trial_ends_at', function ($query) {
                return Carbon::parse($query->trial_ends_at)->format('Y-m-d');
            })
            ->addColumn('remaining_trial_days', function ($query) {
                $now = Carbon::now();
                if ($query->trial_ends_at && $now->lt($query->trial_ends_at)) {
                    return $now->diffInDays(Carbon::parse($query->trial_ends_at)) . ' ' . __('lang.days');
                }
                return 0 . ' ' . __('lang.days');
            })
            ->addColumn('status_tracker', function ($query) {
                $now = Carbon::now();
                $trialEndsAt = Carbon::parse($query->trial_ends_at);
                $endsAt = Carbon::parse($query->ends_at);

                // If still in trial period
                if ($query->stripe_status !== 'paid' && $now->lt($trialEndsAt)) {
                    // Calculate remaining trial days
                    $remainingTrialDays = $now->diffInDays($trialEndsAt);

                    // For example, if trial is about to expire in 1-3 days, show expiring else active
                    if ($remainingTrialDays >= 0 && $remainingTrialDays <= 3) {
                        return __('lang.trial_expiring');
                    }
                    return __('lang.trial_active');
                }

                // If paid user (or trial over), check ends_at
                if ($now->gt($endsAt)) {
                    return __('lang.expired');
                }

                $daysDiff = $now->diffInDays($endsAt, false);

                if ($daysDiff >= 0 && $daysDiff <= 3) {
                    return __('lang.expiring');
                }

                return __('lang.active');
            })

            ->addColumn('action', function ($subscription) {
                return view('admins.subscriptions.actions', compact('subscription'))->render();
            })
            ->rawColumns(['action', 'status', 'trial_ends_at', 'remaining_trial_days', 'status_tracker'])
            ->setRowId('id');
    }

    public function query(Subscription $model): QueryBuilder
    {
        return $model->newQuery();
    }

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

    public function getColumns(): array
    {
        return [
            Column::make('name'),
            Column::make('email'),
            Column::make('plan'),
            Column::make('status'),
            Column::make('subscribed_at'),
            Column::make('ends_at'),
            Column::make('trial_ends_at'),
            Column::make('remaining_trial_days')->addClass('text-center'),
            Column::make('status_tracker'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(75)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Subscription_' . date('YmdHis');
    }
}
