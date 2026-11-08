document.addEventListener("DOMContentLoaded", () => {

    const risk = document.getElementById("riskText");

    if (!risk) return; // important so other pages don't break

    const text = risk.textContent.trim().toLowerCase();

    risk.classList.remove("low","medium","high");

    if (text.includes("low")) {
        risk.classList.add("low");
    } else if (text.includes("medium")) {
        risk.classList.add("medium");
    } else if (text.includes("high")) {
        risk.classList.add("high");
    }

    function highlightRisk() {
        risk.classList.remove("highlight");
        void risk.offsetWidth;
        risk.classList.add("highlight");
    }

    const identifyBtn = document.querySelector('a[href="#vulstat"]');

    if (identifyBtn) {
        identifyBtn.addEventListener("click", () => {
            setTimeout(highlightRisk, 500);
        });
    }

});


document.addEventListener('DOMContentLoaded', () => {
    if (!window.VulneraData) {
        console.error("VulneraData bridge missing!");
        return;
    }

    const { totalUsers, rawRisk } = window.VulneraData;
    const { households, vulnerabilities } = rawRisk;

    let highRiskCount = 0;
    households.forEach(hh => {
        let totalScore = 0;

        totalScore += (parseInt(hh.elderly_count || 0) * 3);
        totalScore += (parseInt(hh.child_count || 0) * 2);
        totalScore += (parseInt(hh.pwd_count || 0) * 3);

        const relevantVuls = vulnerabilities.filter(v => 
            v.Household_ID == hh.HouseholdID || 
            (v.Area_ID == hh.Area_ID && v.Household_ID == null)
        );

        relevantVuls.forEach(v => {
            totalScore += parseInt(v.Vulnerability_Points || 0);
        });

        if (totalScore >= 20) {
            highRiskCount++;
        }
    });

    const userDisplay = document.getElementById('total-users-display');
    if (userDisplay) userDisplay.innerText = totalUsers;

    const riskDisplay = document.getElementById('grand-total-risk');
    if (riskDisplay) riskDisplay.innerText = highRiskCount;
});