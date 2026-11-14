<?php
require_once __DIR__ . '/../bootstrap.php';

appStartSession();
appRequireAuthenticatedUser();

include '../processes/statsctrl.php';

/* ==========================================
   1. NEWSDATA.IO CONFIGURATION
========================================== */

$baseUrl = "https://newsdata.io/api/1/latest";
$apiKey = appEnvironment('NEWSDATA_API_KEY', 'pub_2135fe9265584955927654269ec21ae2');

$params = [
    'apikey'   => $apiKey,
    'country'  => 'ph',
    'language' => 'en',
    'q'        => 'flood OR flooding OR typhoon OR "storm surge" OR "flash flood"',
    'size'     => 10
];

$apiUrl = $baseUrl . '?' . http_build_query($params);

$response = '';
$curlError = '';
$httpCode = 0;
$data = null;
$hazardArticles = [];

if (empty($apiKey)) {
    $curlError = 'NewsData API key is not configured. Please add NEWSDATA_API_KEY to your .env file.';
} else {

    /* ==========================================
       2. FETCH NEWS FROM API
    ========================================== */

    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $apiUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_USERAGENT => 'Mozilla/5.0',
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => 30
    ]);

    $response = curl_exec($ch);
    $curlError = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    $data = json_decode($response ?: '', true);
}

/* ==========================================
   3. SAFE OUTPUT FUNCTION
========================================== */

if (!function_exists('safeOutput')) {
    function safeOutput($value) {

        if (is_array($value) || is_object($value)) {
            $value = json_encode($value, JSON_UNESCAPED_SLASHES);
        }

        return htmlspecialchars(
            (string)($value ?? ''),
            ENT_QUOTES,
            'UTF-8'
        );
    }
}

/* ==========================================
   4. FLOOD-RELATED KEYWORD DETECTION
========================================== */

$reasonKeywords = [

    'flash flood' =>
        'The article discusses flash flooding, which can cause sudden water accumulation and dangerous conditions.',

    'flood warning' =>
        'The article mentions a flood warning, indicating a possible flooding threat to affected communities.',

    'storm surge' =>
        'The article discusses storm surges, which can cause coastal flooding.',

    'floodwater' =>
        'The article mentions floodwaters, indicating the presence or spread of water in affected areas.',

    'waterlogging' =>
        'The article mentions waterlogging, which can cause water accumulation in affected areas.',

    'inundation' =>
        'The article discusses inundation, which refers to land being covered by water.',

    'tropical cyclone' =>
        'The article discusses a tropical cyclone that may bring heavy rainfall and increase the risk of flooding.',

    'tropical storm' =>
        'The article discusses a tropical storm that may bring heavy rainfall and increase the risk of flooding.',

    'typhoon' =>
        'The article discusses a typhoon that may bring heavy rainfall and increase the risk of flooding.',

    'flood' =>
        'The article discusses flooding and its possible effects on people, properties, or communities.'
];

/* ==========================================
   5. FILTER FLOOD-RELATED ARTICLES
========================================== */

if (
    is_array($data) &&
    ($data['status'] ?? '') === 'success' &&
    !empty($data['results']) &&
    is_array($data['results'])
) {

    foreach ($data['results'] as $article) {

        $title = $article['title'] ?? 'Untitled News';
        $description = $article['description'] ?? '';
        $content = $article['content'] ?? '';

        $articleText = implode(' ', array_filter(
            [$title, $description, $content],
            'is_string'
        ));

        $whyRelated = '';

        foreach ($reasonKeywords as $keyword => $reason) {

            if (stripos($articleText, $keyword) !== false) {
                $whyRelated = $reason;
                break;
            }
        }

        if (!empty($whyRelated)) {

            $hazardArticles[] = [
                'title' => $title,
                'why' => $whyRelated,
                'link' => $article['link'] ?? ''
            ];
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Vulneratrack</title>

    <link rel="icon" href="../style/imgs/VULNETITLE.png" type="image/png">

    <!-- EXISTING STYLESHEETS -->
    <link rel="stylesheet" href="../style/body.css">
    <link rel="stylesheet" href="../style/scrollbar.css">
    <link rel="stylesheet" href="../style/userstyle/home.css">
    <link rel="stylesheet" href="../style/nav.css">
    <link rel="stylesheet" href="../style/userstyle/mobile.css">

    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>

        /* ==========================================
           TOKENS  (colors taken from the Main Features section)
        ========================================== */

        .information-page {
            --side:       6.5vw;      /* left/right page margin: change this to adjust */
            --ink-dark:   #2b3380;
            --ink-brand:  #3a3fb0;
            --ink-body:   #6b7280;
            --accent:     #5046e4;
            --accent-dk:  #4038c9;
            --tint:       #eef0ff;
            --line:       #e8e9fb;
            --surface:    #ffffff;
            --surface-2:  #f7f8fe;
            --nav-h:      80px;       /* height of your navbar: adjust if the hero looks too tall/short */
        }

        .information-page,
        .information-page * {
            box-sizing: border-box;
        }

        .information-page {
            width: 100%;
            max-width: none;
            margin: 0 auto;
            padding: 0 var(--side) 70px;
        }

        /* gradient bar, same one that frames "Main Features" */
        .info-divider {
            height: 18px;
            margin: 56px 0;
            background: linear-gradient(90deg,
                rgba(158, 160, 214, 0) 0%,
                rgba(158, 160, 214, 0.95) 50%,
                rgba(158, 160, 214, 0) 100%);
        }

        .info-card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 20px;
            box-shadow: 0 2px 14px rgba(80, 70, 228, 0.06);
        }

        /* shared button, same as "Report here" / "Locate" */
        .info-btn {
            display: inline-block;
            padding: 12px 28px;

            background: var(--accent);
            color: #ffffff;

            border: none;
            border-radius: 8px;

            font-size: 15px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }

        .info-btn:hover {
            background: var(--accent-dk);
        }

        /* ==========================================
           PAGE HEADER
        ========================================== */

        .information-intro {
            text-align: center;
            margin-bottom: 44px;
        }

        .information-intro h1 {
            color: #ffffff;
            font-size: 48px;
            font-weight: 700;
            margin: 0 0 14px;
        }

        .information-intro p {
            max-width: 720px;
            margin: 0 auto;

            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
            line-height: 1.7;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

        /* ==========================================
           TOP TWO CARDS
        ========================================== */

        .quick-links {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 4.2%;
        }

        /* first screen: fills the whole laptop screen so the chat band stays below the fold */
        .hub-hero {
            min-height: calc(100vh - var(--nav-h));
            min-height: calc(100svh - var(--nav-h));

            display: flex;
            flex-direction: column;
            justify-content: center;

            /* break out of the page side padding so the band is full width */
            margin: 0 calc(-1 * var(--side));
            padding: 40px var(--side);

            /* blue overlay on top of the picture */
            background:
                linear-gradient(rgba(43, 51, 128, 0.78), rgba(43, 51, 128, 0.78)),
                url('../style/imgs/emergency.png') center / cover no-repeat;
        }

        .quick-card {
            display: block;
            text-decoration: none;
            padding: 64px 40px 60px;
            text-align: center;
        }

        .quick-card i {
            display: block;
            color: var(--accent);
            font-size: 40px;
            margin-bottom: 20px;
        }

        .quick-card h2 {
            color: var(--ink-brand);
            font-size: 28px;
            font-weight: 700;
            line-height: 1.3;
            margin: 0 0 14px;
        }

        .quick-card p {
            color: var(--ink-body);
            font-size: 17px;
            line-height: 1.7;
            margin: 0 0 32px;
        }

        .quick-card .info-btn {
            padding: 14px 36px;
            font-size: 16px;
        }

        /* ==========================================
           CHATBOT CARD
        ========================================== */

        /* plain white wrapper around the chat card */
        .chat-band {
            scroll-margin-top: 90px;
            padding: 70px 0 20px;
        }

        .chat-container {
            padding: 32px 34px 28px;
            border: 2px solid var(--accent);
            box-shadow: 0 4px 22px rgba(80, 70, 228, 0.14);
        }

        .chat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 22px;
        }

        .chat-header-left {
            display: flex;
            align-items: center;
            gap: 16px;
            min-width: 0;
        }

        .chat-avatar {
            width: 48px;
            height: 48px;
            flex-shrink: 0;

            display: flex;
            align-items: center;
            justify-content: center;

            background: var(--tint);
            color: var(--accent);
            border-radius: 12px;
            font-size: 20px;
        }

        .chat-header h2 {
            color: var(--ink-brand);
            font-size: 21px;
            font-weight: 700;
            margin: 0 0 3px;
        }

        .chat-header p {
            color: var(--ink-body);
            font-size: 14px;
            margin: 0;
        }

        .chat-status {
            flex-shrink: 0;
            padding: 6px 16px;
            border-radius: 30px;

            background: var(--tint);
            color: var(--accent);
            font-size: 13px;
            font-weight: 700;
        }

        .chat-content {
            display: flex;
            flex-direction: column;
        }

        .chat-box {
            max-height: 380px;
            min-height: 90px;
            overflow-y: auto;

            padding: 14px;

            background: var(--surface-2);
            border: 1px solid var(--line);
            border-radius: 14px;

            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .chat-box::-webkit-scrollbar {
            width: 7px;
        }

        .chat-box::-webkit-scrollbar-thumb {
            background: #c9cbe8;
            border-radius: 10px;
        }

        .chat-box::-webkit-scrollbar-track {
            background: transparent;
        }

        .chat-box .message {
            padding: 13px 18px;
            border-radius: 12px;
            max-width: 80%;

            font-size: 14px;
            line-height: 1.6;
            overflow-wrap: anywhere;
        }

        .chat-box .user-msg {
            background: var(--accent);
            color: #ffffff;

            align-self: flex-end;
            margin-left: auto;
            border-bottom-right-radius: 4px;
        }

        .chat-box .bot-msg {
            background: var(--surface);
            color: #4a4f6a;
            border: 1px solid var(--line);

            align-self: flex-start;
            border-bottom-left-radius: 4px;
        }

        .chat-box .welcome-msg {
            max-width: 100%;
            width: 100%;
            padding: 18px 22px;
            background: var(--tint);
            border-color: transparent;
        }

        .welcome-msg strong {
            display: block;
            color: var(--ink-dark);
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .welcome-msg span {
            display: block;
            color: var(--ink-body);
            font-size: 14px;
        }

        .suggested-label {
            margin: 24px 0 4px;
            padding-left: 4px;

            color: var(--ink-body);
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .suggested-list {
            display: flex;
            flex-direction: column;
        }

        .suggested-question {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;

            width: 100%;
            padding: 15px 6px;

            background: transparent;
            border: none;
            border-bottom: 1px solid var(--line);

            color: var(--ink-brand);
            font-family: inherit;
            font-size: 14px;
            text-align: left;
            cursor: pointer;
        }

        .suggested-question i {
            color: var(--accent);
            font-size: 11px;
        }

        .suggested-question:hover {
            color: var(--accent);
        }

        .chat-page-input {
            display: flex;
            align-items: center;
            gap: 12px;

            margin-top: 14px;
            padding: 6px 6px 6px 16px;

            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 12px;
        }

        .chat-page-input:focus-within {
            border-color: var(--accent);
        }

        .chat-page-input input {
            flex: 1;
            min-width: 0;
            padding: 12px 4px;

            background: transparent;
            border: none;
            outline: none;

            color: #34384f;
            font-size: 14px;
        }

        .chat-page-input input::placeholder {
            color: #9a9db3;
        }

        .chat-page-input button {
            width: 44px;
            height: 44px;
            flex-shrink: 0;

            display: flex;
            align-items: center;
            justify-content: center;

            background: var(--accent);
            color: #ffffff;

            border: none;
            border-radius: 8px;
            font-size: 15px;
            cursor: pointer;
        }

        .chat-page-input button:hover {
            background: var(--accent-dk);
        }

        .chat-page-input button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* ==========================================
           NEWS SECTION  (full-width blue picture band)
        ========================================== */

        .news-section {
            scroll-margin-top: 90px;
            padding-top: 60px;
        }

        .news-header {
            margin-bottom: 22px;
        }

        .news-header h2 {
            color: var(--ink-dark);
            font-size: 26px;
            font-weight: 700;
            margin: 0;
        }

        .news-grid {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .news-card {
            padding: 26px 30px;
        }

        .news-card h3 {
            color: var(--ink-brand);
            font-size: 18px;
            font-weight: 700;
            line-height: 1.45;
            margin: 0 0 12px;
            overflow-wrap: anywhere;
        }

        .news-why-pill {
            display: inline-block;

            padding: 5px 13px;
            margin-bottom: 10px;

            background: var(--tint);
            color: var(--accent);
            border-radius: 30px;

            font-size: 13px;
            font-weight: 700;
        }

        .news-card .news-explanation {
            color: var(--ink-body);
            font-size: 14px;
            line-height: 1.7;
            margin: 0;
        }

        .news-read-more {
            margin-top: 18px;
            padding: 10px 22px;
            font-size: 14px;
        }

        /* ==========================================
           NEWS PAGINATION
        ========================================== */

        .news-pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 28px;
        }

        .news-pagination button {
            min-width: 42px;
            height: 42px;
            padding: 0 14px;

            background: var(--surface);
            color: var(--ink-brand);
            border: 1px solid var(--line);
            border-radius: 8px;

            font-family: inherit;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
        }

        .news-pagination button:hover:not(:disabled) {
            border-color: var(--accent);
            color: var(--accent);
        }

        .news-pagination button.active {
            background: var(--accent);
            border-color: var(--accent);
            color: #ffffff;
        }

        .news-pagination button:disabled {
            opacity: 0.45;
            cursor: not-allowed;
        }

        /* ==========================================
           EMPTY AND ERROR STATES
        ========================================== */

        .news-empty,
        .news-error {
            padding: 40px 25px;
            text-align: center;
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 20px;
        }

        .news-empty i {
            color: var(--accent);
            font-size: 34px;
            margin-bottom: 14px;
        }

        .news-empty h3,
        .news-error h3 {
            color: var(--ink-brand);
            font-size: 19px;
            margin: 0 0 9px;
        }

        .news-empty p,
        .news-error p {
            color: var(--ink-body);
            font-size: 14px;
            line-height: 1.7;
            margin: 0;
        }

        .news-error {
            border-color: #f1d4d4;
            overflow-wrap: anywhere;
        }

        .news-error i {
            color: #d9534f;
            font-size: 30px;
            margin-bottom: 12px;
        }

        /* ==========================================
           RESPONSIVE DESIGN
        ========================================== */

        @media (max-width: 900px) {

            .information-page {
                --side: 4vw;
            }
        }

        @media (max-width: 768px) {

            .information-page {
                --side: 16px;
            }

            .info-divider {
                margin: 36px 0;
            }

            .information-intro h1 {
                font-size: 30px;
            }

            .information-intro p {
                font-size: 12px;
            }

            .quick-links {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .chat-container {
                padding: 22px 18px;
            }

            .chat-header h2 {
                font-size: 18px;
            }

            .chat-box .message {
                max-width: 92%;
                font-size: 13px;
            }

            .chat-band {
                padding: 40px 0 10px;
            }

            .hub-hero {
                padding: 30px var(--side);
            }

            .quick-card {
                padding: 40px 24px 36px;
            }

            .news-section {
                padding-top: 40px;
            }

            .news-header h2 {
                font-size: 21px;
            }

            .news-card {
                padding: 20px;
            }

            .news-card h3 {
                font-size: 17px;
            }
        }

        @media (max-width: 480px) {

            .chat-header {
                flex-wrap: wrap;
            }

            .chat-avatar {
                width: 42px;
                height: 42px;
                font-size: 17px;
            }

            .chat-header p {
                font-size: 12px;
            }
        }

    </style>

</head>

<body>

    <!-- ==========================================
         EXISTING NAVBAR
    ========================================== -->

    <?php
        $path_to_root = "../";
        include '../nav/homenav.php';
    ?>


    <main class="information-page">

        <div class="hub-hero">

        <!-- ==========================================
             PAGE HEADER
        ========================================== -->

        <div class="information-intro">
            <h1>Information Hub</h1>
            <p>Your source for flood awareness, disaster information, and the latest hazard updates in the Philippines.</p>
        </div>


        <!-- ==========================================
             QUICK LINKS
        ========================================== -->

        <div class="quick-links">

            <a href="#chatSection" class="quick-card info-card">
                <i class="fas fa-robot"></i>
                <h2>Chat with VulFAQ!</h2>
                <p>Ask questions about how Vulneratrack works, from flood reporting to real-time monitoring and get answers about floods.</p>
                <span class="info-btn">Ask now</span>
            </a>

            <a href="#newsSection" class="quick-card info-card">
                <i class="fas fa-newspaper"></i>
                <h2>Latest News</h2>
                <p>Stay updated on floods and typhoons.</p>
                <span class="info-btn">Read news</span>
            </a>

        </div>

        </div><!-- /.hub-hero -->


        <!-- ==========================================
             CHATBOT SECTION
        ========================================== -->

        <section class="chat-band" id="chatSection">

        <div class="chat-container info-card">

            <div class="chat-header">

                <div class="chat-header-left">

                    <div class="chat-avatar">
                        <i class="fas fa-robot"></i>
                    </div>

                    <div>
                        <h2>VulFAQ</h2>
                        <p>Your flood-related FAQ chatbot</p>
                    </div>

                </div>

                <span class="chat-status">Online</span>

            </div>

            <div class="chat-content">

                <div class="chat-box" id="chatBox">

                    <div class="message bot-msg welcome-msg">
                        <strong>Hello! How can I help you?</strong>
                        <span>You can ask me about flood safety, preparedness, evacuation, and other disaster-related concerns.</span>
                    </div>

                </div>

                <div class="suggested-label">Suggested questions</div>

                <div class="suggested-list">

                    <button type="button" class="suggested-question">
                        What is Vulneratrack and how does it work?
                        <i class="fas fa-arrow-up-right-from-square"></i>
                    </button>

                    <button type="button" class="suggested-question">
                        What should I include in an emergency kit?
                        <i class="fas fa-arrow-up-right-from-square"></i>
                    </button>

                    <button type="button" class="suggested-question">
                        When is it safe to evacuate?
                        <i class="fas fa-arrow-up-right-from-square"></i>
                    </button>

                </div>

                <form class="chat-page-input" id="chatForm">

                    <input
                        type="text"
                        id="userInput"
                        placeholder="Ask your flood-related question..."
                        autocomplete="off"
                        required
                    >

                    <button type="submit" id="sendButton" aria-label="Send message">
                        <i class="fas fa-paper-plane"></i>
                    </button>

                </form>

            </div>

        </div>

        </section>


        <!-- ==========================================
             LATEST FLOOD AND TYPHOON NEWS
        ========================================== -->

        <section class="news-section" id="newsSection">

            <div class="news-header">

                <h2>Latest Flood &amp; Typhoon News</h2>

            </div>


            <div class="news-grid">


                <?php if ($curlError): ?>

                    <!-- CONNECTION / CONFIGURATION ERROR -->

                    <div class="news-error">

                        <i class="fas fa-exclamation-circle"></i>

                        <h3>Unable to Connect to News Service</h3>

                        <p>
                            <?= safeOutput($curlError) ?>
                        </p>

                    </div>


                <?php elseif ($httpCode !== 200): ?>

                    <!-- API ERROR -->

                    <div class="news-error">

                        <i class="fas fa-exclamation-triangle"></i>

                        <h3>Unable to Retrieve News</h3>

                        <p>
                            API Error. HTTP Status:
                            <strong><?= safeOutput($httpCode) ?></strong>
                        </p>

                        <?php

                        $errorDetails = 'No API error details available.';

                        if (is_array($data)) {

                            $errorDetails =
                                $data['message']
                                ?? $data['results']
                                ?? $data['error']
                                ?? $errorDetails;

                        } elseif (!empty($response)) {

                            $errorDetails = $response;

                        }

                        ?>

                        <p style="margin-top: 8px;">
                            <?= safeOutput($errorDetails) ?>
                        </p>

                        <?php if ($httpCode == 401): ?>

                            <p style="margin-top: 8px;">
                                Please verify that your NewsData.io API key is active.
                            </p>

                        <?php elseif ($httpCode == 422): ?>

                            <p style="margin-top: 8px;">
                                Your API request contains invalid parameters.
                            </p>

                        <?php endif; ?>

                    </div>


                <?php elseif (!empty($hazardArticles)): ?>

                    <!-- DISPLAY NEWS ARTICLES -->

                    <?php foreach ($hazardArticles as $article): ?>

                        <article class="news-card info-card">

                            <h3>
                                <?= safeOutput($article['title']) ?>
                            </h3>

                            <span class="news-why-pill">
                                <i class="fas fa-info-circle"></i>
                                Why is this flood-related?
                            </span>

                            <p class="news-explanation">
                                <?= safeOutput($article['why']) ?>
                            </p>

                            <?php
                            $articleLink = $article['link'];

                            $validArticleLink =
                                is_string($articleLink) &&
                                filter_var($articleLink, FILTER_VALIDATE_URL) &&
                                in_array(
                                    strtolower(parse_url($articleLink, PHP_URL_SCHEME) ?? ''),
                                    ['http', 'https'],
                                    true
                                );
                            ?>

                            <?php if ($validArticleLink): ?>

                                <a
                                    class="news-read-more info-btn"
                                    href="<?= safeOutput($articleLink) ?>"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    Read full article
                                    <i class="fas fa-arrow-right"></i>
                                </a>

                            <?php endif; ?>

                        </article>

                    <?php endforeach; ?>


                <?php elseif (($data['status'] ?? '') === 'success'): ?>

                    <!-- NO NEWS AVAILABLE -->

                    <div class="news-empty">

                        <i class="fas fa-newspaper"></i>

                        <h3>No Flood or Typhoon News Available</h3>

                        <p>
                            No matching flood-related articles were found.
                            Please check again later.
                        </p>

                    </div>


                <?php else: ?>

                    <!-- UNKNOWN ERROR -->

                    <div class="news-error">

                        <i class="fas fa-exclamation-circle"></i>

                        <h3>Something Went Wrong</h3>

                        <p>
                            <?= safeOutput(
                                $data['message']
                                ?? 'Unable to retrieve news. Please try again later.'
                            ) ?>
                        </p>

                    </div>

                <?php endif; ?>


            </div>

            <div class="news-pagination" id="newsPagination"></div>

        </section>

    </main>


    <!-- ==========================================
         ORIGINAL CHATBOT JAVASCRIPT
         LEFT UNCHANGED
    ========================================== -->

    <script>
        const chatBox = document.getElementById('chatBox');
        const chatForm = document.getElementById('chatForm');
        const inputField = document.getElementById('userInput');
        const sendButton = document.getElementById('sendButton');

        function appendMessage(text, className) {
            const message = document.createElement('div');
            message.className = `message ${className}`;
            message.textContent = text;
            chatBox.appendChild(message);
            chatBox.scrollTop = chatBox.scrollHeight;
            return message;
        }

        async function sendMessage(event) {
            event.preventDefault();
            const messageText = inputField.value.trim();
            if (!messageText) return;

            appendMessage(messageText, 'user-msg');
            inputField.value = '';
            sendButton.disabled = true;
            const typingMessage = appendMessage('Typing...', 'bot-msg');

            try {
                const response = await fetch('../processes/bot.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ message: messageText })
                });
                const data = await response.json();
                typingMessage.textContent = response.ok && typeof data.reply === 'string'
                    ? data.reply
                    : (data.error || 'The chatbot could not answer right now. Please try again later.');
            } catch (error) {
                typingMessage.textContent = 'Unable to reach the chatbot service. Please try again later.';
            } finally {
                sendButton.disabled = false;
                inputField.focus();
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        }

        chatForm.addEventListener('submit', sendMessage);
    </script>

    <!-- OPTIONAL ADD-ON (separate from the original script above):
         lets the "Suggested questions" buttons send their text.
         Delete this block if you don't want the buttons clickable. -->
    <script>
        document.querySelectorAll('.suggested-question').forEach(function (btn) {
            btn.addEventListener('click', function () {
                document.getElementById('userInput').value = btn.textContent.trim();
                document.getElementById('chatForm').requestSubmit();
            });
        });
    </script>


    <!-- ==========================================
         NEWS PAGINATION (2 articles per page)
    ========================================== -->

    <script>
        (function () {
            const perPage = 2;
            const cards = Array.from(document.querySelectorAll('.news-grid .news-card'));
            const nav = document.getElementById('newsPagination');
            const totalPages = Math.ceil(cards.length / perPage);

            if (!nav || totalPages <= 1) return;

            let current = 1;

            function makeBtn(label, onClick, opts) {
                const b = document.createElement('button');
                b.type = 'button';
                b.innerHTML = label;
                if (opts && opts.active) b.className = 'active';
                if (opts && opts.disabled) b.disabled = true;
                if (opts && opts.aria) b.setAttribute('aria-label', opts.aria);
                b.addEventListener('click', onClick);
                return b;
            }

            function render(scrollToTop) {
                cards.forEach(function (card, i) {
                    const page = Math.floor(i / perPage) + 1;
                    card.style.display = page === current ? '' : 'none';
                });

                nav.innerHTML = '';

                nav.appendChild(makeBtn('<i class="fas fa-chevron-left"></i>', function () {
                    current--; render(true);
                }, { disabled: current === 1, aria: 'Previous page' }));

                for (let p = 1; p <= totalPages; p++) {
                    nav.appendChild(makeBtn(String(p), (function (page) {
                        return function () { current = page; render(true); };
                    })(p), { active: p === current, aria: 'Page ' + p }));
                }

                nav.appendChild(makeBtn('<i class="fas fa-chevron-right"></i>', function () {
                    current++; render(true);
                }, { disabled: current === totalPages, aria: 'Next page' }));

                if (scrollToTop) {
                    document.getElementById('newsSection').scrollIntoView({ behavior: 'smooth' });
                }
            }

            render(false);
        })();
    </script>


    <!-- ==========================================
         EXISTING FOOTER
    ========================================== -->

    <?php
        $path_to_root = "../";
        include '../nav/footer.php';
    ?>

</body>
</html>