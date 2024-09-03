<div class="container">
    <h1>Reports</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Plan</th>
                <th>Sells</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $item)
                <tr>
                    <td>{{ $item->business_id??'' }}</td>
                    <td>{{ $item->business->name??'' }}</td>
                    <td>{{ $item->business->plan??'' }}</td>
                    <td>{{ $item->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>