
<!DOCTYPE html>
<html>
<head>
    <style>
        #customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #customers td, #customers th {
            border: 1px solid #ddd;
            padding: 4px;
        }
        #customers tr:nth-child(even){background-color: #f2f2f2;}
        #customers tr:hover {background-color: #ddd;}
        #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>


<table id="customers">
    <tr>
        <td><h2>
                <img src="" width="200" height="100">

            </h2></td>
        <td><h2>Adarsha school</h2>
            <p>Uttor badda, Dhaka</p>
            <p>Phone : 346677444</p>
            <p>Email : support@adarsha.com</p>
            <p> <b> Student Registration Fee</b> </p>

        </td>
    </tr>


</table>

<table id="customers">
    <tr>
        <th width="10%">Sl</th>
        <th width="45%">Student Details</th>
        <th width="45%">Student Data</th>
    </tr>
    <tr>
        <td>1</td>
        <td><b>Student ID No</b></td>
        <td>{{ $data->id_no }}</td>
    </tr>
    <tr>
        <td>2</td>
        <td><b>Roll No</b></td>
        <td>{{ $data->roll }}</td>
    </tr>

    <tr>
        <td>3</td>
        <td><b>Student Name</b></td>
        <td>{{ $data->student_name }}</td>
    </tr>

    <tr>
        <td>4</td>
        <td><b>Father's Name</b></td>
        <td>{{ $data->father_name }}</td>
    </tr>
    <tr>
        <td>5</td>
        <td><b>Session</b></td>
        <td>{{ $data->year }}</td>
    </tr>
    <tr>
        <td>6</td>
        <td><b>Class </b></td>
        <td>{{ $data->class }}</td>
    </tr>
    <tr>
        <td>7</td>
        <td><b>Registration Fee</b></td>
        <td>{{ $feeAmount->amount }} $</td>
    </tr>
    <tr>
        <td>8</td>
        <td><b>Discount Fee </b></td>
        <td>{{ $data->discount  }} %</td>
    </tr>

    <tr>
        <td>9</td>
        <td><b>Fee For this Student </b></td>
        <td>{{ $finalAmount }} $</td>
    </tr>



</table>
<br> <br>
<i style="font-size: 10px; float: right;">Print Data : {{ date("d M Y") }}</i>

<hr style="border: dashed 2px; width: 95%; color: #000000; margin-bottom: 50px">

<table id="customers">
    <tr>
        <th width="10%">Sl</th>
        <th width="45%">Student Details</th>
        <th width="45%">Student Data</th>
    </tr>
    <tr>
        <td>1</td>
        <td><b>Student ID No</b></td>
        <td>{{ $data->id_no }}</td>
    </tr>
    <tr>
        <td>2</td>
        <td><b>Roll No</b></td>
        <td>{{ $data->roll }}</td>
    </tr>

    <tr>
        <td>3</td>
        <td><b>Student Name</b></td>
        <td>{{ $data->student_name }}</td>
    </tr>

    <tr>
        <td>4</td>
        <td><b>Father's Name</b></td>
        <td>{{ $data->father_name }}</td>
    </tr>
    <tr>
        <td>5</td>
        <td><b>Session</b></td>
        <td>{{ $data->year }}</td>
    </tr>
    <tr>
        <td>6</td>
        <td><b>Class </b></td>
        <td>{{ $data->class }}</td>
    </tr>
    <tr>
        <td>7</td>
        <td><b>Registration Fee</b></td>
        <td>{{ $feeAmount->amount }} $</td>
    </tr>
    <tr>
        <td>8</td>
        <td><b>Discount Fee </b></td>
        <td>{{ $data->discount  }} %</td>
    </tr>

    <tr>
        <td>9</td>
        <td><b>Fee For this Student </b></td>
        <td>{{ $finalAmount }} $</td>
    </tr>



</table>
<br> <br>
<i style="font-size: 10px; float: right;">Print Data : {{ date("d M Y") }}</i>







</body>
</html>
