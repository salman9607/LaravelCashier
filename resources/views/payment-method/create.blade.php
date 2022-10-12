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
                    <div class="card-header">Add Payment Method</div>

                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger">{{session('error')}}</div>
                        @endif
                        Payment form will be here.
                        <div class="row">
                            <div class="col-md-6">
                                <form action="{{ route('payment-method.store') }}" method="POST" id="checkout-form">
                                    @csrf
                                    <input type="hidden" name="payment-method" id="payment-method" value="" />

                                    <input id="card-holder-name" type="text" placeholder="Card holder name">

                                    <!-- Stripe Elements Placeholder -->
                                    <div id="card-element"></div>

                                    <br />
                                    <input type="checkbox" name="default" value="1" /> Mark as Default Method
                                    <br /><br />

                                    <button id="card-button" class="btn btn-primary">
                                        Add Method
                                    </button>

                                </form>
                            </div>
                        </div>
                    </div>
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
