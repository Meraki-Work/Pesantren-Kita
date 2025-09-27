@extends('index')

@section('title', 'Keuangan')


@section('content')
<h1>Keuangan</h1>
@endsection

<div class="flex bg-gray-100">
    <x-sidemenu title="Admin Panel" />

    <main class="flex-1 p-6">
        {{ $slot ?? '' }}
        <div class="w-full sm:w-1/3 lg:w-1/4 flex flex-col rounded-e-md box-bg p-4">
            <div class="flex justify-between">
                <label class="sm:text-lg md:text-xl lg:text-2xl">
                    {{$kategori ?? 'Dummy'}}
                </label>
                <button class="circle-add flex justify-center items-center">
                    <a href="">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#fdfdfd">
                            <path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z" />
                        </svg>
                    </a>
                </button>
            </div>
            <div class="my-2">
                <span class="font-medium sm:text-lg md:text-xl lg:text-2xl">{{$jumlah ?? "Rp. 000.000,00"}}</span>
                <div class="grid">
                    <span class="text-mint text-[8px] sm:text-[10px] md:text-[12px] lg:text-[17px]">
                        Sumber
                    </span>
                    <span class="text-[8px] sm:text-[10px] md:text-[12px] lg:text-[17px]">
                        Kas Tunai, Donasi
                    </span>
                </div>
            </div>
        </div>
        <label class="font-semibold sm:text-lg md:text-xl lg:text-2xl">Cashflow</label>

        <div class="box-bg p-4 w-full h-[64vh] grid justify-center">
            <span class="font-semibold m-2 flex justify-between sm:text-lg md:text-xl lg:text-2xl">Alokasi Asset</span>

            <canvas id="myChart" width="800" height="340vh"></canvas>
            <div id="tooltip" class="font-semibold box-bg"
                style="position:absolute; padding:4px 8px; color:#1E3932; font-size:12px; border-radius:4px; display:none;">
            </div>

            <script>
                const canvas = document.getElementById("myChart");
                canvas.width = canvas.clientWidth;
                canvas.height = canvas.clientHeight;
                const ctx = canvas.getContext("2d");
                const tooltip = document.getElementById("tooltip");

                // Data
                const data = [65, 32, 80, 44, 56, 11, 40];
                const labels = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul"];

                // Chart dimensions
                const paddingLeft = 60;
                const paddingBottom = 30;
                const paddingRight = 40;
                const chartHeight = canvas.height - paddingBottom - 10;
                const chartWidth = canvas.width - paddingLeft - paddingRight;
                const stepX = chartWidth / (data.length - 1);
                const maxVal = Math.max(...data);
                const stepY = 20;

                let points = [];

                function formatRupiah(angka) {
                    return "Rp " + angka.toLocaleString("id-ID", {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });
                }


                function drawChart() {
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    points = [];

                    // Garis data dengan curve
                    ctx.beginPath();
                    for (let i = 0; i < data.length; i++) {
                        const x = paddingLeft + i * stepX;
                        const y = chartHeight - (data[i] / maxVal) * (chartHeight - 10);

                        points.push({
                            x,
                            y,
                            label: labels[i],
                            value: data[i]
                        });

                        if (i === 0) {
                            ctx.moveTo(x, y);
                        } else {
                            const prev = points[i - 1];
                            const cpX = (prev.x + x) / 2;
                            ctx.bezierCurveTo(cpX, prev.y, cpX, y, x, y);
                        }
                    }
                    ctx.strokeStyle = "#2ecc71";
                    ctx.lineWidth = 2;
                    ctx.stroke();

                    // Label Y kiri
                    ctx.textAlign = "right";
                    ctx.textBaseline = "middle";
                    ctx.fillStyle = "#333";
                    for (let y = 0; y <= maxVal; y += stepY) {
                        const yPos = chartHeight - (y / maxVal) * (chartHeight - 10);
                        ctx.fillText(y, paddingLeft - 5, yPos);

                        // garis bantu horizontal kiri
                        ctx.beginPath();
                        ctx.moveTo(paddingLeft, yPos);
                        // ctx.lineTo(paddingLeft + chartWidth, yPos);
                        // ctx.strokeStyle = "#eee";
                        ctx.stroke();
                    }

                    // Label Y kanan
                    ctx.textAlign = "left";
                    for (let y = 0; y <= maxVal; y += stepY) {
                        const yPos = chartHeight - (y / maxVal) * (chartHeight - 10);
                        ctx.fillText(y, paddingLeft + chartWidth + 5, yPos);
                    }

                    // Label X + garis vertikal
                    ctx.textAlign = "center";
                    ctx.textBaseline = "top";
                    labels.forEach((label, i) => {
                        const x = paddingLeft + i * stepX;
                        ctx.fillText(label, x, chartHeight + 5);

                        // garis vertikal
                        ctx.beginPath();
                        ctx.moveTo(x, chartHeight);
                        ctx.lineTo(x, 20);
                        ctx.strokeStyle = "#ECECEE";
                        ctx.stroke();
                    });

                    // Titik data
                    points.forEach(p => {
                        ctx.beginPath();
                        ctx.arc(p.x, p.y, 4, 0, Math.PI * 2);
                        ctx.fillStyle = "#2ecc71";
                        ctx.fill();
                    });
                }

                drawChart();

                // Tooltip interaktif
                canvas.addEventListener("mousemove", (e) => {
                    const rect = canvas.getBoundingClientRect();
                    const mouseX = e.clientX - rect.left;
                    const mouseY = e.clientY - rect.top;

                    let found = null;
                    points.forEach(p => {
                        const dx = mouseX - p.x;
                        const dy = mouseY - p.y;
                        if (Math.sqrt(dx * dx + dy * dy) < 10) {
                            found = p;
                        }
                    });

                    if (found) {
                        drawChart();
                        ctx.beginPath();
                        ctx.arc(found.x, found.y, 6, 0, Math.PI * 2);
                        ctx.fillStyle = "red";
                        ctx.fill();

                        tooltip.style.display = "block";
                        tooltip.style.left = (e.pageX + 10) + "px";
                        tooltip.style.top = (e.pageY - 20) + "px";
                        tooltip.innerHTML = `Total Kas : <br> ${formatRupiah(found.value)}`;
                    } else {
                        tooltip.style.display = "none";
                    }
                });
            </script>
        </div>

    </main>

</div>