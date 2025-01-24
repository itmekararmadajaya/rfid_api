<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Display Absence</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    @vite(['resources/js/app.js', 'resources/css/app.css'])

  </head>
  <body class="bg-light d-flex justify-content-center p-5">

    <div class="bg-white border rounded w-100 p-3">
        <div class="text-center mb-3">
            <span class="h4">List RFID</span>
        </div>
        <div class="row row-cols-1 row-cols-md-2 g-4" id="parent">
            {{-- @foreach ($rfid_datas as $rfid)
                <div class="col">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5 class="card-title">{{$rfid->tag_no}} (Tag No)</h5>
                            <p class="card-text">{{$rfid->client_type}} (Client Type)</p>
                            <p class="card-text">{{$rfid->reader_no}} (Reader No)</p>
                        </div>
                    </div>
                </div>
            @endforeach --}}
        </div>
    </div>

    <script type="module">
        var data_temp = []; //temp variable for check max of data

        var channel = Echo.channel('rfid-middleware');
        channel.listen('RfidMiddlewareEvent', function(data) {
            var data = data['data'];

            data_temp.push(data);
            var parent = document.getElementById("parent");

            /** 
             * Delete childrem when array is max
            */
            if(data_temp.length == 3){
                parent.innerHTML = '';
            }

            /** 
             * Add new data to view
            */
            var newDiv = document.createElement('div');
            newDiv.classList.add('col');

            newDiv.innerHTML = `
                <div class="card bg-light">
                    <div class="card-body">
                        <h5 class="card-title">${data['tag_no']} (Tag No)</h5>
                        <p class="card-text">${data['client_type']} (Client Type)</p>
                        <p class="card-text">${data['reader_no']} (Reader No)</p>
                    </div>
                </div>
            `;

            parent.appendChild(newDiv);
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>