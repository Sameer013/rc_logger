<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DataTable with Headers in Column and Serial Number</title>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
</head>
<body>

<table id="example" class="display" style="width:100%">
  <thead>
    <tr>
      <th>Serial Number</th>
      <th>Value</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody></tbody>
</table>
<script>
  $(document).ready(function() {
    $('#example').DataTable({
      "paging": false,
      "ajax": {
        "url": "display_data.php",
        "method": "GET",
        "dataSrc": "data" 
      },
      "columns": [
        {"data": "serialNumber"},
        {"data": "value"},
        {"data": "status"},
      ]
    });
  });
</script>

</body>
</html>