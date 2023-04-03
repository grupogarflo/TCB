<!DOCTYPE html>
<html>
<head>
    <title>Tour cancellation request</title>
    <style>
        p{
            font-size: 1.2rem;
        }
        h4{
            font-size: 1.3rem;
        }
    </style>

</head>
<body>

    <h4 style="margin-top:10px;">User make request cancellation:</h4>
    <p>Name: {{$details['userName']}}</p>

    <h4 style="margin-top:10px;">Client information:</h4>
    <p>Name: {{ $details['client'] }}</p>
    <p>Email: {{ $details['email'] }}</p>
    <p>Phone: {{ $details['phone'] }}</p>
    <p>Hotel: {{ $details['hotel'] }}</p>
    <p>Code book: {{ $details['codeBook'] }}</p>
    <p>Site: {{ $details['siteBook'] }}</p>
    <p>Language: {{ $details['language'] }}</p>
    <p>Status: {{ $details['status'] }}</p>
    <p>Total: {{ $details['total'] }} {{ $details['currency']}}</p>
    <p>Authorization: {{ $details['authorization'] }}</p>

    <h4 style="margin-top:10px;">Tour information:</h4>

    <p>Tour: {{ $details['toursId'] }}</p>
    <p>Date: {{ $details['date'] }}</p>
    <p>Adults: {{ $details['adults'] }}</p>
    <p>Child: {{ $details['child'] }}</p>
    <p>Promocode: {{ $details['promocode'] }}</p>
    <p>Discount: {{ $details['discount'] }}</p>
</body>
</html>