@extends('layouts.app', ['title' => 'Reminders'])

@section('content')
<style>
    /* Card safety */
    .card { overflow: hidden; }

    /* Make table scroll inside the card (so Actions never go outside) */
    .table-wrap {
        overflow-x: auto;
        width: 100%;
    }

    /* Stable compact table */
    .table-wrap table {
        width: 100%;
        min-width: 980px;          /* IMPORTANT: prevents Actions from being squeezed/clipped */
        border-collapse: collapse;
        table-layout: fixed;
    }

    .table-wrap th, .table-wrap td {
        padding: 12px 10px;
        vertical-align: middle;
        white-space: nowrap;
    }

    .table-wrap td {
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .actions-cell { white-space: nowrap; }

    /* Softer matching buttons */
    .btn-view-soft {
        padding: 6px 16px;
        border-radius: 999px;
        background: rgba(255,255,255,0.06);
        color: #ffffff;
        border: 1px solid rgba(255,255,255,0.25);
        font-size: 12px;
        text-decoration: none;
        display: inline-block;
        margin-right: 8px;
    }

    .btn-send-soft {
        background: rgba(125, 211, 252, 0.18);
        color: #e0f2fe;
        border: 1px solid rgba(125, 211, 252, 0.45);
        padding: 6px 16px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* Status colors (nice but not neon) */
    .status-sent { color: #22c55e; font-weight: 700; }
    .status-pending { color: #fbbf24; font-weight: 700; }
</style>

<div class="card">
    <h2>Reminders</h2>

    @if(session('status'))
        <div style="padding: 15px; background: rgba(34,197,94,0.15); color: #bbf7d0; border: 1px solid rgba(34,197,94,0.25); border-radius: 10px; margin-bottom: 20px;">
            ✅ {{ session('status') }}
        </div>
    @endif

    @if(session('error'))
        <div style="padding: 15px; background: rgba(239,68,68,0.15); color: #fecaca; border: 1px solid rgba(239,68,68,0.25); border-radius: 10px; margin-bottom: 20px;">
            ❌ {{ session('error') }}
        </div>
    @endif

    <div class="table-wrap">
        <table>
            <thead>
            <tr>
                <th style="width:60px;">ID</th>
                <th style="width:90px;">Booking</th>
                <th style="width:140px;">Customer</th>
                <th style="width:130px;">Installment #</th>
                <th style="width:210px;">Reminder Date</th>
                <th style="width:90px;">Type</th>
                <th style="width:110px;">Status</th>
                <th style="width:220px;">Actions</th>
            </tr>
            </thead>

            <tbody>
            @forelse($reminders as $reminder)
                <tr>
                    <td>{{ $reminder->id }}</td>
                    <td>#{{ optional(optional(optional($reminder->installmentItem)->schedule)->booking)->id }}</td>
                    <td>{{ optional(optional(optional(optional($reminder->installmentItem)->schedule)->booking)->user)->name }}</td>
                    <td>#{{ optional($reminder->installmentItem)->installment_number }}</td>
                    <td>{{ $reminder->reminder_date }}</td>
                    <td>{{ $reminder->type }}</td>

                    <td>
                        @if($reminder->status === 'sent')
                            <span class="status-sent">SENT</span>
                        @else
                            <span class="status-pending">PENDING</span>
                        @endif
                    </td>

                    <td class="actions-cell">
                        <a href="{{ route('reminders.show', $reminder) }}" class="btn-view-soft">View</a>

                        @if($reminder->status === 'pending')
                            <form method="POST" action="{{ route('reminders.send-now', $reminder) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-send-soft">📧 Send</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding:20px;">No reminders found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{ $reminders->links() }}
</div>
@endsection
