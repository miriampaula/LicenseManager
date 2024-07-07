<?php


session_start();



$db = mysqli_connect("localhost:3306", "root", "");
mysqli_select_db($db, "licente");

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['nume'])) {
    $nume = $_SESSION['nume'];
} else {

    header("Location: login.php");
    exit;
}

?>



<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">


    <style>
        .custom-hover:hover {
            color: #82fe88;
            text-shadow: 2px 2px 4px rgba(255, 255, 255, 0.5);
        }

        .custom-hover-red:hover {
            color: #E97451;
            text-shadow: 2px 2px 4px rgba(255, 255, 255, 0.5);
        }

        input.editable-input {
            width: 100%;
            height: 100%;
            border: none;
            background: transparent;
            color: inherit;
            font-size: inherit;
            font-family: inherit;
            padding: 0;
            outline: none;
            cursor: text;
        }

        input.editable-input[readonly]:hover {
            cursor: text;
        }

        .splash-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 999;
        }

        .splash-screen.loaded {
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s, visibility 0s 0.2s;
        }
    </style>
</head>

<body style="background: linear-gradient(to right, black, #3d5c3d); font-family: Georgia, 'Times New Roman', Times, serif;">

    <div class="splash-screen" style="background: linear-gradient(to right, black, #3d5c3d)"></div>
    <nav class="border-gray-200" style="background: linear-gradient(to right, black, #3d5c3d);">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="" class="flex items-center">
                <span class="self-center text-5xl font-semibold whitespace-nowrap" style="color: #90c090; font-size: 40px;">Licenses</span>
            </a>
            <div class="flex items-center md:order-2">
                <form action="logout.php" method="post">
                    <button type="submit" class="text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-xl rounded-full text-2xl px-10 py-2.5 text-center mr-2 mb-2 mt-5 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800" style="background-color: #424B44; color:#d0d0da;">Log Out</button>
                </form>
            </div>
        </div>
    </nav>

    <div id="popup-modal" tabindex="-1" data-id="" class="fixed top-0 left-0 right-0 bottom-0 z-50 hidden p-4 overflow-x-hidden overflow-y-auto flex items-center justify-center">
        <div class="relative w-full max-w-md max-h-full">
            <div class="relative rounded-lg shadow bg-black">
                <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="popup-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="p-6 text-center">
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to delete this ?</h3>
                    <button id="stergere' . $rowIndex . '" onclick="stergere()" data-modal-hide="popup-modal" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                        Yes, I'm sure
                    </button>
                    <button data-modal-hide="popup-modal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">No, cancel</button>
                </div>
            </div>
        </div>
    </div>



    <div class="relative w-3/4 overflow-x-auto mx-auto my-10 sm:rounded-lg">

        <button type="button" onclick="adaugare_rand()" class="text-white focus:outline-none font-xl rounded-full text-2xl px-10 py-2.5 text-center ml-auto mr-2 mb-2 mt-5 bbg-green-600 hover:bg-green-700 focus:ring-green-800" style="background-color: #424B44; color:#d0d0da;">+License</button>

    </div>
    <div class="relative w-3/4 overflow-x-auto mx-auto my-10 shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left bg-gray-600 dark:text-gray-400">
            <thead class="text-xl uppercase text-gray-400" style="background-color: #222922; color:#90c090">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Email
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Date
                    </th>
                    <th scope="col" class="px-6 py-3">
                        No license
                    </th>
                    <th scope="col" class="px-6 py-3">

                    </th>
                </tr>
            </thead>
            <tbody id="lista">



                <?php
                $sqlv = "SELECT * FROM element ORDER BY id DESC";
                $stmt = mysqli_prepare($db, $sqlv);
                mysqli_stmt_execute($stmt);

                $resultv = mysqli_stmt_get_result($stmt);

                if ($resultv) {

                    $rowIndex = 1; 

                    while ($myrow = mysqli_fetch_array($resultv)) {
                        $nume = $myrow['nume'];
                        $email = $myrow['email'];
                        $data = $myrow['data_'];
                        $nr_lic = $myrow['nr_licenta'];
                        $id_inregistrare = $myrow['id'];


                        echo '
                            <tr id="' . $id_inregistrare . '" class="bg-gray-500 border-gray-700" style="background-color: #424B44; color:#d0d0da; font-size:18px;">
                    
                    <th scope="row" class="px-6 py-4 whitespace-nowrap text-white">
                    <input class="editable-input" readonly value="' . $nume . '" type="text" id="nume' . $rowIndex . '">
                    </th>
                    <td class="px-6 py-4">
                    <input class="editable-input" readonly value="' . $email . '" type="text" id="email' . $rowIndex . '">
                    </td>
                    <td class="px-6 py-4">
                    <input class="editable-input" readonly value="' . $data . '" type="text" id="data' . $rowIndex . '">
                    </td>
                    <td class="px-6 py-4">
                    <input class="editable-input" readonly value="' . $nr_lic . '" type="text" id="licenta' . $rowIndex . '">
                    </td>
                    <td class="px-6 py-4">
                        <a href="" id="editare' . $rowIndex . '" onclick="editare(' . $id_inregistrare . ', event, ' . $rowIndex . ')" style="font-size: xx-large;" class="custom-hover">&#9998;</a>
                        <button type="button" class="custom-hover-red" onclick="trimite_id_stergere(' . $id_inregistrare . ')" data-modal-target="popup-modal" data-modal-toggle="popup-modal" href="" id="stergere' . $rowIndex . '" style="font-size: 30px; width: 40px; height:40px; margin:auto;">&#128465;</button>
                        </td>
                    </tr>

                        ';

                        $rowIndex++; 
                    }
                } else {
                    echo "Fara date in baza de date.";
                } ?>


            </tbody>
        </table>
    </div>



    <footer class="p-4 md:p-8 lg:p-10" style="background: linear-gradient(to right, black, #3d5c3d);">
        <div class="mx-auto max-w-screen-xl text-center">
            <a href="#" class="flex justify-center items-center text-2xl font-semibold text-white">
                <svg class="mr-2 h-8" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M25.2696 13.126C25.1955 13.6364 24.8589 14.3299 24.4728 14.9328C23.9856 15.6936 23.2125 16.2264 22.3276 16.4114L18.43 17.2265C17.8035 17.3575 17.2355 17.6853 16.8089 18.1621L14.2533 21.0188C13.773 21.5556 13.4373 21.4276 13.4373 20.7075C13.4315 20.7342 12.1689 23.9903 15.5149 25.9202C16.8005 26.6618 18.6511 26.3953 19.9367 25.6538L26.7486 21.7247C29.2961 20.2553 31.0948 17.7695 31.6926 14.892C31.7163 14.7781 31.7345 14.6639 31.7542 14.5498L25.2696 13.126Z" fill="url(#paint0_linear_11430_22515)" />
                    <path d="M23.5028 9.20133C24.7884 9.94288 25.3137 11.0469 25.3137 12.53C25.3137 12.7313 25.2979 12.9302 25.2694 13.1261L28.0141 14.3051L31.754 14.5499C32.2329 11.7784 31.2944 8.92561 29.612 6.65804C28.3459 4.9516 26.7167 3.47073 24.7581 2.34097C23.167 1.42325 21.5136 0.818599 19.8525 0.486816L17.9861 2.90382L17.3965 5.67918L23.5028 9.20133Z" fill="url(#paint1_linear_11430_22515)" />
                    <path d="M1.5336 11.2352C1.5329 11.2373 1.53483 11.238 1.53556 11.2358C1.67958 10.8038 1.86018 10.3219 2.08564 9.80704C3.26334 7.11765 5.53286 5.32397 8.32492 4.40943C11.117 3.49491 14.1655 3.81547 16.7101 5.28323L17.3965 5.67913L19.8525 0.486761C12.041 -1.07341 4.05728 3.51588 1.54353 11.2051C1.54233 11.2087 1.53796 11.2216 1.5336 11.2352Z" fill="url(#paint2_linear_11430_22515)" />
                    <path d="M19.6699 25.6538C18.3843 26.3953 16.8003 26.3953 15.5147 25.6538C15.3402 25.5531 15.1757 25.4399 15.0201 25.3174L12.7591 26.8719L10.8103 30.0209C12.9733 31.821 15.7821 32.3997 18.589 32.0779C20.7013 31.8357 22.7995 31.1665 24.7582 30.0368C26.3492 29.1191 27.7 27.9909 28.8182 26.7195L27.6563 23.8962L25.7762 22.1316L19.6699 25.6538Z" fill="url(#paint3_linear_11430_22515)" />
                    <path d="M15.0201 25.3175C14.0296 24.5373 13.4371 23.3406 13.4371 22.0588V21.931V11.2558C13.4371 10.6521 13.615 10.5494 14.1384 10.8513C13.3323 10.3864 11.4703 8.79036 9.17118 10.1165C7.88557 10.858 6.8269 12.4949 6.8269 13.978V21.8362C6.8269 24.775 8.34906 27.8406 10.5445 29.7966C10.6313 29.874 10.7212 29.9469 10.8103 30.0211L15.0201 25.3175Z" fill="url(#paint4_linear_11430_22515)" />
                    <path d="M28.6604 5.49565C28.6589 5.49395 28.6573 5.49532 28.6589 5.49703C28.9613 5.83763 29.2888 6.23485 29.6223 6.68734C31.3648 9.05099 32.0158 12.0447 31.4126 14.9176C30.8093 17.7906 29.0071 20.2679 26.4625 21.7357L25.7761 22.1316L28.8181 26.7195C34.0764 20.741 34.09 11.5388 28.6815 5.51929C28.6789 5.51641 28.67 5.50622 28.6604 5.49565Z" fill="url(#paint5_linear_11430_22515)" />
                    <path d="M7.09355 13.978C7.09354 12.4949 7.88551 11.1244 9.17113 10.3829C9.34564 10.2822 9.52601 10.1965 9.71002 10.1231L9.49304 7.38962L7.96861 4.26221C5.32671 5.23364 3.1897 7.24125 2.06528 9.83067C1.2191 11.7793 0.75001 13.9294 0.75 16.1888C0.75 18.0243 1.05255 19.7571 1.59553 21.3603L4.62391 21.7666L7.09355 21.0223V13.978Z" fill="url(#paint6_linear_11430_22515)" />
                    <path d="M9.71016 10.1231C10.8817 9.65623 12.2153 9.74199 13.3264 10.3829L13.4372 10.4468L22.3326 15.5777C22.9566 15.9376 22.8999 16.2918 22.1946 16.4392L22.7078 16.332C23.383 16.1908 23.9999 15.8457 24.4717 15.3428C25.2828 14.4782 25.5806 13.4351 25.5806 12.5299C25.5806 11.0468 24.7886 9.67634 23.503 8.93479L16.6911 5.00568C14.1436 3.53627 11.0895 3.22294 8.29622 4.14442C8.18572 4.18087 8.07756 4.2222 7.96875 4.26221L9.71016 10.1231Z" fill="url(#paint7_linear_11430_22515)" />
                    <path d="M20.0721 31.8357C20.0744 31.8352 20.0739 31.8332 20.0717 31.8337C19.6252 31.925 19.1172 32.0097 18.5581 32.0721C15.638 32.3978 12.7174 31.4643 10.5286 29.5059C8.33986 27.5474 7.09347 24.7495 7.09348 21.814L7.09347 21.0222L1.59546 21.3602C4.1488 28.8989 12.1189 33.5118 20.0411 31.8421C20.0449 31.8413 20.0582 31.8387 20.0721 31.8357Z" fill="url(#paint8_linear_11430_22515)" />
                    <defs>
                        <linearGradient id="paint0_linear_11430_22515" x1="20.8102" y1="23.9532" x2="23.9577" y2="12.9901" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#1724C9" />
                            <stop offset="1" stop-color="#1C64F2" />
                        </linearGradient>
                        <linearGradient id="paint1_linear_11430_22515" x1="28.0593" y1="10.5837" x2="19.7797" y2="2.33321" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#1C64F2" />
                            <stop offset="1" stop-color="#0092FF" />
                        </linearGradient>
                        <linearGradient id="paint2_linear_11430_22515" x1="16.9145" y1="5.2045" x2="4.42432" y2="5.99375" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#0092FF" />
                            <stop offset="1" stop-color="#45B2FF" />
                        </linearGradient>
                        <linearGradient id="paint3_linear_11430_22515" x1="16.0698" y1="28.846" x2="27.2866" y2="25.8192" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#1C64F2" />
                            <stop offset="1" stop-color="#0092FF" />
                        </linearGradient>
                        <linearGradient id="paint4_linear_11430_22515" x1="8.01881" y1="15.8661" x2="15.9825" y2="24.1181" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#1724C9" />
                            <stop offset="1" stop-color="#1C64F2" />
                        </linearGradient>
                        <linearGradient id="paint5_linear_11430_22515" x1="26.2004" y1="21.8189" x2="31.7569" y2="10.6178" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#0092FF" />
                            <stop offset="1" stop-color="#45B2FF" />
                        </linearGradient>
                        <linearGradient id="paint6_linear_11430_22515" x1="6.11387" y1="9.31427" x2="3.14054" y2="20.4898" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#1C64F2" />
                            <stop offset="1" stop-color="#0092FF" />
                        </linearGradient>
                        <linearGradient id="paint7_linear_11430_22515" x1="21.2932" y1="8.78271" x2="10.4278" y2="11.488" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#1724C9" />
                            <stop offset="1" stop-color="#1C64F2" />
                        </linearGradient>
                        <linearGradient id="paint8_linear_11430_22515" x1="7.15667" y1="21.5399" x2="14.0824" y2="31.9579" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#0092FF" />
                            <stop offset="1" stop-color="#45B2FF" />
                        </linearGradient>
                    </defs>
                </svg>
                Flowbite
            </a>
            <p class="my-6 text-gray-400">Open-source library of over 400+ web components and interactive elements built for better web.</p>
            <ul class="flex flex-wrap justify-center items-center mb-6 text-white">
                <li>
                    <a href="#" class="mr-4 hover:underline md:mr-6 ">About</a>
                </li>
                <li>
                    <a href="#" class="mr-4 hover:underline md:mr-6">Premium</a>
                </li>
                <li>
                    <a href="#" class="mr-4 hover:underline md:mr-6 ">Campaigns</a>
                </li>
                <li>
                    <a href="#" class="mr-4 hover:underline md:mr-6">Blog</a>
                </li>
                <li>
                    <a href="#" class="mr-4 hover:underline md:mr-6">Affiliate Program</a>
                </li>
                <li>
                    <a href="#" class="mr-4 hover:underline md:mr-6">FAQs</a>
                </li>
                <li>
                    <a href="#" class="mr-4 hover:underline md:mr-6">Contact</a>
                </li>
            </ul>
            <span class="text-sm sm:text-center text-gray-400">© 2021-2022 <a href="#" class="hover:underline">Flowbite™</a>. All Rights Reserved.</span>
        </div>
    </footer>
    <script src="https://unpkg.com/@barba/core"></script>

    <script>
        function stergere() {


            let modal = document.getElementById('popup-modal');
            let id_inregistrare = modal.getAttribute('data-id');

            //   alert("stergem inregistrare");
            //   alert(id_inregistrare);


            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    //  alert(this.responseText)
                    location.reload();
                }
            };
            var url = "stergere_inregistrare.php?id=" + encodeURIComponent(id_inregistrare);
            xmlhttp.open("GET", url, true);
            xmlhttp.send();
        }


        function editare(id_inregistrare, event, rowIndex) {
            event.preventDefault();
            var clickedRow = document.getElementById(id_inregistrare);

            if (!clickedRow) {
                //     alert("Row not found!");
                return;
            }

            var nume_input = document.getElementById("nume" + rowIndex);
            var email_input = document.getElementById("email" + rowIndex);
            var data_input = document.getElementById("data" + rowIndex);
            var licenta_input = document.getElementById("licenta" + rowIndex);
            var icon_editare = document.getElementById("editare" + rowIndex);
            var icon_stergere = document.getElementById("stergere" + rowIndex);



            if (nume_input.readOnly) {
                nume_input.readOnly = false;
                email_input.readOnly = false;
                data_input.readOnly = false;
                licenta_input.readOnly = false;
                icon_editare.innerHTML = "&#10003";
                icon_stergere.innerHTML = "";


            } else {

                nume_input.readOnly = true;
                email_input.readOnly = true;
                data_input.readOnly = true;
                licenta_input.readOnly = true;
                icon_editare.innerHTML = "&#9998";
                icon_stergere.innerHTML = "&#x1F5D1";

                var updatedName = nume_input.value;
                var updatedEmail = email_input.value;
                var updatedData = data_input.value;
                var updateLicenta = licenta_input.value;

                //        alert(updatedName);


                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        //        alert(this.responseText);

                        location.reload();
                    }
                };


                var url = "editare_inregistrare.php?id=" + encodeURIComponent(id_inregistrare);
                var params = "name=" + encodeURIComponent(updatedName) + "&email=" + encodeURIComponent(updatedEmail) + "&data=" + encodeURIComponent(updatedData) + "&lic=" + encodeURIComponent(updateLicenta);
                xmlhttp.open("POST", url, true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send(params);

            }
        }

        function get_last_id() {
            <?php
            $sqlv = "SELECT id FROM element ORDER BY id DESC LIMIT 1";
            $resultv = mysqli_query($db, $sqlv);
            $row = mysqli_fetch_assoc($resultv);

            $lastId = null;
            if ($row) {
                $lastId = $row['id'];
            } else {
                
            
            }

            ?>

            var lastId = <?php echo json_encode($lastId); ?>;
            return lastId;
        }



        function adaugare_rand() {


            var lista = document.getElementById('lista');


            var id = parseInt(get_last_id(), 10);
            id = id + 1;
            //       alert(id);
            var newRowIndex = 1;
            var newRowHTML = `
            <tr id="${id}" class="bg-gray-500 border-gray-700" style="background-color: #424B44; color:#d0d0da; font-size:18px;">
                    
                    <th scope="row" class="px-6 py-4 whitespace-nowrap text-white">
                    <input class="editable-input" value="" type="text" id="nume${newRowIndex}">
                    </th>
                    <td class="px-6 py-4">
                    <input class="editable-input" value="" type="text" id="email${newRowIndex}">
                    </td>
                    <td class="px-6 py-4">
                    <input class="editable-input" value="" type="text" id="data${newRowIndex}">
                    </td>
                    <td class="px-6 py-4">
                    <input class="editable-input" value="" type="text" id="licenta${newRowIndex}">
                    </td>
                    <td class="px-6 py-4">
                        <a href="#" id="editare${newRowIndex}" onclick="adaugare_in_db(event)" style="font-size: xx-large;" class="custom-hover">&#10003;</a>
                    </td>
                    </tr>
                `;


            lista.insertAdjacentHTML('afterbegin', newRowHTML);

            var rows = lista.getElementsByClassName('');
            for (var i = 0; i < rows.length; i++) {
                rows[i].querySelector(`[id^="nume"]`).id = `nume${i + 1}`;
                rows[i].querySelector(`[id^="email"]`).id = `email${i + 1}`;
                rows[i].querySelector(`[id^="data"]`).id = `data${i + 1}`;
                rows[i].querySelector(`[id^="licenta"]`).id = `licenta${i + 1}`;
            }

        }



        function adaugare_in_db(event) {


            event.preventDefault(); 


            var nameInput = document.getElementById("nume1");
            var emailInput = document.getElementById("email1");
            var dataInput = document.getElementById("data1");
            var licentaInput = document.getElementById("licenta1");
            var Icon_editare = document.getElementById("editare1");
            var Icon_stergere = document.getElementById("stergere1");




            if (nameInput.readOnly) {
                nameInput.readOnly = false;
                emailInput.readOnly = false;
                dataInput.readOnly = false;
                licentaInput.readOnly = false;
                Icon_editare.innerHTML = "&#10003";
                Icon_stergere.innerHTML = "";

            } else {

                nameInput.readOnly = true;
                emailInput.readOnly = true;
                dataInput.readOnly = true;
                licentaInput.readOnly = true;
                Icon_editare.innerHTML = "&#9998";
                Icon_stergere.innerHTML = "&#x1F5D1";

                var updated_Name = nameInput.value;
                var updated_Email = emailInput.value;
                var updated_Data = dataInput.value;
                var updated_Licenta = licentaInput.value;


                var xml = new XMLHttpRequest();
                xml.onreadystatechange = function() {
                    if (this.readyState == 4) {
                        if (this.status == 200) {
                            location.reload();
                        } else {
                            alert("Error: " + this.status + "\n" + this.responseText);
                        }
                    }
                };

                var url1 = "adaugare_inregistrare.php";
                var params1 = "name=" + encodeURIComponent(updated_Name) + "&email=" + encodeURIComponent(updated_Email) + "&data=" + encodeURIComponent(updated_Data) + "&lic=" + encodeURIComponent(updated_Licenta);
                xml.open("POST", url1, true);
                xml.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xml.send(params1);
                xml.onerror = function() {
                    alert("Request error occurred");
                };






            }

        }

        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('popup-modal');
            const openBtns = document.querySelectorAll('[data-modal-toggle="popup-modal"]'); // Select all open buttons
            const closeBtns = document.querySelectorAll('[data-modal-hide="popup-modal"]');

            const showModal = () => {
                modal.classList.remove('hidden');
            };

            const hideModal = () => {
                modal.classList.add('hidden');
            };

            openBtns.forEach(btn => {
                btn.addEventListener('click', showModal);
            });

            closeBtns.forEach(btn => {
                btn.addEventListener('click', hideModal);
            });
        });

        window.addEventListener('load', function() {
            const splashScreen = document.querySelector('.splash-screen');
            splashScreen.classList.add('loaded');
        });


        function trimite_id_stergere(id) {
        //    alert(id);
            let modal = document.getElementById('popup-modal');
            modal.setAttribute('data-id', id);
        }
    </script>
</body>


</html>