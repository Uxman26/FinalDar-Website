<table class="table">
    <thead>
        <tr>
            <th>Kroki No</th>
            <th>Title</th>
            <th>Type</th>
            <th>Size</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($listings as $listing)
        <tr>
            <td>{{ $listing->serial_no }}</td>
            <td>{{ $listing->title }}</td>
            <td>{{ $listing->type }}</td>
            <td>{{ $listing->size }} sqm</td>
            <td>{{ $listing->created_at->format('d M Y') }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="5">No listings found.</td>
        </tr>
        @endforelse
    </tbody>
</table>