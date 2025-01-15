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
        grafikContainer.innerHTML = '<div id="grafik"></div>';

        const chart = new ApexCharts(document.getElementById('grafik'), {
            chart: {
                type: 'bar',
                height: 350
            },
            title: {
                text: 'Evaluasi Pegawai'
            },
            xaxis: {
                categories: evaluasi.months
            },
            series: [
                {
                    name: 'Hasil Kerja',
                    data: evaluasi.hasilKerja
                },
                {
                    name: 'Perilaku Kerja',
                    data: evaluasi.perilakuKerja
                }
            ]
        });

        chart.render();
    }
});
