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
            <td>{{ $t->name }}</td>
            <td>{{ $t->url }}</td>
            <td>{{ $t->language }}</td>
            <td>{{ $t->sub_title }}</td>
            <td>{{ $t->rank }}</td>
            <td>{{ $t->avaible }}</td>
            <td>{{ $t->private==0 ? 'No':'Si' }}</td>

            <td>{{ $t->duration }}</td>
            <td>{{ $t->public == 0 ? 'No': 'Si' }}</td>





        </tr>
    @endforeach
    </tbody>
</table>
