<!doctype html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; }
        .title { font-size: 18px; font-weight: bold; margin: 8px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background: #eee; }
        .mt { margin-top: 12px; }
        .sign { border: 2px solid #000; padding: 10px; height: 80px; }
    </style>
</head>
<body>

<div class="header">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <div>
            {{-- Put your logo in public/images/logo-left.png --}}
            <img src="{{ public_path('images/logo-left.png') }}" style="height:60px;">
        </div>
        <div>
            <div><strong>Giunta Regionale della Campania</strong></div>
            <div>Direzione Generale 18 - Lavori Pubblici e Protezione Civile</div>
            <div><strong>STAFF Protezione Civile Emergenza e Post-Emergenza</strong></div>
        </div>
        <div>
            {{-- Put your logo in public/images/logo-right.png --}}
            <img src="{{ public_path('images/logo-right.png') }}" style="height:60px;">
        </div>
    </div>

    <div class="title">ELENCO VOLONTARI IMPIEGATI</div>
    <div>Giorno di impiego: {{ $day }}</div>
</div>

<table class="mt">
    <thead>
        <tr>
            <th style="width:40px;">N</th>
            <th>NOME E COGNOME</th>
            <th style="width:180px;">CODICE FISCALE</th>
            <th style="width:180px;">FIRMA AUTOGRAFA</th>
            <th style="width:100px;">ART.39 SI/NO</th>
        </tr>
    </thead>
    <tbody>
        @foreach($volunteers as $i => $v)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $v->last_name }} {{ $v->first_name }}</td>
                <td>{{ $v->tax_code }}</td>
                <td></td>
                <td>{{ $v->pivot->art39 ? 'SI' : 'NO' }}</td>
            </tr>
        @endforeach
        {{-- Optionally pad to 13 rows like the form --}}
        @for($k = count($volunteers); $k < 13; $k++)
            <tr>
                <td>{{ $k + 1 }}</td>
                <td></td><td></td><td></td><td></td>
            </tr>
        @endfor
    </tbody>
</table>

<div class="title mt">ELENCO VEICOLI ASSOCIATIVI UTILIZZATI</div>

<table class="mt">
    <thead>
        <tr>
            <th style="width:160px;">TIPO</th>
            <th>MODELLO</th>
            <th style="width:160px;">TARGA</th>
        </tr>
    </thead>
    <tbody>
        @foreach($vehicles as $veh)
            <tr>
                <td>{{ $veh->type ?? '' }}</td>
                <td>{{ ($veh->brand ?? '') . ' ' . ($veh->model ?? '') }}</td>
                <td>{{ $veh->plate }}</td>
            </tr>
        @endforeach
        @for($k = count($vehicles); $k < 2; $k++)
            <tr><td></td><td></td><td></td></tr>
        @endfor
    </tbody>
</table>

<div class="mt" style="display:flex; gap:10px;">
    <div class="sign" style="flex:1;">
        <strong>TIMBRO E FIRMA REFERENTE AUTORITÀ DI PROTEZIONE CIVILE</strong>
        <div style="margin-top:45px; border-top:1px solid #000;"></div>
    </div>
    <div class="sign" style="flex:1;">
        <strong>FIRMA LEGALE RAPPRESENTANTE ODV</strong>
        <div style="margin-top:45px; border-top:1px solid #000;"></div>
    </div>
</div>

</body>
</html>
