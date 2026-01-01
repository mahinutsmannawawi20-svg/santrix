@extends('owner.layouts.app')

@section('title', 'Activity Logs')
@section('subtitle', 'Audit trail of all owner actions on tenants.')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                    <th class="px-6 py-4">Timestamp</th>
                    <th class="px-6 py-4">User</th>
                    <th class="px-6 py-4">Action</th>
                    <th class="px-6 py-4">Subject</th>
                    <th class="px-6 py-4">Details</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($logs as $log)
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="px-6 py-4 text-sm text-slate-600">
                        {{ $log->created_at->format('d M Y H:i') }}
                    </td>
                    <td class="px-6 py-4">
                        @if($log->causer)
                            <div class="text-sm font-medium text-slate-800">{{ $log->causer->name }}</div>
                            <div class="text-xs text-slate-400">{{ $log->causer->email }}</div>
                        @else
                            <span class="text-xs text-slate-400 italic">System</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-700">
                        {{ $log->description }}
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        @if($log->subject)
                            <a href="{{ route('owner.pesantren.show', $log->subject_id) }}" class="text-indigo-600 hover:underline">
                                {{ $log->subject->nama ?? 'Pesantren #'.$log->subject_id }}
                            </a>
                        @else
                            <span class="text-slate-400">Deleted</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if(!empty($log->properties))
                            <button type="button" onclick="alert(JSON.stringify({{ json_encode($log->properties) }}, null, 2))" class="text-xs text-indigo-600 hover:underline">
                                View JSON
                            </button>
                        @else
                            <span class="text-xs text-slate-400">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="mx-auto w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h3 class="text-slate-900 font-medium">No activity logs yet</h3>
                        <p class="text-slate-500 text-sm mt-1">Actions on tenants will appear here.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($logs->hasPages())
    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
        {{ $logs->links() }}
    </div>
    @endif
</div>
@endsection
