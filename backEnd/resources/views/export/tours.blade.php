<table>
    <thead>
        <tr>
            @foreach($columns as $column)
                <th style="width: 250px; ">{{ $column['name'] }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
    @foreach($tours as $t)
        <tr>
            <td>{{ $t->id }} ---  {{ $t->name }}</td>
            <td>{{ $t->url }}</td>
            <td>{{ $t->language }}</td>
            <td>{{ $t->sub_title }}</td>
            <td>{{ $t->rank }}</td>
            <td>{{ $t->avaible }}</td>
            <td>{{ $t->is_private==0 ? 'No':'Si' }}</td>

            <td>{{ $t->duration }}</td>
            <td>{{ $t->public == 0 ? 'No': 'Si' }}</td>

            @if($t->is_private)
                    <td>
                        @foreach($t->rates as $rate)
                            <p> Rango de personas: {{  $rate['pax'] }}</p>
                            <p> Desde Fake: {{  $rate['rate_from_fake'] }}</p>
                            <p> Desde Real: {{  $rate['rate_from_real'] }}</p>
                            <p> Precio Fake: {{  $rate['real_price'] }}</p>
                            <p> Precio Real: {{  $rate['pax'] }}</p>
                            <p> </p>

                        @endforeach
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>

            @else
                <td></td>
                <td>{{ $t->fake_adult }}</td>
                <td>{{ $t->real_adult }}</td>
                <td>{{ $t->price_fake_child_mxn }}</td>
                <td>{{ $t->price_real_child_mxn }}</td>

            @endif





        </tr>
    @endforeach
    </tbody>
</table>
