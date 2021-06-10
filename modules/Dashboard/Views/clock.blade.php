@push('css')
    <style>
        .bg-clock {
            margin-left: -20px;
            margin-bottom: 20px;
            display: flex;
            width: 102.1%;
            justify-content: center;
            padding: 20px 0 30px 0;
            min-height: 280px;
            background: #FFA17F;
            background: -webkit-linear-gradient(to right, #00223E, #FFA17F);
            background: linear-gradient(to right, #00223E, #FFA17F);
        }

        #dong_ho #thoi_gian {
            display: flex;
        }

        #dong_ho #thoi_gian div {
            position: relative;
            margin: 0 5px;
        }

        #dong_ho #thoi_gian div span {
            position: relative;
            width: 90px;
            height: 100px;
            background: #2196f3;
            color: #fff;
            font-weight: 400;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 3rem;
            z-index: 3;
            box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.2);
        }

        #dong_ho #thoi_gian span:nth-child(2) {
            height: 30px;
            font-size: 1rem;
            z-index: 2;
            box-shadow: none;
            background: #127fd6;
            text-transform: capitalize;
        }

        #dong_ho #thoi_gian div:last-child span {
            background: #ff006a;
        }

        #dong_ho #thoi_gian div:last-child span:nth-child(2) {
            background: #ec0062;
        }

        #dong_ho #thoi_gian div {
            position: relative;
            margin: 0 5px;
            -webkit-box-reflect: below 10px linear-gradient(transparent, #0004);
        }

        @media only screen and (max-width: 739px) {
            .bg-clock {
                width: 110.5%;
            }
        }
    </style>
@endpush
<div class="bg-clock">
    <div id="dong_ho">
        <div id="thoi_gian">
            <div>
                <span id="gio">00</span><span>Hour</span>
            </div>
            <div>
                <span id="phut">00</span><span>Minute</span>
            </div>
            <div>
                <span id="giay">00</span><span>Second</span>
            </div>
        </div>
    </div>
</div>
@push('js')
    <script>
        function Dong_ho() {
            var gio = document.getElementById("gio");
            var phut = document.getElementById("phut");
            var giay = document.getElementById("giay");
            var Gio_hien_tai = new Date().getHours();
            var Phut_hien_tai = new Date().getMinutes();
            var Giay_hien_tai = new Date().getSeconds();
            gio.innerHTML = Gio_hien_tai;
            phut.innerHTML = Phut_hien_tai;
            giay.innerHTML = Giay_hien_tai;
        }

        var Dem_gio = setInterval(Dong_ho, 1000);
    </script>
@endpush
