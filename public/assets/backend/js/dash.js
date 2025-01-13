document.addEventListener('DOMContentLoaded', function () {
    const hasilKerjaData = JSON.parse(document.getElementById('hasilKerjaData').value);
    const perilakuKerjaData = JSON.parse(document.getElementById('perilakuKerjaData').value);

    const categories = ['Di Bawah Ekspektasi', 'Sesuai Ekspektasi', 'Di Atas Ekspektasi'];
    const months = [
        'January', 'February', 'March', 'April', 'May',
        'June', 'July', 'August', 'September', 'October',
        'November', 'December'
    ];

    // Pastikan data sesuai panjang bulan dan tambahkan null jika "Tidak Ada Data"
    const normalizeData = (data) =>
        months.map((month, index) => {
            if (data[index] === "Tidak Ada Data") return { month, value: null }; // Tidak ada data
            return { month, value: categories.indexOf(data[index]) };
        });

    const normalizedHasilKerja = normalizeData(hasilKerjaData);
    const normalizedPerilakuKerja = normalizeData(perilakuKerjaData);

    const $chartEl = document.querySelector('.echart-basic-bar-chart-example');

    if ($chartEl) {
        const chart = window.echarts.init($chartEl);

        const options = {
            tooltip: {
                trigger: 'axis',
                formatter: params => {
                    return params.map(item => `
                        <div>
                            <span style="color:${item.color}">●</span>
                            ${item.seriesName}: ${
                                item.data.value !== null
                                    ? categories[item.data.value]
                                    : 'Tidak Ada Data'
                            }
                        </div>
                    `).join('');
                },
                axisPointer: { type: 'shadow' }
            },
            legend: {
                data: ['Hasil Kerja', 'Perilaku Kerja'],
                top: 10
            },
            grid: {
                left: '15%',
                right: '15%',
                bottom: '20%',
                top: '20%'
            },
            xAxis: {
                type: 'category',
                data: months, // Selalu gunakan semua bulan
                axisLabel: { formatter: value => value.substring(0, 3) }
            },
            yAxis: {
                type: 'category',
                data: categories,
                axisLabel: { formatter: value => value }
            },
            series: [
                {
                    name: 'Hasil Kerja',
                    type: 'bar',
                    data: normalizedHasilKerja.map(item => ({ value: item.value })),
                    barWidth: '35%'
                },
                {
                    name: 'Perilaku Kerja',
                    type: 'bar',
                    data: normalizedPerilakuKerja.map(item => ({ value: item.value })),
                    barWidth: '35%'
                }
            ]
        };

        chart.setOption(options);
    }
});
