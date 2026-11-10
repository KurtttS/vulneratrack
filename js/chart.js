document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('risk-stats-container');
    if (!container) return;

    const rawData = JSON.parse(container.getAttribute('data-raw-risk'));
    const { households, vulnerabilities } = rawData;

    const areaTallies = {};

    households.forEach(hh => {
        let totalScore = 0;
        const areaName = hh.Area_Name || "Unknown Area";
        if (!areaTallies[areaName]) areaTallies[areaName] = 0;

        totalScore += (parseInt(hh.elderly_count) * 3);
        totalScore += (parseInt(hh.child_count) * 2);
        totalScore += (parseInt(hh.pwd_count) * 3);

        const relevantVuls = vulnerabilities.filter(v => 
            v.Household_ID == hh.HouseholdID || 
            (v.Area_ID == hh.Area_ID && v.Household_ID == null)
        );

        relevantVuls.forEach(v => {
            totalScore += parseInt(v.Vulnerability_Points || 0);
        });

        if (totalScore >= 20) {
            areaTallies[areaName]++;
        }
    });

    const grandTotal = Object.values(areaTallies).reduce((sum, val) => sum + val, 0);
    const applytotal = document.getElementById('grand-total-risk');
    if (applytotal) {
        applytotal.innerHTML = grandTotal;
    }
    renderRiskUI(container, areaTallies);
});
document.addEventListener('DOMContentLoaded', function () {
    if (!window.chartData) {
        console.error("Chart data not found.");
        return;
    }

    const { vulnDist, popBreakdown, areaPop } = window.chartData;

    // Population per Area
    const ctxArea = document.getElementById('areaBarChart');
    if (ctxArea) {
        new Chart(ctxArea, {
            type: 'bar',
            data: {
                labels: areaPop.map(item => item.label || 'Unknown'),
                datasets: [{
                    label: 'Residents',
                    data: areaPop.map(item => item.value || 0),
                    backgroundColor: [
                        '#3D85F7', // Celine Homes 
                        '#505ac9', // East Homes 
                        '#813df7'  // Purok Carvic 
                    ],
                    borderRadius: 5,
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 1500,
                        easing: 'easeOutQuart'
                    },
                    resizeDelay: 200,
                scales: {
                    x: { 
                        beginAtZero: true,
                        ticks: { stepSize: 1, color: '#666' }
                    },
                    y: { 
                        ticks: { color: '#666' }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }

    // Vulnera Distrib
    const ctxVuln = document.getElementById('vulnPieChart');
    if (ctxVuln) {
        new Chart(ctxVuln, {
            type: 'pie',
            data: {
                labels: vulnDist.map(item => item.label || 'Unknown'),
                datasets: [{
                    data: vulnDist.map(item => item.value || 0),
                    backgroundColor: ['#ADD8E6', 'rgb(202, 158, 233)'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 1500, 
                        easing: 'easeOutQuart'
                    },
                    resizeDelay: 200,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }

    // Group Detail
    const ctxPop = document.getElementById('popBarChart');
    if (ctxPop) {
        new Chart(ctxPop, {
            type: 'pie',
            data: {
                labels: popBreakdown.map(item => item.label || 'Other'),
                datasets: [{
                    data: popBreakdown.map(item => item.value || 0),
                    backgroundColor: ['#3D85F7', '#0031CF', '#000063'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 1500,
                        easing: 'easeOutQuart'
                    },
                    resizeDelay: 200,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }
});


function renderRiskUI(container, tallies) {
    let html = '';
    Object.entries(tallies).forEach(([area, count]) => {
        if (count > 0) {
            html += `
                <div class="risk-item">
                    <span class="count-red">${count}</span>
                    <span class="area-label">${area}</span>
                </div>`;
        }
    });
    container.innerHTML = html || '<p>No high risk households identified.</p>';
}