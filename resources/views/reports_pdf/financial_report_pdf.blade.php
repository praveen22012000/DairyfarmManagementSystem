<!DOCTYPE html>
<html>
<head>
    <title>Milk Production Report</title>
    <style>
        body { font-family: 'Arial', sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: center; }
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

    <div class="report-title">Financial Report</div>
    <div class="report-period">
        <strong>From:</strong> {{ $start }} <strong>To:</strong> {{ $end }}
    </div>

    <h3 style="text-align:left;">Income</h3>
        <table>
                <thead class="thead-dark">
                    <tr>
                        <th>Income Way</th>
                        <th>Total Income</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>Milk Product Sales</td>
                        <td>{{ $milk_product_total_income }}</td>
                    </tr>
          
                </tbody>
        </table>
     <p style="color: blue; font-weight: bold;font-size:18px;">
    Total Income: Rs. {{ number_format($total_income, 2) }}
    </p>
<br><br>


<h3 style="text-align:left;">Expenses</h3>
    <table>
        <thead>
            <tr>
                <th>Expenses Way</th>
                <th>Total Expenses</th>
            </tr>
        </thead>
        <tbody>
          
                <tr>
                    <td>Feed Purchases</td>
                    <td>{{ $purchase_feed_items_expenses }}</td>
                </tr>

                <tr>
                    <td>Vaccine Purchases</td>
                    <td>{{ $purchase_vaccine_items_expenses }}</td>
                </tr>

                <tr>
                    <td>Staff Salary</td>
                    <td>{{ $total_salary_expenses }}</td>
                </tr>
          
        </tbody>
    </table>
   <p style="color: blue; font-weight: bold;font-size:18px;">
    Total Expenses: Rs. {{ number_format($total_expenses, 2) }}
</p>
</div>


@if($final_value > 0)

     <p style="color:green;font-size:20px;font-weight:bold;">Profit: Rs. {{ number_format($final_value, 2) }}</p>

@else

     <p style="color:red;font-size:20px;font-weight:bold;">Loss: Rs. {{ number_format(abs($final_value), 2) }}</p>

@endif





</body>
</html>