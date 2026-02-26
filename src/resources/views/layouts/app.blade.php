<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>@yield('title', 'Kopi Brader')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- PWA --}}
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#d4ff00">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Kopi Brader">
    <link rel="apple-touch-icon" href="/images/icon-192.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=Space+Grotesk:wght@400;500;600;700&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg: #0a0a0f; --surface: #111118; --surface2: #1a1a24;
            --border: #2a2a3a; --text: #f0f0f5; --muted: #6b6b7e;
            --accent: #d4ff00; --accent2: #7c6aff; --accent3: #ff6b6b;
            --green: #22c55e;
        }
        * { margin:0; padding:0; box-sizing:border-box; -webkit-tap-highlight-color:transparent; }
        body { font-family:'Space Grotesk',sans-serif; background:var(--bg); color:var(--text); min-height:100vh; }
        img { max-width:100%; }
        button, input, textarea, select { font-family:'Space Grotesk',sans-serif; }

        /* PWA Install Banner */
        .pwa-banner {
            position: fixed; bottom: 80px; left: 16px; right: 16px; z-index: 200;
            background: var(--surface); border: 1px solid var(--accent);
            border-radius: 14px; padding: 14px 16px;
            display: none; align-items: center; gap: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,.5);
            animation: slideUp .3s ease;
        }
        .pwa-banner.show { display: flex; }
        @keyframes slideUp { from{transform:translateY(20px);opacity:0} to{transform:translateY(0);opacity:1} }
        .pwa-icon { font-size: 2rem; flex-shrink: 0; }
        .pwa-text { flex: 1; }
        .pwa-title { font-weight: 700; font-size: 0.85rem; margin-bottom: 2px; }
        .pwa-sub { font-size: 0.7rem; color: var(--muted); }
        .pwa-install { background: var(--accent); color: #000; font-weight: 700; border: none; padding: 8px 14px; border-radius: 8px; cursor: pointer; font-size: 0.78rem; white-space: nowrap; }
        .pwa-close { background: none; border: none; color: var(--muted); cursor: pointer; font-size: 1.2rem; padding: 4px; }
    </style>

    @stack('styles')
</head>
<body>

@yield('content')

{{-- PWA Install Banner --}}
<div class="pwa-banner" id="pwaBanner">
    <div class="pwa-icon">☕</div>
    <div class="pwa-text">
        <div class="pwa-title">Install Kopi Brader App</div>
        <div class="pwa-sub">Akses menu lebih cepat dari HP lo!</div>
    </div>
    <button class="pwa-install" id="pwaInstallBtn">Install</button>
    <button class="pwa-close" onclick="closePWA()">✕</button>
</div>

<script>
// Register Service Worker
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js').catch(() => {});
    });
}

// PWA Install Prompt
let deferredPrompt = null;
window.addEventListener('beforeinstallprompt', e => {
    e.preventDefault();
    deferredPrompt = e;
    const dismissed = localStorage.getItem('pwa_dismissed');
    if (!dismissed) {
        setTimeout(() => document.getElementById('pwaBanner').classList.add('show'), 3000);
    }
});

document.getElementById('pwaInstallBtn').addEventListener('click', async () => {
    if (!deferredPrompt) return;
    deferredPrompt.prompt();
    const { outcome } = await deferredPrompt.userChoice;
    deferredPrompt = null;
    document.getElementById('pwaBanner').classList.remove('show');
});

function closePWA() {
    document.getElementById('pwaBanner').classList.remove('show');
    localStorage.setItem('pwa_dismissed', '1');
}
</script>

@stack('scripts')
</body>
</html>