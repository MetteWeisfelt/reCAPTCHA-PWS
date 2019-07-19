<h2>Result</h2>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Subcategory / Count / Selected / Total Selected / Subcategory Percentage / Total Percentage</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                @if (count($imagePieceResult))
                    @foreach ($imagePieceResult as $result)
                        {{$result['subcategoryName']}} / {{$result['count']}} / {{$result['selected']}} / {{$result['totalSelected']}} / {{number_format($result['subcategoryPercentage'], 2, '.', ',')}}% / {{number_format($result['totalPercentage'], 2, '.', ',')}}%<br/>
                    @endforeach
                @else
                    No data available
                @endif
            </td>
        </tr>
    </tbody>
</table>