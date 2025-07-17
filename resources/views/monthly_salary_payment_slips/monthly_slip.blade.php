<!DOCTYPE html>
<html>
<head>
    <title>Payment Slip</title>
      <style>
        body { 
            font-family: 'Arial', sans-serif; 
            font-size: 12px; 
            margin: 20px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
        }
        th, td { 
            border: 1px solid #333; 
            padding: 8px; 
            text-align: center; 
        }
        th { 
            background-color: #f2f2f2; 
        }
        .header-container { 
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #1a73e8;
            padding-bottom: 10px;
        }
        .logo { 
            width: 70px; 
            height: 70px; 
            margin-right: 15px; 
        }
        .farm-info { 
            flex: 1; 
        }
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
            color: #333;
        }
        .payment-details {
            margin: 20px auto;
            width: 80%;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .payment-details p {
            margin: 8px 0;
            font-size: 14px;
        }
        .payment-details strong {
            color: #1a73e8;
        }
        .reference-number {
            text-align: center;
            font-size: 14px;
            margin-bottom: 15px;
            color: #555;
        }
        .payment-date {
            text-align: center;
            font-style: italic;
            margin-bottom: 20px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header-container">
        <!-- Farm Info -->
        <div class="farm-info">
            <div class="farm-name">Maruthy Dairy Farm</div>
            <div class="farm-details">
                Address: Polikandy,Valvettithurai,Jaffna<br>
                Phone: 077 9425447 | Email: sgajaa1994@gmail.com
            </div>
        </div>
    </div>


            <h1 style="text-align:center;color:blue;">Monthly Salary Assignemnt Details</h1>
   
 

    <div class="payment-details">
        <p><strong>Beneficiary Employee :</strong>  {{ $user->name }}</p>
        <p><strong>Salary Month:</strong> {{ $monthly_salary_assignment->salary_month }}</p>
        <p><strong>Amount of Salary :</strong>{{ $monthly_salary_assignment->amount_paid }}</p>
        <p><strong>Salary Assignment Date :</strong> {{ $monthly_salary_assignment->paid_at }}</p>
    </div>


    



</body>
</html>
