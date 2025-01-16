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
        console.log("Data Evaluasi:", evaluasi); // Debug data evaluasi
        grafikContainer.innerHTML = '<div class="echart-basic-bar-chart-example" style="height: 400px;"></div>';
        console.log("Grafik container terisi:", document.getElementById("grafik"));

        const categories = ['Tidak Ada Data', 'Di Bawah Ekspektasi', 'Sesuai Ekspektasi', 'Di Atas Ekspektasi'];
        const months = [
            'January', 'February', 'March', 'April', 'May',
            'June', 'July', 'August', 'September', 'October',
            'November', 'December'
        ];

        const normalizeData = (data) =>
            months.map((month, index) => ({
                month,
                value: data[index] ? categories.indexOf(data[index]) : null,
            }));

        const normalizedHasilKerja = normalizeData(evaluasi.hasilKerja);
        const normalizedPerilakuKerja = normalizeData(evaluasi.perilakuKerja);

        const chart = echarts.init(document.querySelector('.echart-basic-bar-chart-example'));

        const options = {
            title: {
                text: 'Evaluasi Pegawai',
                left: 'center'
            },
            tooltip: {
                trigger: 'axis',
                formatter: function (params) {
                    return params.map(item => {
                        const value = categories[item.value] || 'Tidak Ada Data';
                        return `${item.seriesName}: ${value}`;
                    }).join('<br>');
                }
            },
            legend: {
                bottom: 10,
                data: ['Hasil Kerja', 'Perilaku Kerja']
            },
            grid: {
                top: '20%',
                bottom: '20%'
            },
            xAxis: {
                type: 'category',
                data: months
            },
            yAxis: {
                type: 'value',
                min: 0,
                max: 3,
                interval: 1,
                axisLabel: {
                    formatter: function (value) {
                        return categories[value] || '';
                    }
                }
            },
            series: [
                {
                    name: 'Hasil Kerja',
                    type: 'bar',
                    data: normalizedHasilKerja.map(item => item.value)
                },
                {
                    name: 'Perilaku Kerja',
                    type: 'bar',
                    data: normalizedPerilakuKerja.map(item => item.value)
                }
            ]
        };

        chart.setOption(options);
    }
});
