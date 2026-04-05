@extends('layouts.app')

@section('title', 'Contact Messages | Admin')

@section('content')
<section class="card" style="margin-bottom: 20px;">
    <h1 class="page-title" style="margin-bottom: 8px;">Contact Messages</h1>
    <p class="page-subtitle">Manage and respond to customer messages.</p>
</section>

<section class="card">
    <div class="section-head">
        <h2>All Messages</h2>
    </div>

    @if ($messages->count())
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Received</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($messages as $message)
                        <tr>
                            <td>
                                <span class="badge {{ $message->status === 'new' ? 'badge-found' : ($message->status === 'read' ? 'badge-neutral' : 'badge-found') }}">{{ ucfirst($message->status) }}</span>
                            </td>
                            <td>{{ $message->name }}</td>
                            <td><a href="mailto:{{ $message->email }}" style="color: var(--primary); text-decoration: none;">{{ $message->email }}</a></td>
                            <td>{{ $message->subject }}</td>
                            <td>{{ $message->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.contact-messages.show', $message) }}" class="btn btn-outline" style="padding: 7px 12px; font-size: 12px;">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $messages->links() }}
        </div>
    @else
        <div class="empty-state">
            No contact messages yet.
        </div>
    @endif
</section>
@endsection
