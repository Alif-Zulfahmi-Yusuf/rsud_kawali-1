document.addEventListener('DOMContentLoaded', function () {
    const hasilKerjaData = JSON.parse(document.getElementById('hasilKerjaData').value);
    const perilakuKerjaData = JSON.parse(document.getElementById('perilakuKerjaData').value);

    // Tambahkan placeholder untuk memberi ruang di bawah
    const categories = ['Placeholder', 'Di Bawah Ekspektasi', 'Sesuai Ekspektasi', 'Di Atas Ekspektasi'];
    const months = [
        'January', 'February', 'March', 'April', 'May',
        'June', 'July', 'August', 'September', 'October',
        'November', 'December'
    ];

    const normalizeData = (data) =>
        months.map((month, index) => {
            if (!data[index] || data[index] === "Tidak Ada Data") return { month, value: null };
            // Geser indeks kategori agar sesuai dengan penambahan placeholder
            return { month, value: categories.indexOf(data[index]) };
        });

    const normalizedHasilKerja = normalizeData(hasilKerjaData);
    const normalizedPerilakuKerja = normalizeData(perilakuKerjaData);

    const allDataEmpty = (data) =>
        data.every(item => item.value === null);

    const isHasilKerjaEmpty = allDataEmpty(normalizedHasilKerja);
    const isPerilakuKerjaEmpty = allDataEmpty(normalizedPerilakuKerja);

    const $chartEl = document.querySelector('.echart-basic-bar-chart-example');

    if ($chartEl) {
        if (isHasilKerjaEmpty && isPerilakuKerjaEmpty) {
            $chartEl.innerHTML = '<p style="text-align: center; color: gray;">Data tidak tersedia untuk ditampilkan</p>';
        } else {
            const chart = window.echarts.init($chartEl);

            const options = {
                tooltip: {
                    trigger: 'axis',
                    formatter: params => {
                        return params.map(item => `
                            <div>
                                <span style="color:${item.color}">●</span>
                                ${item.seriesName}: ${
                                    item.data.value !== null && categories[item.data.value] !== 'Placeholder'
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
                    left: '5%',
                    right: '10%',
                    bottom: '5%',
                    top: '15%',
                    containLabel: true
                },
                xAxis: {
                    type: 'category',
                    data: months,
                    boundaryGap: true,
                    axisLabel: {
                        formatter: value => value.substring(0, 3),
                        fontSize: 12
                    }
                },
                yAxis: {
                    type: 'category',
                    data: categories,
                    axisLabel: {
                        fontSize: 12,
                        formatter: value => (value === 'Placeholder' ? '' : value) // Jangan tampilkan placeholder di label
                    },
                    boundaryGap: false
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

            window.addEventListener('resize', () => {
                chart.resize();
            });
        }
    }
});
