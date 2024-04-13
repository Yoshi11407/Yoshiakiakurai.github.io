@extends('layouts.app')

@secction('title','Admin Post')

@secction('content')
    <tabele class="table table-hover align-middle bg-white bordert text-secondary">
      <thead class="table-primary text-secondary small">
        <th></th>
        <th></th>
        <th>CATEGORY</th>
        <th>OWNER</th>
        <th>CREATED</th>
        <th>STATUS</th>
      </thead>
    <tbody>
        @forelse($all_posts as $post)
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </tabele>
@endsection