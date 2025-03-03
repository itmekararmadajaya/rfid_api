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
        <div class="container mx-auto p-4 col-span-2">
            <div class="flex justify-center mb-5">
                <img src="{{asset('asset/Logo Fu-Qing bg putih.png')}}" alt="" srcset="" style="width: 200px; height: 200px;">
            </div>
            <div class="" id="parent">
                
            </div>
        </div>
        <div class="border rounded-lg border-4 border-yellow-400 p-2">
            <div class="mb-3 flex justify-between">
                <span id="attendance-count" class="text-yellow-300 text-xl font-semibold">Jumlah Absen : </span>
                <button id="testSound" class="text-yellow-300 text-base font-semibold" style="cursor: pointer;">Test Sound</button>
                {{-- <button id="testSound2" class="text-yellow-300 text-base font-semibold">Test Sound 2</button> --}}
            </div>
            <div class="" id="parent-latest-attendance">
                
            </div>
        </div>
    </div>

    <script type="module">
        alert("Agar sound dapat diputar, silahkan test sound terlebih dahulu");
        var reader_no = {{$reader_no}};
        
        // var ip = "http://192.168.200.3:8083/";
        var ip = "http://127.0.0.1:8000/";

        attendanceCount(ip, reader_no);
        getLatestAttendance(ip, reader_no);

        var audio_reader = new Audio(`${ip}asset/backsound/by-reader.wav`);
        var audio_manual = new Audio(`${ip}asset/backsound/by-manual.wav`);
        var audio_duplicate = new Audio(`${ip}asset/backsound/duplicate.wav`);

        document.getElementById('testSound').addEventListener('click', function () {
            audio_reader.play().catch(error => console.log("Audio play error:", error));

            setTimeout(() => {
                audio_manual.play().catch(error => console.log("Audio play error:", error));
            }, 1000);

            setTimeout(() => {    
                audio_duplicate.play().catch(error => console.log("Audio play error:", error));
            }, 1000);
        });

        // document.getElementById('testSound2').addEventListener('click', function () {
        //     audio_reader.play().catch(error => console.log("Audio play error:", error));
        //     audio_reader.play().catch(error => console.log("Audio play error:", error));
        //     audio_reader.play().catch(error => console.log("Audio play error:", error));
        // });
        
        console.log(audio_reader);
        
        var data_temp = []; //temp variable for check max of data

        var channel = Echo.channel(`rfid-middleware-reader-${reader_no}`);
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

            if(data['is_new'] == 1){
                if(data['source'] == 'reader'){
                    audio_reader.play();
                }else if(data['source'] == 'manual'){
                    audio_manual.play();
                }
            }else {
                audio_duplicate.play();
            }

            const backgroundColor = data['is_new'] == 1 ? 'bg-red-900' : 'bg-green-900';

            newDiv.innerHTML = `
                <div class="p-6 rounded-lg shadow-lg text-center border-4 ${backgroundColor} border-yellow-400">
                    <h2 class="text-7xl font-semibold text-yellow-300">${data['name'] ? data['name'] : ''}</h2>
                    <br>
                    <h2 class="text-7xl font-semibold text-yellow-300">${data['mandarin_name'] ? data['mandarin_name'] : ''}</h2>
                    <br>
                    <p class="text-6xl text-yellow-200">${data['position'] ? data['position'] : ''}</p>
                    <br>
                    <p class="text-6xl text-yellow-200">${data['city'] ? data['city'] : ''}</p>
                    <br>
                    <p class="text-6xl text-yellow-200">Nomor Meja : ${data['table_no'] ? data['table_no'] : '-'}</p>
                </div>
            `;

            parent.appendChild(newDiv);

            getLatestAttendance(ip, reader_no);
            attendanceCount(ip, reader_no);
        });

        function getLatestAttendance(ip, reader_no){
            var data_temp_latest_attendance = [];

            fetch(`${ip}api/get-latest-attendance/5/${reader_no}`)
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

                        const backgroundColor = participant['is_new'] === 1 ? 'bg-red-900' : 'bg-green-900';

                        newDiv.innerHTML = `
                            <div class="grid grid-cols-3 gap-1 mb-2 p-3 rounded-lg shadow-lg text-left border-4 ${backgroundColor} border-yellow-400">
                                <div class="col-span-2">
                                    <h2 class="text-xl font-semibold text-yellow-300">${participant['name'] ? participant['name'] : ''}</h2>
                                    <h2 class="text-xl font-semibold text-yellow-300">${participant['mandarin_name'] ? participant['mandarin_name'] : ''}</h2>
                                    <p class="text-lg text-yellow-200">${participant['position'] ? participant['position'] : ''}</p>
                                </div>
                                <div>
                                    <p class="text-lg text-yellow-200">${participant['city'] ? participant['city'] : ''}</p>
                                    <p class="text-lg text-yellow-200">Nomor Meja : ${participant['table_no'] ? participant['table_no'] : '-'}</p>
                                </div>
                            </div>
                        `;

                        parent.appendChild(newDiv); 
                    });
                })
                .catch(error => {
                    console.log(error)
                })
        }

        function attendanceCount(ip, reader_no){

            fetch(`${ip}api/get-attendance-today/${reader_no}`)
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