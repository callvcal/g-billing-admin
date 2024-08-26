<div class="container">
    <h1>Reports</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Location</th>
                <th>Entry</th>
                <th>Departure</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $item)
                <tr>
                    <td>{{ $item->business_id }}</td>
                    <td>{{ $item->business_key }}</td>
                    <td>{{ $item->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>