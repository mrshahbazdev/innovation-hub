<x-guest-layout>
    <div class="pt-4 bg-gray-100">
        <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">

            <div>
                <x-authentication-card-logo />
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-10 bg-white shadow-md overflow-hidden sm:rounded-lg text-center">

                <div class="w-20 h-20 bg-green-100 text-green-600 flex items-center justify-center rounded-full mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <h1 class="text-3xl font-bold text-gray-900">Thank You!</h1>
                <p class="text-gray-600 mt-4">
                    Your idea has been submitted successfully. We will review it soon and get back to you.
                </p>

                <div class="mt-8">
                    <a href="{{ url('/') }}"
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Go to Homepage
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
