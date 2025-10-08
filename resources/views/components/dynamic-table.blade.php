@props(['columns' => [], 'rows' => []])

<div class="overflow-x-auto bg-white rounded-xl border-grey p-4">
    {{-- Tombol Filter --}}
    <div class="mb-4 flex">
        <button id="btnSemua"
            class="filter-btn px-4 py-2 mx-1 rounded-xl bg-blue-500 text-white hover:bg-blue-600">
            Semua
        </button>
        <button id="btnSaving"
            class="filter-btn px-4 py-2 mx-1 rounded-xl bg-green-500 text-white hover:bg-green-600">
            Saving
        </button>
    </div>

    {{-- Table --}}
    <table id="tableKeuangan" class="min-w-full text-sm text-left text-gray-600 border">
        <thead class="bg-mid text-white text-xs uppercase">
            <tr>
                @foreach ($columns as $col)
                    <th class="px-4 py-3 bg-mid">{{ $col }}</th>
                @endforeach
                <th class="px-4 py-3 text-center bg-mid">Actions</th>
            </tr>
        </thead>
        <tbody id="tbodyData">
            @foreach ($rows as $row)
                <tr class="border-grey hover:bg-gray-50">
                    @foreach ($columns as $col)
                        <td class="px-4 py-3">
                            {{ $row[$loop->index] ?? '-' }}
                        </td>
                    @endforeach
                    <td class="px-4 py-3 text-center text-gray-400">
                        <button class="circle-more flex justify-center items-center">
                            <a href="#">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                    width="24px" fill="#888">
                                    <path
                                        d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z" />
                                </svg>
                            </a>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Script untuk filter --}}
<script>
    // Ambil semua data awal dari PHP dan konversi ke JS
    // const allData = @json($rows);

    const tbody = document.getElementById('tbodyData');

    function renderTable(data) {
        tbody.innerHTML = '';
        if (data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="{{ count($columns) + 1 }}" class="text-center py-4 text-gray-400">Tidak ada data</td></tr>`;
            return;
        }

        data.forEach(row => {
            const tr = document.createElement('tr');
            tr.classList.add('border-grey', 'hover:bg-gray-50');
            tr.innerHTML = `
                ${row.map(item => `<td class='px-4 py-3'>${item}</td>`).join('')}
                <td class="px-4 py-3 text-center text-gray-400">
                    <button class="circle-more flex justify-center items-center">
                        <a href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                width="24px" fill="#888">
                                <path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z" />
                            </svg>
                        </a>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    // Tampilkan semua data di awal
    renderTable(allData);

    // Event handler tombol
    document.getElementById('btnSemua').addEventListener('click', () => renderTable(allData));

    document.getElementById('btnSaving').addEventListener('click', () => {
        // Misalnya filter kategori atau sumber berisi kata "Kas"
        const filtered = allData.filter(row => row[2].toLowerCase().includes('kas'));
        renderTable(filtered);
    });
</script>
