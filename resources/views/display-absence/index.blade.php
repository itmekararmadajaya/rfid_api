<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Display Absence</title>

    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>

    @vite(['resources/js/app.js', 'resources/css/app.css'])

  </head>
  <body class="bg-red-700 flex justify-center items-center h-screen lg:mx-62 md:mx-5 mx-5">
    <div class="container mx-auto p-4">
        <h1 class="text-8xl text-center font-extrabold text-yellow-300 mb-6 drop-shadow-lg tracking-wide">
            Selamat Datang
        </h1>
        <div class="" id="parent">
            {{-- <div class="bg-red-900 p-6 rounded-lg shadow-lg text-center border-4 border-yellow-400">
                <h2 class="text-7xl font-semibold text-yellow-300">Lorem, ipsum dolor</h2>
                <br>
                <h2 class="text-7xl font-semibold text-yellow-300">你好 世界 好世</h2>
                <br>
                <p class="text-6xl text-yellow-200">Jabatan</p>
                <br>
                <p class="text-6xl text-yellow-200">Kota</p>
                <br>
                <p class="text-6xl text-yellow-200">31</p>
            </div> --}}
        </div>
    </div>

    <script type="module">
        var data_temp = []; //temp variable for check max of data

        var channel = Echo.channel(`rfid-middleware`);
        channel.listen('RfidMiddlewareEvent', function(data) {
            var data = data['data'];

            if(!data){
                console.log("Data is null or undefined")
                return;
            }

            data_temp.push(data);
            var parent = document.getElementById("parent");
            parent.innerHTML = '';

            /** 
             * Add new data to view
            */
            var newDiv = document.createElement('div');
            newDiv.classList.add('col');

            newDiv.innerHTML = `
                <div class="bg-red-900 p-6 rounded-lg shadow-lg text-center border-4 border-yellow-400">
                    <h2 class="text-7xl font-semibold text-yellow-300">${data['name']}</h2>
                    <br>
                    <h2 class="text-7xl font-semibold text-yellow-300">${data['mandarin_name']}</h2>
                    <br>
                    <p class="text-6xl text-yellow-200">${data['position']}</p>
                    <br>
                    <p class="text-6xl text-yellow-200">${data['city']}</p>
                    <br>
                    <p class="text-6xl text-yellow-200">Nomor Meja : ${data['table_no']}</p>
                </div>
            `;

            parent.appendChild(newDiv);
        });
    </script>
  </body>
</html>