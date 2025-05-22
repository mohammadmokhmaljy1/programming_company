const ctx = document.getElementById('monthlyChart').getContext('2d');

const data = {
    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
    datasets: [
        {
            label: 'Monthly Incomes ($)',
            data: [1200, 1350, 1600, 1500, 1700, 1800, 1750, 1900, 2100, 2000, 2200, 2300],

            backgroundColor: 'silver',
            borderColor: 'gray',
            borderWidth: 1,

        }
    ]
};

const options = {
    responsive: true,
    plugins: {
        legend: {
            labels: {
                color: 'black'
            }
        }
    },
    scales: {
        x: {
            ticks: { color: 'black' },
            grid: { color: '#eee' }
        },
        y: {
            ticks: { color: 'black' },
            grid: { color: '#eee' }
        }
    }
};

new Chart(ctx, {
    type: 'bar',
    data: data,
    options: options
});