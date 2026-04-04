@extends('layouts.app')

@section('title', 'Manage Users | Lost and Found')

@section('content')
<div style="max-width: 1160px; margin: 0 auto; display: grid; gap: 18px;">
    <div class="card" style="margin: 0;">
        <div style="display: flex; justify-content: space-between; gap: 14px; align-items: center; flex-wrap: wrap; margin-bottom: 14px;">
            <div>
                <h2 style="font-size: 24px; font-weight: 800; margin-bottom: 6px;">Manage Users</h2>
                <p style="font-size: 14px; color: var(--text-gray);">View all users, filter by role/status, and block or unblock accounts.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">Back to Dashboard</a>
        </div>

        <form method="GET" action="{{ route('admin.users.index') }}" style="display: grid; grid-template-columns: minmax(0, 1fr) 170px 170px auto; gap: 10px; align-items: center;">
            <input class="form-input" type="text" name="q" value="{{ $filters['q'] }}" placeholder="Search by name or email">

            <select class="form-select" name="role">
                <option value="">All roles</option>
                <option value="user" @selected($filters['role'] === 'user')>User</option>
                <option value="admin" @selected($filters['role'] === 'admin')>Admin</option>
            </select>

            <select class="form-select" name="blocked">
                <option value="">All status</option>
                <option value="yes" @selected($filters['blocked'] === 'yes')>Blocked</option>
                <option value="no" @selected($filters['blocked'] === 'no')>Active</option>
            </select>

            <button class="btn btn-outline" type="submit">Filter</button>
        </form>
    </div>

    @if (session('success'))
        <div class="alert alert-success" style="margin-bottom: 0;">{{ session('success') }}</div>
    @endif

    @if ($errors->has('admin'))
        <div class="alert alert-error" style="margin-bottom: 0;">{{ $errors->first('admin') }}</div>
    @endif

    <div class="card" style="margin: 0; overflow-x: auto;">
        @if ($users->count() === 0)
            <p style="font-size: 14px; color: var(--text-gray);">No users found.</p>
        @else
            <table style="width: 100%; border-collapse: collapse; min-width: 980px;">
                <thead>
                    <tr>
                        <th style="text-align: left; padding: 10px; border-bottom: 1px solid var(--border-color);">Name</th>
                        <th style="text-align: left; padding: 10px; border-bottom: 1px solid var(--border-color);">Email</th>
                        <th style="text-align: left; padding: 10px; border-bottom: 1px solid var(--border-color);">Role</th>
                        <th style="text-align: left; padding: 10px; border-bottom: 1px solid var(--border-color);">Status</th>
                        <th style="text-align: left; padding: 10px; border-bottom: 1px solid var(--border-color);">Joined</th>
                        <th style="text-align: left; padding: 10px; border-bottom: 1px solid var(--border-color);">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td style="padding: 12px 10px; border-bottom: 1px solid var(--border-color); color: var(--text-dark); font-weight: 600;">{{ $user->name }}</td>
                            <td style="padding: 12px 10px; border-bottom: 1px solid var(--border-color); color: var(--text-gray);">{{ $user->email }}</td>
                            <td style="padding: 12px 10px; border-bottom: 1px solid var(--border-color);">
                                <span class="badge badge-neutral" style="text-transform: capitalize;">{{ $user->role }}</span>
                            </td>
                            <td style="padding: 12px 10px; border-bottom: 1px solid var(--border-color);">
                                @if ($user->is_blocked)
                                    <span class="badge" style="background: #fef2f2; color: #b91c1c;">Blocked</span>
                                @else
                                    <span class="badge" style="background: #f0fdf4; color: #166534;">Active</span>
                                @endif
                            </td>
                            <td style="padding: 12px 10px; border-bottom: 1px solid var(--border-color); color: var(--text-gray);">{{ $user->created_at?->format('M d, Y') }}</td>
                            <td style="padding: 12px 10px; border-bottom: 1px solid var(--border-color);">
                                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                    <a class="btn btn-outline" style="padding: 7px 12px;" href="{{ route('admin.users.show', $user) }}">View Details</a>

                                    @if ($user->role !== 'admin')
                                        @if (! $user->is_blocked)
                                            <form method="POST" action="{{ route('admin.users.block', $user) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button class="btn" type="submit" style="padding: 7px 12px; border-color: #fecaca; background: #fef2f2; color: #b91c1c;">Block</button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.users.unblock', $user) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button class="btn" type="submit" style="padding: 7px 12px; border-color: #bbf7d0; background: #f0fdf4; color: #166534;">Unblock</button>
                                            </form>
                                        @endif

                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user account? This should be used only for repeated fake activity.');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline" type="submit" style="padding: 7px 12px;">Delete</button>
                                        </form>
                                    @else
                                        <span style="font-size: 13px; color: var(--text-gray);">Admin account</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top: 16px;">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
