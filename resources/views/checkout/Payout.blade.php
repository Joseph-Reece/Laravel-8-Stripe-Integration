<x-app-layout>
    <x-slot name="header">
        <h2 class="italic text-red-500 font-weight-bold underline ">Checkout Page</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-2">
                {{-- <input id="card-holder-name" type="text"> --}}
                <div class="grid grid-cols-1 md:grid-cols-6" >
                    <x-input id="card-holder-name" class="block mt-1 w-full" type="text" name="" required autofocus />
                </div>

                <!-- Stripe Elements Placeholder -->
                <div class="my-2 p-3">
                    <div id="card-element"></div>
                </div>

                <button id="card-button" class="bg-green-500 rounded p-3 focus:bg-green-800 hover:shadow-md hover:border-red-400" data-secret="{{ $intent->client_secret }}">
                    Update Payment Method
                </button>
            </div>
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>

<script>
    const stripe = Stripe('pk_test_51HoJTlCDh8HdIZ5wWyNIhspLdWctfhHHhaVuX8YkX7eXhFxECUrtBmKgQIrz9YeOsqvfto4NljS9YsW8EJ4lvFlk00zdqJen3c');

    const elements = stripe.elements();
    const cardElement = elements.create('card');

    cardElement.mount('#card-element');

    const cardHolderName = document.getElementById('card-holder-name');
    const cardButton = document.getElementById('card-button');
    const clientSecret = cardButton.dataset.secret;

    cardButton.addEventListener('click', async (e) => {
        const { setupIntent, error } = await stripe.confirmCardSetup(
            clientSecret, {
                payment_method: {
                    card: cardElement,
                    billing_details: { name: cardHolderName.value }
                }
            }
        );

        if (error) {
            // Display "error.message" to the user...
            console.log(error.message);
        } else {
            // The card has been verified successfully...
            console.log(setupIntent);
        }
    });
</script>
</x-app-layout>
