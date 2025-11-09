<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

    <div class="bg-white overflow-hidden shadow-lg rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 18a.75.75 0 00.75-.75V8.66l1.97 1.97a.75.75 0 101.06-1.06l-3.25-3.25a.75.75 0 00-1.06 0L8.22 9.57a.75.75 0 101.06 1.06l1.97-1.97v8.57a.75.75 0 00.75.75z" />
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            Total Ideas
                        </dt>
                        <dd>
                            <div class="text-3xl font-bold text-gray-900">
                                {{ $totalIdeas }}
                            </div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-lg rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a8.955 8.955 0 01-1.406 4.676 8.955 8.955 0 01-4.676 1.406A8.955 8.955 0 0112 21a8.955 8.955 0 01-4.676-1.406 8.955 8.955 0 01-1.406-4.676A8.955 8.955 0 016 12a8.955 8.955 0 011.406-4.676 8.955 8.955 0 014.676-1.406A8.955 8.955 0 0112 6z" />
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            Pending Review
                        </dt>
                        <dd>
                            <div class="text-3xl font-bold text-gray-900">
                                {{ $pendingReview }}
                            </div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-lg rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25v-1.5m0 1.5c-1.214 0-2.428.16-3.568.468m3.568-.468a9 9 0 100 11.064m0-11.064c1.214 0 2.428.16 3.568.468m-3.568-.468c-1.63.22-3.178.652-4.593 1.256m12.78 7.318a9.01 9.01 0 00-4.593-1.256m4.593 1.256c.453.193.891.41 1.308.65m-7.219-5.912a8.96 8.96 0 00-1.308-.65m1.308.65c-1.314.56-2.542 1.24-3.642 2.052m10.884 3.86a8.96 8.96 0 01-1.308.65m-1.308-.65c-1.314.56-2.542 1.24-3.642 2.052M9.219 9.878c-.453.193-.891.41-1.308.65m1.308-.65c-1.314.56-2.542 1.24-3.642 2.052m10.884 3.86a8.96 8.96 0 01-1.308.65m-1.308-.65c-1.314.56-2.542 1.24-3.642 2.052" />
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            Pending Pricing
                        </dt>
                        <dd>
                            <div class="text-3xl font-bold text-gray-900">
                                {{ $pendingPricing }}
                            </div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-lg rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a8.955 8.955 0 004.676-1.406 8.955 8.955 0 001.406-4.676A8.955 8.955 0 0021 12a8.955 8.955 0 00-1.406-4.676 8.955 8.955 0 00-4.676-1.406A8.955 8.955 0 0012 6a8.955 8.955 0 00-4.676 1.406A8.955 8.955 0 006 12a8.955 8.955 0 001.406 4.676 8.955 8.955 0 004.676 1.406z" />
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            Approved Budget
                        </dt>
                        <dd>
                            <div class="text-3xl font-bold text-gray-900">
                                ${{ number_format($approvedBudget, 2) }}
                            </div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

</div>
