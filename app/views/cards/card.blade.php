<!DOCTYPE html>
<html>
<head>
    <title>Somato | Digital Business Cards</title>
</head>

<style>
    .card
    {
        width: 100%;
        border-radius: 10px;
        -moz-box-shadow:    0px 0px 10px 3px #5c5c5c;
        -webkit-box-shadow: 0px 0px 10px 3px #5c5c5c;
        box-shadow:         0px 0px 10px 3px #5c5c5c;
    }
</style>

<body>

    <!-- Show the card as an image -->
    <img src="/cards/{{ $svg }}.svg" alt="Business Card" class="card">

</body>
</html>