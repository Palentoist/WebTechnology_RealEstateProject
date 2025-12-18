@extends('layouts.app', ['title' => 'New Payment'])

@section('content')
    <div class="card">
        <h2>Record / Make Payment</h2>
        @if($installmentItem)
            <div class="mb-4 p-4 bg-gray-100 rounded">
                <p><strong>Booking:</strong> #{{ optional($installmentItem->schedule->booking)->id }}
                    - {{ optional(optional($installmentItem->schedule->booking)->user)->name }}</p>
                <p><strong>Installment #{{ $installmentItem->installment_number }}</strong></p>
                <p>Due: {{ $installmentItem->due_date }}</p>
                <p class="text-xl font-bold">Amount to Pay:
                    ${{ number_format($installmentItem->amount - $installmentItem->paid_amount, 2) }}</p>
            </div>
        @endif

        <form id="payment-form" method="POST" action="{{ route('payments.store') }}">
            @csrf
            <input type="hidden" name="installment_item_id"
                value="{{ old('installment_item_id', optional($installmentItem)->id) }}">
            @error('installment_item_id') <div class="error">{{ $message }}</div> @enderror

            <div class="form-row">
                <label>Payment Method</label>
                <select name="payment_method" id="payment_method" class="form-control" onchange="togglePaymentFields()">
                    <option value="manual">Manual / Cash (Admin)</option>
                    <option value="stripe">Credit Card (Stripe)</option>
                    <option value="paypal">PayPal</option>
                </select>
                @error('payment_method') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-row">
                <label>Amount</label>
                <input type="number" step="0.01" name="amount"
                    value="{{ old('amount', optional($installmentItem)->amount - optional($installmentItem)->paid_amount) }}">
                @error('amount') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-row">
                <label>Payment Date</label>
                <input type="date" name="payment_date" value="{{ old('payment_date', now()->toDateString()) }}">
                @error('payment_date') <div class="error">{{ $message }}</div> @enderror
            </div>

            <!-- Manual Fields -->
            <div id="manual-fields">
                <div class="form-row">
                    <label>Account Number / Transaction ID</label>
                    <input type="text" name="transaction_id" value="{{ old('transaction_id') }}">
                </div>
                <div class="form-row">
                    <label>Bank Name</label>
                    <input type="text" name="bank_name" value="{{ old('bank_name') }}">
                </div>
            </div>

            <!-- Stripe Fields -->
            <div id="stripe-fields" style="display: none;">
                <div class="form-row">
                    <label>Card Details</label>
                    <div id="card-element" class="p-3 border rounded"></div>
                    <div id="card-errors" role="alert" class="text-red-500 mt-2"></div>
                </div>
                <input type="hidden" name="token" id="stripe-token">
            </div>

            <!-- PayPal Info -->
            <div id="paypal-fields" style="display: none;">
                <p>You will be redirected to PayPal to complete the payment.</p>
            </div>

            <button class="btn btn-primary mt-4" type="submit" id="submit-button">Submit Payment</button>
            <a href="{{ route('payments.index') }}" class="btn mt-4">Cancel</a>
        </form>
    </div>

    @push('scripts')
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            const stripe = Stripe("{{ config('services.stripe.key') }}");
            const elements = stripe.elements();
            const card = elements.create('card');
            card.mount('#card-element');

            const form = document.getElementById('payment-form');
            const submitButton = document.getElementById('submit-button');

            function togglePaymentFields() {
                const method = document.getElementById('payment_method').value;
                document.getElementById('manual-fields').style.display = method === 'manual' ? 'block' : 'none';
                document.getElementById('stripe-fields').style.display = method === 'stripe' ? 'block' : 'none';
                document.getElementById('paypal-fields').style.display = method === 'paypal' ? 'block' : 'none';
            }

            form.addEventListener('submit', async (event) => {
                const method = document.getElementById('payment_method').value;

                if (method === 'stripe') {
                    event.preventDefault();
                    submitButton.disabled = true;

                    const { paymentMethod, error } = await stripe.createPaymentMethod({
                        type: 'card',
                        card: card,
                    });

                    if (error) {
                        const errorElement = document.getElementById('card-errors');
                        errorElement.textContent = error.message;
                        submitButton.disabled = false;
                    } else {
                        document.getElementById('stripe-token').value = paymentMethod.id;
                        form.submit();
                    }
                }
            });

            // Init
            togglePaymentFields();
        </script>
    @endpush
@endsection