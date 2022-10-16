<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
{{--            {{ __('Dashboard') }}--}}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">My Plan</div>
                    <div class="card-body">
                        @if(session('message'))
                            <div class="alert alert-info">{{session('message')}}</div>
                        @endif
                        @if(is_null($currentPlan))
                        You are now on Free Plan. Please choose plan to upgrade:
                        @elseif(!empty($currentPlan->trial_ends_at))
                            <div class="alert alert-info">Your Trial will end on {{ $currentPlan->trial_ends_at->toDateString() }} and your card will be charged automatically</div>
                        @endif
                            <br /><br />
                            <div class="row">
                            @foreach($plans as $plan)
                                <div class="col-md-4 text-center">
                                    <h3>{{ $plan->name }}</h3>
                                    <b>${{ number_format($plan->price / 100, 2)  }}</b>
                                    <hr />
                                    @if(!empty($currentPlan) && $plan->stripe_plan_id === $currentPlan->stripe_price)
                                        Your Current Plan
                                        <br>
                                    @if(!$currentPlan->onGraceperiod())
                                        <a href="{{route('cancel')}}" class="btn btn-danger" onclick="return confirm('Are you sure?')">Cancel Plan</a>
                                    @else
                                        Your subscription will end on  {{$currentPlan->ends_at->toDateString()}}
                                            <br><br>
                                            <a href="{{route('resume')}}" class="btn btn-primary">Resume Subscription</a>
                                    @endif
                                    @else
                                    <a href="{{  route('checkout', $plan->id) }}" class="btn btn-primary" >Subscribe to {{ $plan->name }}</a>
                                    @endif
                                </div>
                            @endforeach
                            <br/>

                        </div>
                    </div>
                </div>

                @if(!is_null($currentPlan))
                <div class="card">
                    <div class="card-header">Payment Method</div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Brand</th>
                                <th>Expires at</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($paymentMethods as $paymentMethod)
                                <tr>
                                    <td>{{ $paymentMethod->card->brand }}</td>
                                    <td>{{ $paymentMethod->card->exp_month }} / {{ $paymentMethod->card->exp_year }}</td>
                                    <td>
                                        @if ($defaultPaymentMethod->id == $paymentMethod->id)
                                            default
                                        @else
                                            <a href="{{ route('markDefault', $paymentMethod->id) }}">Mark as Default</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <br>
                        <a href="{{route('payment-method.create')}}" class="btn btn-primary">Add Payment Method</a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
