<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Display Absence</title>

    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>

    @vite(['resources/js/app.js', 'resources/css/app.css'])

  </head>
  <body class="bg-red-700 flex justify-center h-screen mx-5">
    <div class="lg:grid lg:grid-cols-3 gap-2 w-full">
        <div class="border rounded-lg border-4 border-yellow-400 p-2">
            <div class="mb-3">
                <span id="attendance-count" class="text-yellow-300 text-xl font-semibold">Jumlah Absen : </span>
            </div>
            <div class="" id="parent-latest-attendance">
                {{-- <div class="mb-2 bg-red-900 p-3 rounded-lg shadow-lg text-center border-4 border-yellow-400">
                    <h2 class="text-4xl font-semibold text-yellow-300">Lorem, ipsum dolor</h2>
                    <h2 class="text-4xl font-semibold text-yellow-300">你好 世界 好世</h2>
                    <p class="text-2xl text-yellow-200">Jabatan</p>
                    <p class="text-2xl text-yellow-200">Kota</p>
                    <p class="text-2xl text-yellow-200">31</p>
                </div> --}}
            </div>
        </div>
        <div class="container mx-auto p-4 col-span-2">
            <div class="flex justify-center mb-5">
                <img src="{{asset('asset/Logo Fu-Qing bg putih.png')}}" alt="" srcset="" style="width: 200px; height: 200px;">
            </div>
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
    </div>

    <script type="module">
        var ip = "http://192.168.200.3:8083/";
        attendanceCount(ip);
        
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

            getLatestAttendance(ip);
            attendanceCount(ip);
        });

        function getLatestAttendance(ip){
            var data_temp_latest_attendance = [];

            fetch(`${ip}api/get-latest-attendance/3`)
                .then(response => {
                    if(!response.ok){
                        console.log("API get latest attendance error");
                    }

                    return response.json();
                })
                .then(data => {
                    var parent = document.getElementById("parent-latest-attendance");
                    parent.innerHTML = '';

                    data.forEach(participant => {
                       /** 
                         * Add new data to view
                        */
                        var newDiv = document.createElement('div');
                        newDiv.classList.add('col');

                        newDiv.innerHTML = `
                            <div class="mb-2 bg-red-900 p-3 rounded-lg shadow-lg text-center border-4 border-yellow-400">
                                <h2 class="text-4xl font-semibold text-yellow-300">${participant['name']}</h2>
                                <h2 class="text-4xl font-semibold text-yellow-300">${participant['mandarin_name']}</h2>
                                <p class="text-2xl text-yellow-200">${participant['position']}</p>
                                <p class="text-2xl text-yellow-200">${participant['city']}</p>
                                <p class="text-2xl text-yellow-200">Nomor Meja : ${participant['table_no']}</p>
                            </div>
                        `;

                        parent.appendChild(newDiv); 
                    });
                })
                .catch(error => {
                    console.log(error)
                })
        }

        function attendanceCount(ip){

            fetch(`${ip}api/get-attendance-today`)
            .then(response => {
                if (!response.ok) {
                    console.log("API get attendance today error");
                }
                return response.json();
            })
            .then(data => {
                var attendance_count = document.getElementById('attendance-count');
                attendance_count.textContent = `Jumlah Absen : ${data}`;
            })
            .catch(error => {
                console.log("Error:", error);
            });

        }
    </script>
  </body>
</html>