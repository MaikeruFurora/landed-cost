@extends('../_layout/app')
@section('content')
<style>
/* .table {
  background: #ee99a0;
  border-radius: 0.2rem;
  width: 100%;
  padding-bottom: 1rem;
  color: #212529;
  margin-bottom: 0;
}

.table th:first-child,
.table td:first-child {
  position: sticky;
  left: 0;
  background-color: #ad6c80;
  color: #373737;
}

.table td {
  white-space: nowrap;
} */
</style>
    <!-- Page-Title -->
    <x-page-title title="user management">
        <a class="btn btn-primary btn-sm" href="{{ route('authenticate.user.create') }}">
            <i class="ti-plus mr-1"></i> Create
        </a>
    </x-page-title>
    <!-- end page title end breadcrumb -->
    @if (session()->has('msg'))
        <div class="alert alert-{{ session()->get('action') ?? 'success' }}" role="alert">
            @if(session()->has('icon'))
                {{ session()->get('icon') }}
            @else
                <i class="far fa-check-circle"></i>
            @endif
            {{ session()->get('msg') }}
        </div>
    @endif
   <div class="card">
        <div class="card-body p-0">
           <div class="table-responsive">
           <table class="table table-striped">
                <thead>
                    <tr>
                        <th># Date ID</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                @forelse($users as $i => $user)
                    <tr>
                        <td>{{ strtotime($user->updated_at) }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <a href="{{ route('authenticate.user.edit',[$user->id]) }}" style="font-size:11px"
                            class="btn btn-primary btn-sm pl-2 pr-2"><i class="fas fa-edit"></i> Edit</a>
                        </td>
                    </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No data available</td>
                </tr>
                @endforelse
            </table>
        </div>
    </div>
    <div class="card-footer p-3">
    {{ $users->links() }}
    </div>
    
   </div>
@endsection