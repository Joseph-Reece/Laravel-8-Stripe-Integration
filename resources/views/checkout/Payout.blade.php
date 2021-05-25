<x-app-layout>
    <style>
        .hide {
            display: none
        }

    </style>
    <x-slot name="header">
        <h2 class="italic text-red-500 font-weight-bold underline ">Checkout Page With custom javascript</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-2">
                {{-- <input id="card-holder-name" type="text"> --}}
                <div class="d-flex align-items-center mb-2 ">
                    <h3>Payment Details</h3>
                </div>

                @if (Session::has('success'))

                    <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md"
                        role="alert">
                        <div class="flex">
                            <div class="py-1"><svg class="fill-current h-6 w-6 text-teal-500 mr-4"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path
                                        d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z" />
                                </svg></div>
                            <div>
                                <p class="font-bold">Success</p>
                                <p class="text-sm">{{ Session::get('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <form role="form" action="{{ route('stripe.payment') }}" method="post" class="validation"
                    data-cc-on-file="false" data-stripe-publishable-key="{{ env('STRIPE_KEY') }}" id="payment-form">
                    @csrf

                    <div class="flex flex-wrap -mx-3 mb-6">

                        <div class='w-full md:w-1/2 px-3 mb-6 required'>
                            <x-label value="Name on Card" />
                            <x-input type="text" />

                        </div>

                        <div class='w-full md:w-1/2 px-3 mb-6 card required'>
                            <x-label value="Card Number" />
                            <x-input class="card-num" size="20" type="text" />

                        </div>

                    </div>

                    <div class='flex flex-wrap -mx-3 mb-2'>
                        <div class='w-full md:w-1/3 px-3 mb-6 md:mb-0 cvc required'>
                            <x-label value="CVC" />
                            <x-input autocomplete="off" type="text" class='card-cvc' placeholder='e.g 415' />
                        </div>
                        <div class='w-full md:w-1/3 px-3 mb-6 md:mb-0 expiration required'>
                            <x-label value="Expiration Month" />
                            <x-input class='card-expiry-month' placeholder='MM' type='text' />

                        </div>
                        <div class='w-full md:w-1/3 px-3 mb-6 md:mb-0 expiration required'>
                            <x-label value="Expiration Year" />
                            <x-input class='card-expiry-year' placeholder='YYYY' type='text' />
                        </div>
                    </div>

                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 hide alert rounded relative"
                        role="alert">
                        <strong class="font-bold">Holy smokes!</strong>
                        <span class="block sm:inline">Something seriously bad happened.</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                            <svg class="fill-current h-6 w-6 text-red-500" role="button"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <title>Close</title>
                                <path
                                    d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                            </svg>
                        </span>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <x-button type="submit">
                                Pay Now (₹100)
                            </x-button>
                        </div>
                    </div>

                </form>


                <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
                <script type="text/javascript">
                    $(function() {
                        var $form = $(".validation");
                        $('form.validation').bind('submit', function(e) {
                            var $form = $(".validation"),
                                inputVal = ['input[type=email]', 'input[type=password]',
                                    'input[type=text]', 'input[type=file]',
                                    'textarea'
                                ].join(', '),
                                $inputs = $form.find('.required').find(inputVal),
                                $errorStatus = $form.find('div.error'),
                                valid = true;
                            $errorStatus.addClass('hide');

                            $('.has-error').removeClass('has-error');
                            $inputs.each(function(i, el) {
                                var $input = $(el);
                                if ($input.val() === '') {
                                    $input.parent().addClass('has-error');
                                    $errorStatus.removeClass('hide');
                                    e.preventDefault();
                                }
                            });

                            if (!$form.data('cc-on-file')) {
                                e.preventDefault();
                                Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                                Stripe.createToken({
                                    number: $('.card-num').val(),
                                    cvc: $('.card-cvc').val(),
                                    exp_month: $('.card-expiry-month').val(),
                                    exp_year: $('.card-expiry-year').val()
                                }, stripeHandleResponse);
                            }

                        });

                        function stripeHandleResponse(status, response) {
                            if (response.error) {
                                $('.error')
                                    .removeClass('hide')
                                    .find('.alert')
                                    .text(response.error.message);
                            } else {
                                var token = response['id'];
                                $form.find('input[type=text]').empty();
                                $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                                $form.get(0).submit();
                            }
                        }

                    });

                </script>


            </div>
        </div>
    </div>

</x-app-layout>
