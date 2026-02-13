<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            {{ self::$heading }}
        </x-slot>

        <div wire:ignore id="country-map-{{ $this->getId() }}" class="w-full h-[450px]"></div>
    </x-filament::section>

    @script
    <script>
        (function() {
            async function init() {
                google.charts.load('current', {
                    'packages': ['geochart']
                });

                google.charts.setOnLoadCallback(() => {
                    const mapContainer = document.getElementById('country-map-{{ $this->getId() }}');
                    if (!mapContainer) {
                        console.error('Map container not found');
                        return;
                    }

                    // Prepare chart data
                    const chartData = [
                        ['País', 'Cliques']
                    ];

                    const countryData = @json($countryData ?? []);

                    if (countryData && countryData.length > 0) {
                        countryData.forEach(item => {
                            chartData.push([item.country, parseInt(item.clicks) || 0]);
                        });
                    }

                    const chart = new google.visualization.GeoChart(mapContainer);
                    chart.draw(google.visualization.arrayToDataTable(chartData), {
                        colorAxis: {
                            colors: ['#99f393', '#57b63b', '#43830e']
                        },
                        backgroundColor: 'transparent',
                        datalessRegionColor: '#f5f5f5'
                    });
                });
            }

            init();
        })();
    </script>
    @endscript
</x-filament-widgets::widget>
