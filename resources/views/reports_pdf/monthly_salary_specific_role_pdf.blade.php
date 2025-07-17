<!DOCTYPE html>
<html>
<head>
    <title>Milk Production Report</title>
    <style>
        body { font-family: 'Arial', sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header-container { 
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #1a73e8;
            padding-bottom: 10px;
        }
        .logo { width: 70px; height: 70px; margin-right: 15px; }
        .farm-info { flex: 1; }
        .farm-name { 
            color: #1a73e8;
            font-size: 42px;
            font-weight: bold;
            margin-bottom: 5px;
            text-align: center;
        }
        .farm-details {
            text-align: center;
            font-size: 15px;
            line-height: 1.5;
            color: #555;
        }
        .report-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0;
        }
        .report-period {
            text-align: center;
            margin-bottom: 15px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header-container">
        <!-- Logo -->
     
        
        <!-- Farm Info -->
        <div class="farm-info">
            <div class="farm-name">Maruthi Dairy Farm</div>
            <div class="farm-details">
            Address: Polikandy,Valvettithurai,Jaffna<br>
                Phone: 077 9425447 | Email: sgajaa1994@gmail.com
            </div>
        </div>
    </div>

    <div class="report-title">Salary Allocation by Role Report</div>
    <div class="report-period">
        <strong>From:</strong> {{ $start }} <strong>To:</strong> {{ $end }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Month</th>
                <th>Total Amount(Rs.)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($salaryPerMonth as $sal)
                <tr>
                    <td>{{  $sal->month }}</td>
                    <td>{{ $sal->total_salary}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
   
</body>
</html>