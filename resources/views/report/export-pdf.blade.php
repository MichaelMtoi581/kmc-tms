<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #333; }
        h2 { text-align: center; margin-bottom: 4px; font-size: 16px; }
        .subtitle { text-align: center; color: #666; margin-bottom: 15px; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 5px 6px; text-align: left; }
        th { background-color: #f5f5f5; font-weight: bold; font-size: 9px; text-transform: uppercase; }
        td { font-size: 9px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { text-align: center; font-size: 8px; color: #999; margin-top: 15px; border-top: 1px solid #eee; padding-top: 8px; }
        .badge { display: inline-block; padding: 1px 5px; border-radius: 3px; font-size: 8px; }
    </style>
</head>
<body>
    <h2>{{ $title }}</h2>
    @isset($subtitle)
        <div class="subtitle">{{ $subtitle }}</div>
    @endisset

    <table>
        <thead>
            <tr>
                @foreach($headings as $heading)
                    <th>{{ $heading }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                <tr>
                    @foreach($row as $cell)
                        <td>{!! $cell !!}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ now()->format('d/m/Y H:i') }} | KMC - Training Management System
    </div>
</body>
</html>
