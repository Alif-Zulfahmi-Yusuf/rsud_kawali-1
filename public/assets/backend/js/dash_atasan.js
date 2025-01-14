document.addEventListener('DOMContentLoaded', function () {
    const selectPegawai = document.getElementById('select-pegawai');
    const grafikContainer = document.getElementById('grafik-container');

    selectPegawai.addEventListener('change', function () {
        const pegawaiId = selectPegawai.value;

        if (!pegawaiId) {
            grafikContainer.innerHTML = '<p class="text-center text-muted">Pilih pegawai untuk melihat grafik evaluasi.</p>';
            return;
        }

        console.log(`Memuat grafik untuk pegawai dengan ID: ${pegawaiId}`);

        fetch(`/dashboard/atasan/${pegawaiId}`, { method: 'GET' })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                renderGrafik(data.evaluasi);
            } else {
                grafikContainer.innerHTML = '<p class="text-center text-danger">Data tidak tersedia.</p>';
            }
        })
        .catch(err => {
            grafikContainer.innerHTML = '<p class="text-center text-danger">Terjadi kesalahan saat memuat data.</p>';
            console.error(err);
        });
    
    });

    function renderGrafik(evaluasi) {
        const categories = ['Placeholder', 'Di Bawah Ekspektasi', 'Sesuai Ekspektasi', 'Di Atas Ekspektasi'];
        const normalizedHasilKerja = evaluasi.hasilKerja.map(item => ({
            value: categories.indexOf(item) !== -1 ? categories.indexOf(item) : null
        }));
        const normalizedPerilakuKerja = evaluasi.perilakuKerja.map(item => ({
            value: categories.indexOf(item) !== -1 ? categories.indexOf(item) : null
        }));

        grafikContainer.innerHTML = '<div id="grafik" style="min-height: 350px;"></div>';
        const chart = window.echarts.init(document.getElementById('grafik'));

        const options = {
            tooltip: {
                trigger: 'axis',
                formatter: params => params.map(item => `
                    <div>
                        <span style="color:${item.color}">●</span>
                        ${item.seriesName}: ${
                            item.data.value !== null && categories[item.data.value] !== 'Placeholder'
                                ? categories[item.data.value]
                                : 'Tidak Ada Data'
                        }
                    </div>
                `).join('')
            },
            legend: { data: ['Hasil Kerja', 'Perilaku Kerja'], top: 10 },
            grid: { left: '5%', right: '10%', bottom: '5%', top: '15%', containLabel: true },
            xAxis: {
                type: 'category',
                data: evaluasi.months,
                boundaryGap: true,
                axisLabel: { formatter: value => value.substring(0, 3), fontSize: 12 }
            },
            yAxis: {
                type: 'category',
                data: categories,
                axisLabel: { fontSize: 12, formatter: value => (value === 'Placeholder' ? '' : value) },
                boundaryGap: false
            },
            series: [
                {
                    name: 'Hasil Kerja',
                    type: 'bar',
                    data: normalizedHasilKerja,
                    barWidth: '35%'
                },
                {
                    name: 'Perilaku Kerja',
                    type: 'bar',
                    data: normalizedPerilakuKerja,
                    barWidth: '35%'
                }
            ]
        };

        chart.setOption(options);
        window.addEventListener('resize', () => chart.resize());
    }
});


