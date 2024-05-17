<div class="container">
    <div class="card">
        <div class="card-body" style="background-color: {{ $data['color'][0] }}; border:bl">
            <h5 class="card-title" style="color: {{ $data['color'][1] }};">Table {{ $data['table']->number }}</h5>
            <p class="card-text" style="color: {{ $data['color'][1] }};"> {{ $data['table']->customer_name ??'-'}}</p>
            <p class="card-text" style="color: {{ $data['color'][1] }};"> {{ $data['table']->status ??'-' }}</p>
            <p class="card-text" style="color: {{ $data['color'][1] }};"> {{isset($data['table']->amount)?"Rs. ":'-'. $data['table']->amount }}</p>
        </div>
    </div>
</div>
