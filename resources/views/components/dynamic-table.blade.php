@props(['columns' => [], 'rows' => []])

<div class="overflow-x-auto bg-white rounded-xl border-grey p-4">
    <table id="tableKeuangan" class="min-w-full text-sm text-left text-gray-600 border">
        <thead class="bg-mid text-white text-xs uppercase">
            <tr>
                @foreach ($columns as $col)
                <th class="px-4 py-3 bg-mid">{{ $col }}</th>
                @endforeach
                <th class="px-4 py-3 text-center bg-mid">Actions</th>
            </tr>
        </thead>
        <tbody id="tbodyData"></tbody>
    </table>
</div>

<div id="table-data" data-rows='@json($rows)'></div>

<script type="text/javascript">
    const allData = JSON.parse(document.getElementById('table-data').dataset.rows);
    const tbody = document.getElementById('tbodyData');

    function renderTable(data) {
        tbody.innerHTML = '';
        if (!data.length) {
            tbody.innerHTML = `<tr><td colspan="{{ count($columns) + 1 }}" class="text-center py-4 text-gray-400">Tidak ada data</td></tr>`;
            return;
        }
        data.forEach(row => {
            const tr = document.createElement('tr');
            tr.classList.add('border-grey', 'hover:bg-gray-50');
            tr.innerHTML = `
                ${row.map(item => `<td class='px-4 py-3'>${item}</td>`).join('')}
                <td class="px-4 py-3 text-center text-gray-400">+</td>
            `;
            tbody.appendChild(tr);
        });
    }

    renderTable(allData);
</script>