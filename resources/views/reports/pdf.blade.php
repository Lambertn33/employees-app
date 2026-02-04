<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Daily Attendance Report</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
        }

        h1 {
            font-size: 18px;
            margin: 0 0 6px;
        }

        .meta {
            margin-bottom: 12px;
            color: #444;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
        }

        th {
            background: #f4f4f4;
            text-align: left;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 11px;
        }

        .present {
            background: #e7f7ee;
        }

        .absent {
            background: #fdeaea;
        }
    </style>
</head>

<body>
    <h1>Daily Attendance Report</h1>
    <div class="meta">
        <div><strong>Date:</strong> {{ $date }}</div>
        <div><strong>Generated at:</strong> {{ $generated_at }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Code</th>
                <th>Names</th>
                <th>Telephone</th>
                <th>Arrived</th>
                <th>Left</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $row['code'] }}</td>
                    <td>{{ $row['names'] }}</td>
                    <td>{{ $row['telephone'] }}</td>
                    <td>{{ $row['arrived_at'] ? \Carbon\Carbon::parse($row['arrived_at'])->format('H:i:s') : '-' }}</td>
                    <td>{{ $row['left_at'] ? \Carbon\Carbon::parse($row['left_at'])->format('H:i:s') : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
