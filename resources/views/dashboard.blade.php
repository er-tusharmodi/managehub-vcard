<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My vCards Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Account Joined -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-blue-500">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Account Joined</p>
                                <p class="text-2xl font-bold text-gray-900">
                                    {{ $joinedDate ? $joinedDate->format('d M Y') : 'N/A' }}
                                </p>
                            </div>
                            <svg class="w-10 h-10 text-blue-200" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM15 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2h-2zM5 13a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Plan Expires -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-orange-500">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Plan Expires</p>
                                <p class="text-2xl font-bold text-gray-900">
                                    {{ $plannedExpiry ? $plannedExpiry->format('d M Y') : 'Never' }}
                                </p>
                            </div>
                            <svg class="w-10 h-10 text-orange-200" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Visitors -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-500">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Total Visitors</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $totalVisitors }}</p>
                            </div>
                            <svg class="w-10 h-10 text-green-200" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM9 12a6 6 0 11-12 0 6 6 0 0112 0zM12.93 11.93a1 1 0 10-1.414-1.414L9 12.172V10a1 1 0 10-2 0v4a1 1 0 101 1h3a1 1 0 100-2h-1.07l1.93-1.93z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Active vCards -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-purple-500">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Active vCards</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $activeVcards }} / {{ $totalVcards }}</p>
                            </div>
                            <svg class="w-10 h-10 text-purple-200" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 5a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V5zM12 3a1 1 0 01.967.744l1.146 2.31 2.537.366a1 1 0 01.557 1.703l-1.837 1.79.434 2.528a1 1 0 01-1.488 1.054l-2.267-1.193-2.267 1.193a1 1 0 01-1.488-1.054l.434-2.528L9.293 8.66a1 1 0 01.557-1.703l2.537-.366 1.146-2.31A1 1 0 0112 3z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- vCards Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">My vCards</h3>

                    @if ($vcards->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No vCards</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating your first vCard from the admin dashboard.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-gray-50 border-b">
                                    <tr>
                                        <th class="px-4 py-3 font-semibold text-gray-700">vCard Name</th>
                                        <th class="px-4 py-3 font-semibold text-gray-700">Status</th>
                                        <th class="px-4 py-3 font-semibold text-gray-700">Template</th>
                                        <th class="px-4 py-3 font-semibold text-gray-700">URL</th>
                                        <th class="px-4 py-3 font-semibold text-gray-700">Visitors</th>
                                        <th class="px-4 py-3 font-semibold text-gray-700">Last Updated</th>
                                        <th class="px-4 py-3 font-semibold text-gray-700">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($vcards as $vcard)
                                        <tr class="border-b hover:bg-gray-50 transition">
                                            <td class="px-4 py-3">
                                                <div class="font-medium text-gray-900">{{ $vcard->client_name }}</div>
                                                <div class="text-xs text-gray-500">{{ $vcard->client_email }}</div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($vcard->status === 'active') bg-green-100 text-green-800
                                                    @elseif($vcard->status === 'draft') bg-gray-100 text-gray-800
                                                    @elseif($vcard->status === 'pending_verification') bg-yellow-100 text-yellow-800
                                                    @else bg-red-100 text-red-800
                                                    @endif
                                                ">
                                                    {{ ucfirst(str_replace('_', ' ', $vcard->status)) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="text-gray-700">{{ ucfirst($vcard->template_key) }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <a href="{{ url('/' . $vcard->subdomain) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-xs break-all">
                                                    {{ $vcard->subdomain }}.{{ config('vcard.base_domain') }}
                                                </a>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex flex-col text-xs">
                                                    <span class="font-semibold">{{ $visitorsByVcard[$vcard->id]['total'] ?? 0 }}</span>
                                                    <span class="text-gray-500">
                                                        (Today: {{ $visitorsByVcard[$vcard->id]['today'] ?? 0 }},
                                                        Month: {{ $visitorsByVcard[$vcard->id]['month'] ?? 0 }})
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="text-gray-600">{{ $vcard->updated_at->format('d M Y') }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex gap-2">
                                                    <a href="{{ route('vcard.editor', $vcard->subdomain) }}" class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition">
                                                        Edit
                                                    </a>
                                                    <a href="{{ url('/' . $vcard->subdomain) }}" target="_blank" class="px-3 py-1 bg-gray-600 text-white text-xs rounded hover:bg-gray-700 transition">
                                                        View
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

