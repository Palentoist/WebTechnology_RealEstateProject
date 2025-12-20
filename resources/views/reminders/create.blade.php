@extends('layouts.app', ['title' => 'Create Reminder'])

@section('content')
<div class="card">
    <h2>Create Payment Reminder</h2>

    <p><strong>Customer:</strong> {{ $customer->name }}</p>
    <p><strong>Email:</strong> {{ $customer->email }}</p>
    <p><strong>Installment #:</strong> {{ $installmentItem->installment_number }}</p>

    <form method="POST" action="{{ route('reminders.store') }}">
        @csrf

        <input type="hidden" name="installment_item_id"
               value="{{ $installmentItem->id }}">

        {{-- Reminder Date --}}
        <div class="form-group" style="margin-bottom: 16px;">
            <label>Reminder Date</label>
            <input type="date"
                   name="reminder_date"
                   required
                   style="
                        width: 100%;
                        background-color: #bacfddff;
                        border: 1px solid #90cdf4;
                        color: #003366;
                        padding: 10px;
                        border-radius: 10px;
                   ">
        </div>

        {{-- Message --}}
        <div class="form-group" style="margin-bottom: 20px;">
            <label>Message</label>
            <textarea name="msg"
                      rows="4"
                      required
                      style="
                        width: 100%;
                        background-color: #bacfddff;
                        border: 1px solid #90cdf4;
                        color: #003366;
                        padding: 12px;
                        border-radius: 10px;
                        resize: vertical;
                      ">Your installment payment is due soon. Please make payment before the due date.</textarea>
        </div>

        {{-- Create Button --}}
        <button type="submit"
                style="
                    background-color: #488daa91;
                    color: #ffffff;
                    padding: 10px 20px;
                    border-radius: 999px;
                    border: none;
                    font-weight: bold;
                    cursor: pointer;
                    box-shadow: 0 4px 12px rgba(56,189,248,0.35);
                ">
            ➕ Create Reminder
        </button>
    </form>
</div>
@endsection
