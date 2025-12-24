@extends('layouts.app', ['title' => 'Reminders'])

@section('content')
    <div class="card">
        <h2>Reminders</h2>
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Booking</th>
                <th>Customer</th>
                <th>Installment #</th>
                <th>Reminder Date</th>
                <th>Type</th>
                <th>Status</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($reminders as $reminder)
                <tr>
                    <td>{{ $reminder->id }}</td>
                    <td>{{ optional(optional(optional($reminder->installmentItem)->schedule)->booking)->id }}</td>
                    <td>{{ optional(optional(optional(optional($reminder->installmentItem)->schedule)->booking)->user)->name }}</td>
                    <td>{{ optional($reminder->installmentItem)->installment_number }}</td>
                    <td>{{ $reminder->reminder_date }}</td>
                    <td>{{ $reminder->type }}</td>
                    <td>{{ $reminder->status }}</td>
                    <td>
                        <div style="display: flex; gap: 4px;">
                            <a href="{{ route('reminders.show', $reminder) }}" class="btn">View</a>
                            <a href="{{ route('reminders.edit', $reminder) }}" class="btn">Edit</a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8">No reminders found.</td></tr>
            @endforelse
            </tbody>
        </table>
        {{ $reminders->links() }}
    </div>
@endsection


