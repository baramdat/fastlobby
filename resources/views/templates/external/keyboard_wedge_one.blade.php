<html>
    <head>
        <title>Simple barcode scanner</title>
    </head>
    <script>
        var barcode = '';
        var interval;
        document.addEventListener('keydown', function(evt){
            if(interval)
                clearInterval(interval);
                if(evt.code == 'Enter'){
                    if (barcode)
                    handleBarcode(barcode);
                    barcode = '';
                    return;
                }
            
            if(evt.key != 'Shift')
            barcode += evt.key;
            interval = setInterval(()=> barcode = '', 20);
        });
        function handleBarcode(scanned_barcode){
      $data = scanned_barcode;
        document.querySelector('#last-barcode').innerHTML = $data;
        $.ajax({
            type: "get",
            url: "/api/get/appointment/details",
            data: {data:data},
            dataType: "JSON",
            success: function (response) {
                console.log(response);          }
        });
        }
    </script>
    <body>
        <h1>Simple barcode scanner</h1>
        <strong>last scanned barcode </strong>
        <div id="last-barcode"></div>
    </body>
</html>