<style>
    .header {
        color: black;
        text-align: left;
        font-family:'FreeMono, monospace';
    }
    .header img {
        width: 150px;
        vertical-align: middle;
    }
    .header h1 {
        display: inline;
        font-size: 24px;
    }
    .content {
        padding: 20px;
    }

    .container {
        width: 80%;
        margin: auto;
        overflow: hidden;
        font-family:'FreeMono, monospace';

    }

    .minimal-table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    font-size: 1em;
    text-align: left;
    color: #333;
    }

    .minimal-table th,
    .minimal-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #ddd;
    }

    .minimal-table thead th {
        border-bottom: 2px solid #333;
        font-weight: bold;
    }

    .minimal-table tbody tr {
        text-align: center;
    }

    .minimal-table tbody tr:nth-of-type(even) {
        background-color: #f9f9f9;
    }

    .signature {
        margin-top: 40px;
        text-align: center;
        padding: 20px;
        width:100%;
    }

    .signature p {
        display: inline;
        margin-right: 20px;
    }

    </style>

<div class="contenedor">
    <img src="storage/images/fapabe-isologo.png" style="margin:0 auto;width:50%;position:absolute;z-index:0;top:25%;opacity:0.06">

    <div class="header">
        <img src="storage/images/fapabe.png" alt="Logo" style="float:right">
        <h1>Resúmen de compra</h1>
    </div>

    <div class="container">
        <h2>Detalles de Animales</h2>
        <table class="minimal-table">
            <thead>
                <tr>
                    <th>RFID</th>
                    <th>Categoria</th>
                    <th>Peso</th>
                    <th>Grado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($animals as $animal)

                    <tr>
                        <td>{{ $animal['rfid'] }}</td>
                        <td>{{ $animal['category'] }}</td>
                        <td>{{ $animal['weight'] }} Kg</td>
                        <td>{{ $animal['grade'] }}</td>
                    </tr>

                @endforeach
            </tbody>
        </table>
    </div>

    <div class="signature">
        <img src="storage/images/f2.png" alt="Firma Veterinario" width="200px" style="float:right;padding-right:30px">
        <img src="storage/images/f1.png" alt="Firma Barlovento" width="200px" style="float:left;">
    </div>

</div>