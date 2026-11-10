<?php
require_once __DIR__ . '/../classes/statsclass.php';

$statsObj = new StatsClass();

$totalPop = $statsObj->getTotalPopulation();
$vulnPop  = $statsObj->getVulnerablePopulation();
$realTime = $statsObj->getRealTimeReports();
$vulnDist      = $statsObj->getVulnerabilityPieData(); 
$popBreakdown  = $statsObj->getPopulationBreakdown();
$areaPop       = $statsObj->getPopulationPerArea(); 
$resovled = $statsObj->getResReports();
$rawData = $statsObj->getRawHouseholdRiskData();
$households = $rawData['households'] ?? [];
$vulnerabilities = $rawData['vulnerabilities'] ?? [];

$highRiskCount = 0;
foreach ($households as $hh) {
    $score = 0;
    $score += ((int)($hh['elderly_count'] ?? 0) * 3);
    $score += ((int)($hh['child_count'] ?? 0) * 2);
    $score += ((int)($hh['pwd_count'] ?? 0) * 3);

    foreach ($vulnerabilities as $v) {
        if ($v['Household_ID'] == $hh['HouseholdID'] || 
           ($v['Area_ID'] == $hh['Area_ID'] && $v['Household_ID'] == null)) {
            $score += (int)($v['Vulnerability_Points'] ?? 0);
        }
    }

    if ($score >= 20) {
        $highRiskCount++;
    }
}
?>




