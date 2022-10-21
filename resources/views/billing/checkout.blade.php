<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <br/>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Subscribe to {{ $plan->name  }}</div>
                    <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                Name or Company Name:
                                <br>
                                <input type="text" name="company_name" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                Address Line 1:
                                <br>
                                <input type="text" name="address_line_1" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                Address Line 2:
                                <br>
                                <input type="text" name="address_line_2" class="form-control" required>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-4">
                                Country:
                                <br>
                                <input type="text" name="country" class="form-control" value="Pakistan" required>
                            </div>
                            <div class="col-md-4">
                                City:
                                <br>
                                <input type="text" name="city" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                Postcode:
                                <br>
                                <input type="text" name="postcode" class="form-control">
                            </div>
                        </div>
                        <br>
                        @if(session('error'))
                            <div class="alert alert-danger">{{session('error')}}</div>
                        @endif
                        Payment form will be here.
                        <div class="row">
                            <div class="col-md-6">
                                    @csrf
                                    <input type="hidden" name="billing_plan_id" value="{{ $plan->id }}" />
                                    <input type="hidden" name="payment-method" id="payment-method" value="" />

                                    <input id="card-holder-name" type="text" placeholder="Card holder name">

                                    <!-- Stripe Elements Placeholder -->
                                    <div id="card-element"></div>

                                    <br />

                                    <button id="card-button" class="btn btn-primary">
                                        Pay ${{ number_format($plan->price / 100, 2) }}
                                    </button>

                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        $( document ).ready(function() {
            let stripe = Stripe("{{ env('STRIPE_KEY') }}")
            let elements = stripe.elements()
            let style = {
                base: {
                    color: '#32325d',
                    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    fontSmoothing: 'antialiased',
                    fontSize: '16px',
                    '::placeholder': {
                        color: '#aab7c4'
                    }
                },
                invalid: {
                    color: '#fa755a',
                    iconColor: '#fa755a'
                }
            }
            let card = elements.create('card', {style: style})
            card.mount('#card-element')
            let paymentMethod = null
            $('#checkout-form').on('submit', function (e) {
                if (paymentMethod) {
                    return true
                }
                stripe.confirmCardSetup(
                    "{{ $intent->client_secret }}",
                    {
                        payment_method: {
                            card: card,
                            billing_details: {name: $('#card-holder-name').val()}
                        }
                    }
                ).then(function (result) {
                    if (result.error) {
                        console.log(result)
                        alert('error')
                    } else {
                        paymentMethod = result.setupIntent.payment_method
                        $('#payment-method').val(paymentMethod)
                        $('#checkout-form').submit()
                    }
                })
                return false
            })
        });
    </script>

<style>
    .StripeElement {
        box-sizing: border-box;
        height: 40px;
        padding: 10px 12px;
        border: 1px solid transparent;
        border-radius: 4px;
        background-color: white;
        box-shadow: 0 1px 3px 0 #e6ebf1;
        -webkit-transition: box-shadow 150ms ease;
        transition: box-shadow 150ms ease;
    }
    .StripeElement--focus {
        box-shadow: 0 1px 3px 0 #cfd7df;
    }
    .StripeElement--invalid {
        border-color: #fa755a;
    }
    .StripeElement--webkit-autofill {
        background-color: #fefde5 !important;
    }
</style>
