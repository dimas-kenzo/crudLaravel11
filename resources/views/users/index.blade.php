@extends('layouts.main')
@section('content')
<div>
    <h1 class="mt-5">List User</h1>
    <a href="{{ route('users.create') }}" class="btn btn-primary">Tambah User</a>
    <table class="table mt-3">
      <thead>
          <tr>
              <th scope="col">No</th>
              <th scope="col">Nama</th>
              <th scope="col">Email</th>
              <th scope="col">Created At</th>
              <th scope="col">Aksi</th>
          </tr>
      </thead>
      <tbody>
          @if($users->isEmpty())
              <tr>
                  <td colspan="4" class="text-center">Tidak Ada Data User</td>
              </tr>
          @else
              @foreach($users as $user)
                  <tr>
                      <th scope="row">{{ $loop->iteration }}</th>
                      <td>{{ $user->name }}</td>
                      <td>{{ $user->email }}</td>
                      <td>{{ $user->created_at }}</td>
                      <td>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus user {{ $user->name }}?')">Hapus</button>
                        </form>
                    </td>
                  </tr>
              @endforeach
          @endif
      </tbody>
  </table>
</div>
@endsection
